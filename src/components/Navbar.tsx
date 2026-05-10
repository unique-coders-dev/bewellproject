import { useState, useEffect } from 'react'
import { Leaf, Menu, Phone } from 'lucide-react'
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
  }, [])

  const handleNav = (page: string) => {
    onNavigate(page)
    setMobileOpen(false)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <header
      className={cn(
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        scrolled
          ? 'bg-background/95 backdrop-blur-sm border-b border-border shadow-sm'
          : 'bg-transparent'
      )}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <button
            onClick={() => handleNav('home')}
            className="flex items-center gap-2 focus:outline-none group"
          >
            <div className="flex items-center justify-center w-9 h-9 rounded-full bg-primary text-primary-foreground">
              <Leaf className="w-5 h-5" />
            </div>
            <div className="flex flex-col leading-tight">
              <span className="text-lg font-bold text-primary tracking-tight">BE WELL</span>
              <span className="text-[10px] text-muted-foreground uppercase tracking-widest -mt-0.5">Health &amp; Healing</span>
            </div>
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
                    ? 'text-primary bg-accent'
                    : scrolled
                    ? 'text-foreground hover:text-primary hover:bg-accent'
                    : 'text-white hover:text-white/80 hover:bg-white/10'
                )}
              >
                {link.label}
              </button>
            ))}
          </nav>

          {/* CTA + mobile menu */}
          <div className="flex items-center gap-2">
            <a href="tel:+88001700000000" className="hidden sm:flex items-center gap-1.5 text-sm text-muted-foreground hover:text-primary transition-colors">
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
                <Button variant="ghost" size="icon" className="lg:hidden">
                  <Menu className="w-5 h-5" />
                </Button>
              </SheetTrigger>
              <SheetContent side="right" className="w-72">
                <div className="flex items-center gap-2 mb-8 pt-2">
                  <div className="flex items-center justify-center w-9 h-9 rounded-full bg-primary text-primary-foreground">
                    <Leaf className="w-5 h-5" />
                  </div>
                  <div>
                    <div className="text-lg font-bold text-primary">BE WELL</div>
                    <div className="text-[10px] text-muted-foreground uppercase tracking-widest">Health &amp; Healing</div>
                  </div>
                </div>
                <nav className="flex flex-col gap-1">
                  {navLinks.map((link) => (
                    <button
                      key={link.id}
                      onClick={() => handleNav(link.id)}
                      className={cn(
                        'px-4 py-3 text-sm font-medium rounded-md text-left transition-colors',
                        currentPage === link.id
                          ? 'text-primary bg-accent'
                          : 'text-foreground hover:text-primary hover:bg-accent'
                      )}
                    >
                      {link.label}
                    </button>
                  ))}
                </nav>
                <div className="mt-6 pt-6 border-t border-border">
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
