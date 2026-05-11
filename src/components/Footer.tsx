import { MapPin, Phone, Mail, Globe, CirclePlay as PlayCircle } from 'lucide-react'
import { Separator } from '@/components/ui/separator'

interface FooterProps {
  onNavigate: (page: string) => void
}

export function Footer({ onNavigate }: FooterProps) {
  const handleNav = (page: string) => {
    onNavigate(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <footer className="bg-foreground text-background">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* Brand */}
          <div className="lg:col-span-1">
            <div className="flex items-center gap-2 mb-4">
              <div className="bg-white p-1 rounded-md">
                <img src="/logo.png" alt="BE WELL" className="h-10 w-auto object-contain" />
              </div>
            </div>
            <p className="text-sm text-background/70 leading-relaxed mb-4">
              A center of health and healing nestled in the beautiful hills near Choto Daragar Hat. Transforming lives through natural lifestyle medicine.
            </p>
            <div className="flex items-center gap-3">
              <a href="#" aria-label="Website" className="text-background/60 hover:text-primary transition-colors">
                <Globe className="w-5 h-5" />
              </a>
              <a href="#" aria-label="Videos" className="text-background/60 hover:text-primary transition-colors">
                <PlayCircle className="w-5 h-5" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-sm font-semibold text-background uppercase tracking-widest mb-4">Programs</h3>
            <ul className="space-y-2">
              {[
                { id: 'lifestyle', label: 'Lifestyle Program' },
                { id: 'training', label: 'Training Program' },
                { id: 'hostel', label: 'Hostel Services' },
                { id: 'farm', label: 'Our Farm' },
              ].map((link) => (
                <li key={link.id}>
                  <button
                    onClick={() => handleNav(link.id)}
                    className="text-sm text-background/70 hover:text-primary transition-colors"
                  >
                    {link.label}
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {/* Company */}
          <div>
            <h3 className="text-sm font-semibold text-background uppercase tracking-widest mb-4">Company</h3>
            <ul className="space-y-2">
              {[
                { id: 'home', label: 'About Us' },
                { id: 'work', label: 'Work With Us' },
                { id: 'contact', label: 'Contact Us' },
              ].map((link) => (
                <li key={link.id}>
                  <button
                    onClick={() => handleNav(link.id)}
                    className="text-sm text-background/70 hover:text-primary transition-colors"
                  >
                    {link.label}
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="text-sm font-semibold text-background uppercase tracking-widest mb-4">Find Us</h3>
            <ul className="space-y-3">
              <li className="flex items-start gap-2.5 text-sm text-background/70">
                <MapPin className="w-4 h-4 mt-0.5 shrink-0 text-primary" />
                <span>Near Choto Daragar Hat, Beautiful Hills, Bangladesh</span>
              </li>
              <li className="flex items-center gap-2.5 text-sm text-background/70">
                <Phone className="w-4 h-4 shrink-0 text-primary" />
                <a href="tel:+88001700000000" className="hover:text-primary transition-colors">+880 17 0000-0000</a>
              </li>
              <li className="flex items-center gap-2.5 text-sm text-background/70">
                <Mail className="w-4 h-4 shrink-0 text-primary" />
                <a href="mailto:info@bewell.org" className="hover:text-primary transition-colors">info@bewell.org</a>
              </li>
            </ul>
          </div>
        </div>

        <Separator className="my-8 bg-background/10" />

        <div className="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-background/50">
          <p>&copy; {new Date().getFullYear()} BE WELL ALWAYS LTD. All rights reserved.</p>
          <p>Center of Health &amp; Healing</p>
        </div>
      </div>
    </footer>
  )
}
