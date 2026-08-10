import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY

// True only when both build-time variables were actually supplied. Forms check
// this before claiming a submission succeeded — without it a missing .env means
// visitors are told their application was received when nothing was stored.
export const isSupabaseConfigured = Boolean(supabaseUrl && supabaseAnonKey)

if (!isSupabaseConfigured) {
  console.error(
    'Supabase is not configured: VITE_SUPABASE_URL and VITE_SUPABASE_ANON_KEY are missing from the build. ' +
      'Form submissions will be rejected rather than silently discarded.'
  )
}

// Fallback values keep createClient from throwing at module load; every caller
// must still gate on isSupabaseConfigured before trusting a result.
export const supabase = createClient(
  supabaseUrl || 'https://placeholder.supabase.co',
  supabaseAnonKey || 'placeholder-key'
)

export interface Testimonial {
  id: string
  name: string
  role: string
  content: string
  program_type: string
  is_featured: boolean
  created_at: string
}

export interface FarmProduct {
  id: string
  name: string
  description: string
  category: string
  price: string
  unit: string
  is_available: boolean
  image_url: string
  created_at: string
}

export interface ProgramApplication {
  id: string
  name: string
  email: string
  phone: string
  condition: string
  program_length: string
  message: string
  status: string
  created_at: string
}

export interface JobApplication {
  id: string
  name: string
  email: string
  phone: string
  position: string
  experience: string
  motivation: string
  status: string
  created_at: string
}
