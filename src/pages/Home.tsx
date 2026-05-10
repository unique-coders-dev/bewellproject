import { useEffect, useState } from 'react'
import { ArrowRight, Heart, Leaf, Users, Sun, Shield, ChevronRight } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { TestimonialCard } from '@/components/TestimonialCard'
import { supabase, type Testimonial } from '@/lib/supabase'

interface HomeProps {
  onNavigate: (page: string) => void
}

const services = [
  {
    id: 'lifestyle',
    icon: Heart,
    title: 'Lifestyle Program',
    description: 'Two to three weeks of immersive care for heart disease, diabetes, cancer, high blood pressure, depression, and other lifestyle illnesses.',
    badge: 'Most Popular',
  },
  {
    id: 'training',
    icon: Users,
    title: 'Training Program',
    description: 'Comprehensive health education program teaching natural lifestyle medicine principles to healthcare workers and health-conscious individuals.',
    badge: null,
  },
  {
    id: 'hostel',
    icon: Sun,
    title: 'Hostel Services',
    description: 'Comfortable accommodation on our peaceful campus surrounded by hills, fruit trees, and flower gardens.',
    badge: null,
  },
  {
    id: 'farm',
    icon: Leaf,
    title: 'BE WELL Farm',
    description: 'Our best-practices farm grows organic fruits, vegetables, and herbs using sustainable methods. Fresh produce available for purchase.',
    badge: null,
  },
]

const conditions = [
  'Heart Disease', 'Diabetes', 'Cancer', 'High Blood Pressure',
  'Depression', 'Obesity', 'Arthritis', 'Digestive Issues',
]

const principles = [
  { icon: Sun, title: 'Sunlight', desc: 'Daily exposure to natural sunlight for healing and energy.' },
  { icon: Leaf, title: 'Nutrition', desc: 'Whole plant-based foods from our own farm and gardens.' },
  { icon: Heart, title: 'Exercise', desc: 'Gentle movement through our scenic hillside surroundings.' },
  { icon: Shield, title: 'Rest', desc: 'Deep restorative rest in a quiet, peaceful environment.' },
]

