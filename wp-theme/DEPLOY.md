# BE WELL — WordPress migration

The site moves from a Vite + React SPA to WordPress so Eugene and the staff have
real logins. The design is unchanged: `wp-theme/bewell/` is a hand-ported theme
that reproduces the existing canvassing.org pixel for pixel.

Nothing here touches the live site until the cutover in step 6. Until then
canvassing.org keeps serving the React build exactly as it does today.

---

## What is in the repo now

```
wp-theme/bewell/            the WordPress theme
  functions.php             bootstrap
  header.php  footer.php    site chrome, ported from Navbar.tsx / Footer.tsx
  front-page.php            Home
  page-lifestyle.php        Lifestyle Program   (+ application form)
  page-training.php         Training Program    (+ application form)
  page-hostel.php           Hostel Services
  page-farm.php             BE WELL Farm
  page-work.php             Work With Us        (+ job application form)
  page-contact.php          Contact             (+ contact form)
  page.php  index.php  404.php
  inc/                      setup, assets, db, roles, forms, admin, migrate
  src/tailwind.css          Tailwind source (not deployed)
  assets/css/theme.css      built stylesheet (generated, gitignored)
```

The React app under `src/` is untouched and still builds. Both stacks live side
by side until the cutover.

---

## 1. Things only you can do

I cannot create hosting resources or accounts. These are yours:

**a. Create the staging subdomain.**
hPanel → Domains → Subdomains → create `staging.canvassing.org`.

**b. Install WordPress on it.**
hPanel → Website → Auto Installer → WordPress, targeting
`staging.canvassing.org`. Set the admin account to yourself for now; Eugene's
account comes later.

**c. Create an FTP account scoped to the staging subdomain.**
hPanel → Files → FTP Accounts. Scope it to the staging directory only, the same
way `u174852759.canvassing.org` is scoped to production. Do not reuse the
production FTP account and do not use the SSH user `u174852759` — that one
reaches every site on the hosting account.

**d. Add three repository secrets.**
GitHub → Settings → Secrets and variables → Actions:

| Secret | Value |
| --- | --- |
| `FTP_SERVER` | already set — reuse it |
| `FTP_STAGING_USERNAME` | the FTP user from (c) |
| `FTP_STAGING_PASSWORD` | its password |

---

## 2. Get the theme onto staging

Either route works.

**Automatic.** Push to `main`. `.github/workflows/deploy-wp-theme.yml` lints
every PHP file, builds the stylesheet, checks it is not empty, and uploads to
`wp-content/themes/bewell/`.

**Manual.** Run `npm run theme:package` and upload the resulting
`bewell-theme.zip` via Appearance → Themes → Add New → Upload Theme.

Then activate it: Appearance → Themes → BE WELL → Activate.

**Activation does all the setup for you.** It creates the seven pages with the
right templates and slugs, sets Home as the front page, switches on pretty
permalinks, creates the four submission tables, adds the "BE WELL Staff" role,
and turns public registration off. Activating a second time changes nothing —
every step checks before it acts.

---

## 3. Upload the photographs

The theme references the existing library by root-relative path, exactly as the
React site did — `/images/buildings/IMG_3865.JPG` and so on. Copy `public/images/`
from production into the staging site's `public_html/images/`.

If you would rather serve them from somewhere else, every reference goes through
one function. Drop this in a small plugin and no template needs editing:

```php
add_filter( 'bewell_image_base', fn() => 'https://canvassing.org/images' );
```

> **Worth fixing while you are in here.** That library is ~223 MB of unresized
> camera JPEGs — the four images on the home page alone are about 2.5 MB, and
> the hero is 468 KB. Running them through an image optimiser before upload
> would be the single biggest speed win on the site. It was outstanding before
> this migration and it still is; I have not changed it, because resampling
> someone's photographs is their call, not mine.

---

## 4. Accounts

Users → Add New.

| Person | Role | Can |
| --- | --- | --- |
| Eugene | Administrator | everything, including installing plugins |
| Minhaz | BE WELL Staff | read and triage submissions, edit pages and media |
| — | — | Staff **cannot** reach Settings, Plugins, Users, Tools or Themes |

Have each of them set their own password through the "Set New Password" email
rather than you choosing one for them.

