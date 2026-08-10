/*
  # BeWell Application Tables

  Adds storage for the two forms that previously discarded their input:
  the Lifestyle Program application and the Work With Us job application.

  1. New Tables
    - `program_applications`
      - `id` (uuid, primary key)
      - `name` (text) - Applicant's full name
      - `email` (text) - Optional; the form does not require it
      - `phone` (text) - Required by the form, primary contact route
      - `condition` (text) - Primary health condition
      - `program_length` (text) - 'two-week', 'three-week', or 'unsure'
      - `message` (text) - Free-text "tell us about yourself"
      - `status` (text) - Staff triage: 'new', 'contacted', 'accepted', 'declined'
      - `created_at` (timestamptz)

    - `job_applications`
      - `id` (uuid, primary key)
      - `name` (text)
      - `email` (text)
      - `phone` (text)
      - `position` (text) - Which opening they are applying for
      - `experience` (text)
      - `motivation` (text)
      - `status` (text) - Staff triage: 'new', 'contacted', 'hired', 'declined'
      - `created_at` (timestamptz)

  2. Security
    - Enable RLS on both tables
    - Public may INSERT only, matching the existing `contact_messages` pattern
    - No public SELECT: applications contain health and personal data, so they
      are readable only via the Supabase dashboard or an authenticated session
*/

-- Lifestyle Program applications
CREATE TABLE IF NOT EXISTS program_applications (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text DEFAULT '',
  phone text NOT NULL,
  condition text NOT NULL DEFAULT '',
  program_length text DEFAULT '',
  message text DEFAULT '',
  status text NOT NULL DEFAULT 'new',
  created_at timestamptz DEFAULT now()
);

ALTER TABLE program_applications ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can submit a program application"
  ON program_applications FOR INSERT
  TO anon, authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated staff can read program applications"
  ON program_applications FOR SELECT
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS program_applications_created_at_idx
  ON program_applications (created_at DESC);

-- Job applications from the Work With Us page
CREATE TABLE IF NOT EXISTS job_applications (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text NOT NULL,
  phone text NOT NULL,
  position text DEFAULT '',
  experience text DEFAULT '',
  motivation text DEFAULT '',
  status text NOT NULL DEFAULT 'new',
  created_at timestamptz DEFAULT now()
);

ALTER TABLE job_applications ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can submit a job application"
  ON job_applications FOR INSERT
  TO anon, authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated staff can read job applications"
  ON job_applications FOR SELECT
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS job_applications_created_at_idx
  ON job_applications (created_at DESC);