export function Home({ onNavigate }: HomeProps) {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])

  useEffect(() => {
    supabase
      .from('testimonials')
      .select('*')
      .eq('is_featured', true)
      .order('created_at', { ascending: false })
      .limit(3)
      .then(({ data }) => { if (data) setTestimonials(data) })
  }, [])

  const handleNav = (page: string) => {
    onNavigate(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: "url('https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1600&q=80&fit=crop')",
          }}
        />
        <div className="absolute inset-0 bg-foreground/55" />
        <div className="relative z-10 text-center px-4 sm:px-6 max-w-4xl mx-auto">
          <Badge className="mb-6 bg-primary/80 text-primary-foreground border-0 px-4 py-1.5 text-sm">
            Center of Health &amp; Healing
          </Badge>
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
            Find Healing in<br />
            <span className="text-primary">Nature's Embrace</span>
          </h1>
          <p className="text-lg sm:text-xl text-white/85 mb-8 max-w-2xl mx-auto leading-relaxed">
            BE WELL is a lifestyle program where persons suffering with serious illness may come and find healing in two or three weeks of special care, nestled in the beautiful hills near Choto Daragar Hat.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button size="lg" onClick={() => handleNav('lifestyle')} className="text-base px-8">
              Start Your Healing Journey
              <ArrowRight className="ml-2 w-5 h-5" />
            </Button>
            <Button
              size="lg"
              variant="outline"
              onClick={() => handleNav('contact')}
              className="text-base px-8 bg-white/10 text-white border-white/40 hover:bg-white/20 hover:text-white"
            >
              Contact Us
            </Button>
          </div>
        </div>
        <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
          <div className="w-6 h-10 rounded-full border-2 border-white/50 flex items-start justify-center pt-2">
            <div className="w-1 h-3 bg-white/70 rounded-full" />
          </div>
        </div>
      </section>

      {/* Conditions we treat */}
      <section className="py-10 bg-primary text-primary-foreground">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-wrap items-center justify-center gap-3">
            <span className="text-sm font-medium opacity-80 mr-2">We help with:</span>
            {conditions.map((condition) => (
              <Badge key={condition} variant="secondary" className="bg-primary-foreground/15 text-primary-foreground border-primary-foreground/20 text-sm px-3 py-1">
                {condition}
              </Badge>
            ))}
          </div>
        </div>
      </section>

      {/* About section */}
      <section className="py-20 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">About Us</Badge>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-6 leading-tight">
                Where Nature Meets<br />Modern Wellness
              </h2>
              <p className="text-muted-foreground leading-relaxed mb-4">
                Our campus is located in beautiful hills near Choto Daragar Hat. We have flowers and fruit trees growing in abundance, and are surrounded with nature on every side. This peaceful environment is itself part of the healing process.
              </p>
              <p className="text-muted-foreground leading-relaxed mb-6">
                At BE WELL, we believe the body has a remarkable capacity to heal itself when given the right conditions. Our expert team guides each guest through a comprehensive lifestyle change program that addresses the root causes of chronic illness.
              </p>
              <Button onClick={() => handleNav('lifestyle')} variant="outline">
                Learn About Our Program
                <ChevronRight className="ml-2 w-4 h-4" />
              </Button>
            </div>
            <div className="relative">
              <img
                src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&q=80&fit=crop"
                alt="Beautiful green hillside at BeWell campus"
                className="rounded-xl shadow-lg w-full object-cover aspect-[4/3]"
              />
              <div className="absolute -bottom-5 -left-5 bg-card border border-border rounded-xl p-4 shadow-lg">
                <div className="text-3xl font-bold text-primary">2–3</div>
                <div className="text-sm text-muted-foreground">Weeks to transformation</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Health Principles */}
      <section className="py-20 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Our Approach</Badge>
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">Natural Healing Principles</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              We use the eight laws of health — time-tested principles that the human body responds to powerfully.
            </p>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {principles.map((p) => (
              <Card key={p.title} className="text-center hover:shadow-md transition-shadow border-border">
                <CardContent className="p-6">
                  <div className="flex justify-center mb-4">
                    <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                      <p.icon className="w-6 h-6 text-primary" />
                    </div>
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">{p.title}</h3>
                  <p className="text-sm text-muted-foreground">{p.desc}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Services Grid */}
      <section className="py-20 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">What We Offer</Badge>
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">Our Programs &amp; Services</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              From intensive healing programs to training, lodging, and fresh farm produce — BE WELL is a complete wellness destination.
            </p>
          </div>
          <div className="grid sm:grid-cols-2 gap-6">
            {services.map((service) => (
              <Card
                key={service.id}
                className="cursor-pointer hover:shadow-lg transition-all border-border group"
                onClick={() => handleNav(service.id)}
              >
                <CardContent className="p-6">
                  <div className="flex items-start gap-4">
                    <div className="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-primary-foreground transition-colors">
                      <service.icon className="w-5 h-5 text-primary group-hover:text-primary-foreground" />
                    </div>
                    <div className="flex-1">
                      <div className="flex items-center gap-2 mb-1">
                        <h3 className="font-semibold text-foreground">{service.title}</h3>
                        {service.badge && (
                          <Badge className="text-xs bg-primary/10 text-primary border-0">{service.badge}</Badge>
                        )}
                      </div>
                      <p className="text-sm text-muted-foreground leading-relaxed">{service.description}</p>
                      <div className="flex items-center gap-1 mt-3 text-primary text-sm font-medium group-hover:gap-2 transition-all">
                        Learn more <ChevronRight className="w-4 h-4" />
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Campus image */}
      <section className="py-0">
        <div className="relative h-72 sm:h-96 overflow-hidden">
          <img
            src="https://images.unsplash.com/photo-1502082553048-f009c37129b9?w=1600&q=80&fit=crop"
            alt="Lush green nature surrounding BeWell campus"
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-foreground/40 flex items-center justify-center">
            <div className="text-center text-white px-4">
              <h2 className="text-2xl sm:text-3xl font-bold mb-2">Surrounded by Nature's Beauty</h2>
              <p className="text-white/80 max-w-xl">
                Our hillside campus features flowers, fruit trees, and fresh mountain air — nature's own medicine.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      {testimonials.length > 0 && (
        <section className="py-20 bg-muted">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Success Stories</Badge>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">Lives Transformed</h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                Hear from guests who found healing at BE WELL.
              </p>
            </div>
            <div className="grid md:grid-cols-3 gap-6">
              {testimonials.map((t) => (
                <TestimonialCard key={t.id} testimonial={t} />
              ))}
            </div>
            <div className="text-center mt-8">
              <Button variant="outline" onClick={() => handleNav('lifestyle')}>
                Read More Stories
                <ChevronRight className="ml-2 w-4 h-4" />
              </Button>
            </div>
          </div>
        </section>
      )}

      {/* CTA */}
      <section className="py-20 bg-primary text-primary-foreground">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl sm:text-4xl font-bold mb-4">Ready to Start Your Healing Journey?</h2>
          <p className="text-primary-foreground/80 text-lg mb-8 max-w-2xl mx-auto">
            Two to three weeks can change your life. Our team is ready to welcome you to our campus in the hills.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button
              size="lg"
              onClick={() => handleNav('lifestyle')}
              className="bg-primary-foreground text-primary hover:bg-primary-foreground/90 text-base px-8"
            >
              Apply for the Lifestyle Program
            </Button>
            <Button
              size="lg"
              variant="outline"
              onClick={() => handleNav('contact')}
              className="border-primary-foreground/40 text-primary-foreground hover:bg-primary-foreground/10 text-base px-8"
            >
              Contact Us First
            </Button>
          </div>
        </div>
      </section>
    </div>
  )
}
