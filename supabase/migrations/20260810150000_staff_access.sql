/*
  # Staff access for the admin page

  The application tables previously granted SELECT to any `authenticated` user.
  That is too broad: if email sign-ups are ever enabled, anyone who registers
  becomes `authenticated` and could read applications containing health data.

  This migration narrows access to an explicit allowlist.

  1. New Tables
    - `staff`
      - `user_id` (uuid, PK) - references auth.users
      - `email` (text) - convenience copy for reading the table by eye
      - `created_at` (timestamptz)

  2. New Functions
    - `is_staff()` - security definer, returns whether the caller is on the
      allowlist. Security definer is required so the check itself is not
      subject to RLS on `staff`.

  3. Security
    - Replaces the broad `TO authenticated` read policies with `is_staff()`
    - Adds UPDATE for staff so the admin page can set a triage status
    - Adds staff read access to `contact_messages`, which previously had
      insert-only policies and so was unreadable by the admin page
    - Being merely logged in grants nothing; a row in `staff` is required
*/

CREATE TABLE IF NOT EXISTS staff (
  user_id uuid PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  email text DEFAULT '',
  created_at timestamptz DEFAULT now()
);

ALTER TABLE staff ENABLE ROW LEVEL SECURITY;

CREATE OR REPLACE FUNCTION public.is_staff()
RETURNS boolean
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT EXISTS (SELECT 1 FROM staff WHERE user_id = auth.uid())
$$;

DROP POLICY IF EXISTS "Staff can read the staff list" ON staff;
CREATE POLICY "Staff can read the staff list"
  ON staff FOR SELECT
  TO authenticated
  USING (user_id = auth.uid());

-- Program applications: swap the broad authenticated policy for the allowlist
DROP POLICY IF EXISTS "Authenticated staff can read program applications" ON program_applications;
CREATE POLICY "Staff can read program applications"
  ON program_applications FOR SELECT
  TO authenticated
  USING (public.is_staff());

DROP POLICY IF EXISTS "Staff can update program applications" ON program_applications;
CREATE POLICY "Staff can update program applications"
  ON program_applications FOR UPDATE
  TO authenticated
  USING (public.is_staff())
  WITH CHECK (public.is_staff());

-- Job applications
DROP POLICY IF EXISTS "Authenticated staff can read job applications" ON job_applications;
CREATE POLICY "Staff can read job applications"
  ON job_applications FOR SELECT
  TO authenticated
  USING (public.is_staff());

DROP POLICY IF EXISTS "Staff can update job applications" ON job_applications;
CREATE POLICY "Staff can update job applications"
  ON job_applications FOR UPDATE
  TO authenticated
  USING (public.is_staff())
  WITH CHECK (public.is_staff());

-- Contact messages had insert-only policies, so staff could not read them
DROP POLICY IF EXISTS "Staff can read contact messages" ON contact_messages;
CREATE POLICY "Staff can read contact messages"
  ON contact_messages FOR SELECT
  TO authenticated
  USING (public.is_staff());
