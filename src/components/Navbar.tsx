import { useState, useEffect } from 'react'
import { Menu, Phone } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet'
import { cn } from '@/lib/utils'

interface NavbarProps {
  currentPage: string
  onNavigate: (page: string) => void
}

const navLinks = [
  { id: 'home', label: 'Home' },
  { id: 'lifestyle', label: 'Lifestyle Program' },
  { id: 'training', label: 'Training' },
  { id: 'hostel', label: 'Hostel' },
  { id: 'farm', label: 'Farm' },
  { id: 'work', label: 'Work With Us' },
  { id: 'contact', label: 'Contact' },
]

export function Navbar({ currentPage, onNavigate }: NavbarProps) {
  const [scrolled, setScrolled] = useState(false)
  const [mobileOpen, setMobileOpen] = useState(false)

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 20)
    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
    console.log(scrolled)
  }, [])

  const handleNav = (page: string) => {
    onNavigate(page)
    setMobileOpen(false)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <header
      className="fixed top-0 left-0 right-0 z-50 bg-white border-b border-border shadow-sm transition-all duration-300"
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <button
            onClick={() => handleNav('home')}
            className="flex items-center gap-2 focus:outline-none group"
          >
            <img src="/logo.png" alt="BE WELL" className="h-10 w-auto object-contain" />
          </button>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navLinks.map((link) => (
              <button
                key={link.id}
                onClick={() => handleNav(link.id)}
                className={cn(
                  'px-3 py-2 text-sm font-medium rounded-md transition-colors',
                  currentPage === link.id
                    ? 'text-primary bg-primary/10'
                    : 'text-slate-700 hover:text-primary hover:bg-slate-100'
                )}
              >
                {link.label}
              </button>
            ))}
          </nav>

          {/* CTA + mobile menu */}
          <div className="flex items-center gap-2">
            <a href="tel:+88001700000000" className="hidden sm:flex items-center gap-1.5 text-sm text-slate-500 hover:text-primary transition-colors">
              <Phone className="w-4 h-4" />
              <span className="hidden md:inline">Call Us</span>
            </a>
            <Button
              size="sm"
              onClick={() => handleNav('lifestyle')}
              className="hidden sm:flex"
            >
              Apply Now
            </Button>

            {/* Mobile hamburger */}
            <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
              <SheetTrigger asChild>
                <Button variant="ghost" size="icon" className="lg:hidden text-slate-700 hover:bg-slate-100">
                  <Menu className="w-5 h-5" />
                </Button>
              </SheetTrigger>
              <SheetContent side="right" className="w-72 bg-white border-l border-slate-200">
                <div className="flex items-center gap-2 mb-8 pt-2">
                  <img src="/logo.png" alt="BE WELL" className="h-10 w-auto object-contain" />
                </div>
                <nav className="flex flex-col gap-1">
                  {navLinks.map((link) => (
                    <button
                      key={link.id}
                      onClick={() => handleNav(link.id)}
                      className={cn(
                        'px-4 py-3 text-sm font-medium rounded-md text-left transition-colors',
                        currentPage === link.id
                          ? 'text-primary bg-primary/10'
                          : 'text-slate-700 hover:text-primary hover:bg-slate-100'
                      )}
                    >
                      {link.label}
                    </button>
                  ))}
                </nav>
                <div className="mt-6 pt-6 border-t border-slate-200">
                  <Button className="w-full" onClick={() => handleNav('contact')}>
                    Contact Us
                  </Button>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>
      </div>
    </header>
  )
}
