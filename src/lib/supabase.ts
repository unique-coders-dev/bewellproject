import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL || 'https://placeholder.supabase.co'
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY || 'placeholder-key'

// Create client with fallback values to prevent initialization crash
export const supabase = createClient(supabaseUrl, supabaseAnonKey)

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
