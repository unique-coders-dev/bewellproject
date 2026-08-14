# bewellproject

The website for **BE WELL**, a lifestyle-medicine health centre near Choto
Daragar Hat, Bangladesh, founded by Eugene and Heidi Prewitt. It is served at
[canvassing.org](https://canvassing.org) — the domain name is historical and has
nothing to do with canvassing.

## Two stacks, on purpose

The site is being moved to WordPress so Eugene and the staff have real logins.
Both stacks are in this repo during the transition:

| | Stack | Deployed by | Where |
| --- | --- | --- | --- |
| **Current** | Vite + React 19 + shadcn/ui (`src/`) | `.github/workflows/deploy.yml` | canvassing.org |
| **Incoming** | WordPress theme (`wp-theme/bewell/`) | `.github/workflows/deploy-wp-theme.yml` | staging.canvassing.org |

The WordPress theme is a hand-port of the React site — same markup, same
Tailwind classes, same palette — so the visual design does not change.

**Read [`wp-theme/DEPLOY.md`](wp-theme/DEPLOY.md) before touching either
deployment.** It covers the staging setup, the data migration, and the cutover.
The one thing that will bite you: `deploy.yml` uploads `dist/` to production's
document root, so it must be disabled *before* WordPress goes live there, or the
next push silently restores the React build.

## Commands

```bash
npm install

# WordPress theme
npm run theme:build      # compile Tailwind into the theme
npm run theme:watch      # rebuild on save
npm run theme:lint       # php -l over every theme file
npm run theme:package    # bewell-theme.zip, ready to upload

# React site (still live)
npm run dev
npm run build
npm run typecheck
```

## WP-CLI commands the theme adds

```bash
wp bewell pull --supabase-url=<url> --key=<key> [--dry-run]
wp bewell import-csv --type=<program|job|contact|training|testimonials|products> --file=<csv> [--dry-run]
```

Both migrate data out of the old Supabase project and are safe to re-run.

## Where the data lives

Form submissions go to four custom tables (`wp_bewell_*`) rather than post types:
they carry health and contact details, and a custom table starts private instead
of having to be locked down in a dozen places. Staff read them under **BE WELL**
in the dashboard. Testimonials and farm products *are* content, so those are
custom post types Eugene can edit normally.

[![Open in Bolt](https://bolt.new/static/open-in-bolt.svg)](https://bolt.new/~/sb1-2tpzhjd5)
