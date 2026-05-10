/*
  # BeWell Website Tables

  1. New Tables
    - `testimonials`
      - `id` (uuid, primary key)
      - `name` (text) - Person's name
      - `role` (text) - Their role/title or condition treated
      - `content` (text) - Testimonial text
      - `program_type` (text) - 'lifestyle', 'training', 'hostel', or 'general'
      - `is_featured` (boolean) - Show on landing page
      - `created_at` (timestamptz)

    - `farm_products`
      - `id` (uuid, primary key)
      - `name` (text) - Product name
      - `description` (text) - Product description
      - `category` (text) - e.g. 'fruits', 'vegetables', 'grains', 'herbs'
      - `price` (text) - Price as text (e.g. "120 BDT/kg")
      - `unit` (text) - e.g. 'kg', 'bunch', 'piece'
      - `is_available` (boolean) - Currently available
      - `image_url` (text) - Optional image URL
      - `created_at` (timestamptz)

    - `contact_messages`
      - `id` (uuid, primary key)
      - `name` (text)
      - `email` (text)
      - `subject` (text)
      - `message` (text)
      - `created_at` (timestamptz)

  2. Security
    - Enable RLS on all tables
    - Public can read testimonials and farm_products
    - Public can insert contact_messages
    - No public insert on testimonials or farm_products (admin only)
*/

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  role text DEFAULT '',
  content text NOT NULL,
  program_type text NOT NULL DEFAULT 'general',
  is_featured boolean DEFAULT false,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE testimonials ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can read testimonials"
  ON testimonials FOR SELECT
  TO anon, authenticated
  USING (true);

-- Farm products table
CREATE TABLE IF NOT EXISTS farm_products (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  description text DEFAULT '',
  category text NOT NULL DEFAULT 'general',
  price text DEFAULT '',
  unit text DEFAULT '',
  is_available boolean DEFAULT true,
  image_url text DEFAULT '',
  created_at timestamptz DEFAULT now()
);

ALTER TABLE farm_products ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can read farm products"
  ON farm_products FOR SELECT
  TO anon, authenticated
  USING (true);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text NOT NULL,
  subject text NOT NULL,
  message text NOT NULL,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE contact_messages ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can submit contact messages"
  ON contact_messages FOR INSERT
  TO anon, authenticated
  WITH CHECK (true);