> **If you cannot see the Users menu, you are signed in as staff.** It is an
> Administrator-only screen — standard WordPress, nothing here removed it. Staff
> get a 403 on `users.php` and `user-new.php` on purpose: a staff login should
> not be able to create an Administrator account. Sign in as Eugene to add
> people.

Public registration is off, and stays off: the theme also refuses registration
at the `registration_errors` filter, so flipping the setting back on by accident
does not open the door.

---

## 5. Bring the Supabase data across

Two commands, depending on what you can reach.

**Testimonials and farm products** are readable with the publishable key. I
already checked the live project (`lqpzqrwfmvolyzfhgvve`) and **both tables are
empty**, so unless something has been added since, there is nothing to move.

**The three application tables cannot be read with the publishable key** — by
design; they have INSERT-only policies for `anon`, which is what kept health
data out of the browser bundle. Use either:

```bash
# Everything at once, with the service_role key from the Supabase dashboard
wp bewell pull --supabase-url=https://lqpzqrwfmvolyzfhgvve.supabase.co --key=<service_role key> --dry-run
```

```bash
# Or export CSVs from the Supabase table editor and import them
wp bewell import-csv --type=program --file=program_applications_rows.csv
wp bewell import-csv --type=job     --file=job_applications_rows.csv
wp bewell import-csv --type=contact --file=contact_messages_rows.csv
```

Both support `--dry-run`, both are safe to re-run — rows already present are
skipped — and both preserve the original submission dates and triage statuses.
The `condition` column is renamed to `health_condition` on the way in.

Treat the service_role key as a password: use it once from your own machine and
do not put it in the repo or in a GitHub secret.

---

## 6. Cutover

Only after you and Eugene have reviewed staging.

1. **Disable the React deploy first.** In `.github/workflows/deploy.yml`, remove
   the `push` trigger (leave `workflow_dispatch`). This is the step that will
   bite you if you skip it: that workflow uploads `dist/` to production's
   `public_html` root, so the next merge to `main` would drop a React
   `index.html` next to WordPress's `index.php` — and most servers serve
   `index.html` first. The site would silently revert to the old build.
2. Take a full backup of production `public_html` and of the staging database.
3. Move WordPress from the staging subdomain to `canvassing.org` — hPanel's
   auto-installer clone, or copy the files and database and update `siteurl` and
   `home`.
4. Point `FTP_STAGING_*` at production, or rename the secrets, so future theme
   deploys land on the live site.
5. Walk the checklist below.

---

## 7. Post-cutover checklist

- [ ] All seven pages load and look right
- [ ] Submit each of the four forms and confirm the row appears under **BE WELL**
- [ ] Confirm the notification email arrives (Hostinger mail can need SMTP configuring)
- [ ] Eugene can log in and edit a page
- [ ] Minhaz can log in, see submissions, and **cannot** see Settings or Plugins
- [ ] `canvassing.org/wp-admin` reachable; `/#lifestyle`-style old links land on Home
- [ ] Correct the placeholder phone number and the two unverified email addresses
      in Appearance → Customize → Contact Details
- [ ] Install a backup plugin — there is no automatic rollback on FTP deploys

---

## Working on the theme locally

```bash
npm install
npm run theme:watch     # rebuild CSS on save
npm run theme:lint      # php -l over every file
npm run theme:package   # zip for manual upload
```

The stylesheet is generated from `src/tailwind.css` and is gitignored. If the
site ever renders as unstyled HTML, that build did not run — the dashboard shows
an explicit error notice when `assets/css/theme.css` is missing.

---

## Two things worth knowing before you edit

**Form fields are prefixed `bw_`, and must stay that way.**
`WP::parse_request()` reads `$_POST` for every public query var. An input named
plainly `name` is taken as the post-slug query var: WordPress hunts for a post
slugged with whatever the visitor typed, finds none, and serves a 404 instead of
the page. The form appears to break for no reason. `name`, `title`, `s`, `page`,
`author` and dozens more are all live query vars, so every field goes through
`bewell_field_name()`.

**The admin-bar offset lives in the utilities layer, not components.**
The header is `position: fixed; top: 0`, so it ignores the `margin-top` that
WordPress adds for the admin bar and hides under it for every logged-in user.
The fix is `.admin-bar .bw-header { top: 32px }` — and it has to sit in
`@layer utilities`, because the header also carries Tailwind's `top-0` and
cascade-layer order beats selector specificity.
