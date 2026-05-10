import { useEffect, useState } from 'react'
import { Leaf, Star, MapPin, Check } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { TestimonialCard } from '@/components/TestimonialCard'
import { supabase, type Testimonial } from '@/lib/supabase'

interface HostelProps {
  onNavigate: (page: string) => void
}

const amenities = [
  'Clean, comfortable private rooms',
  'Fresh plant-based meals included',
  'Daily room cleaning',
  'Nature walking trails nearby',
  'Quiet prayer and meditation areas',
  'Fresh filtered water',
  'Beautiful garden views',
  'Proximity to the BeWell campus',
]

const roomTypes = [
  {
    title: 'Single Room',
    description: 'A peaceful private room with garden view, ideal for individuals attending our programs.',
    image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80&fit=crop',
    features: ['Private room', 'Single bed', 'Natural light', 'Clean bathroom'],
  },
  {
    title: 'Double Room',
    description: 'Perfect for couples or two guests attending together. Shared spaces for relaxation.',
    image: 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=600&q=80&fit=crop',
    features: ['Private room', 'Two beds', 'Natural light', 'Clean bathroom'],
  },
  {
    title: 'Family Room',
    description: 'Spacious accommodation for families with children attending the program together.',
    image: 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?w=600&q=80&fit=crop',
    features: ['Larger room', 'Multiple beds', 'Family friendly', 'Extra space'],
  },
]

const galleryImages = [
  { src: 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=600&q=80&fit=crop', alt: 'Hostel garden view' },
  { src: 'https://images.unsplash.com/photo-1586798271654-0471bb1b0517?w=600&q=80&fit=crop', alt: 'Peaceful surroundings' },
  { src: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=600&q=80&fit=crop', alt: 'Nature trails' },
  { src: 'https://images.unsplash.com/photo-1490750967868-88df5691cc53?w=600&q=80&fit=crop', alt: 'Flowers on campus' },
  { src: 'https://images.unsplash.com/photo-1444392061186-9fc38f16d8d4?w=600&q=80&fit=crop', alt: 'Fruit trees' },
  { src: 'https://images.unsplash.com/photo-1437719417032-8595fd9e9dc6?w=600&q=80&fit=crop', alt: 'Sunrise view from campus' },
]

export function HostelServices({ onNavigate }: HostelProps) {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        const { data } = await supabase
          .from('testimonials')
          .select('*')
          .eq('program_type', 'hostel')
          .order('created_at', { ascending: false })

        if (data && data.length > 0) {
          setTestimonials(data)
        } else {
          // Fallback mock data
          setTestimonials([
            {
              id: 'h1',
              name: 'John Peterson',
              role: 'Recovery Guest',
              content: 'The peace and quiet at the BE WELL hostel was exactly what I needed. Waking up to the sound of birds and the smell of fruit trees is something I\'ll never forget.',
              program_type: 'hostel',
              is_featured: true,
              created_at: new Date().toISOString()
            },
            {
              id: 'h2',
              name: 'Sarah Milles',
              role: 'Family Guest',
              content: 'We stayed as a family while my husband attended the program. The rooms were spotless, and the staff treated us like family. Highly recommend for anyone seeking a true retreat.',
              program_type: 'hostel',
              is_featured: true,
              created_at: new Date().toISOString()
            }
          ])
        }
      } catch (error) {
        setTestimonials([
          {
            id: 'h1',
            name: 'John Peterson',
            role: 'Recovery Guest',
            content: 'The peace and quiet at the BE WELL hostel was exactly what I needed. Waking up to the sound of birds and the smell of fruit trees is something I\'ll never forget.',
            program_type: 'hostel',
            is_featured: true,
            created_at: new Date().toISOString()
          },
          {
            id: 'h2',
            name: 'Sarah Milles',
            role: 'Family Guest',
            content: 'We stayed as a family while my husband attended the program. The rooms were spotless, and the staff treated us like family. Highly recommend for anyone seeking a true retreat.',
            program_type: 'hostel',
            is_featured: true,
            created_at: new Date().toISOString()
          }
        ])
      }
    }

    fetchTestimonials()
  }, [])

  const handleNav = (page: string) => {
    onNavigate(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative pt-16 min-h-[55vh] flex items-center">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: "url('https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1400&q=80&fit=crop')" }}
        />
        <div className="absolute inset-0 bg-foreground/55" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <Badge className="mb-4 bg-primary/80 text-primary-foreground border-0">Hostel Services</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
            Rest &amp; Recover in<br />
            <span className="text-primary">Natural Tranquility</span>
          </h1>
          <p className="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
            Our hillside hostel provides peaceful, comfortable accommodation for program participants and guests seeking rest in a natural healing environment.
          </p>
          <div className="flex gap-6 flex-wrap">
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Leaf className="w-4 h-4 text-primary" />
              Nature surroundings
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Star className="w-4 h-4 text-primary" />
              Meals included
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <MapPin className="w-4 h-4 text-primary" />
              Near Choto Daragar Hat
            </div>
          </div>
        </div>
      </section>

      {/* Amenities */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Facilities</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">A Peaceful Home Away From Home</h2>
              <p className="text-muted-foreground mb-6 leading-relaxed">
                Our hostel is designed to support healing. From the clean, simply furnished rooms to the beautiful garden pathways, every detail promotes rest and restoration.
              </p>
              <div className="grid sm:grid-cols-2 gap-2.5">
                {amenities.map((item) => (
                  <div key={item} className="flex items-center gap-2 text-sm text-foreground">
                    <Check className="w-4 h-4 text-primary shrink-0" />
                    {item}
                  </div>
                ))}
              </div>
            </div>
            <div className="grid grid-cols-2 gap-3">
              {galleryImages.slice(0, 4).map((img, i) => (
                <img
                  key={i}
                  src={img.src}
                  alt={img.alt}
                  className="rounded-lg w-full object-cover aspect-square"
                />
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Room Types */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Accommodation</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Room Options</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              We offer several room types to suit individuals, couples, and families.
            </p>
          </div>
          <div className="grid md:grid-cols-3 gap-6">
            {roomTypes.map((room) => (
              <Card key={room.title} className="overflow-hidden border-border hover:shadow-md transition-shadow">
                <img
                  src={room.image}
                  alt={room.title}
                  className="w-full h-48 object-cover"
                />
                <CardContent className="p-5">
                  <h3 className="font-semibold text-foreground text-lg mb-2">{room.title}</h3>
                  <p className="text-sm text-muted-foreground mb-4 leading-relaxed">{room.description}</p>
                  <ul className="space-y-1.5">
                    {room.features.map((f) => (
                      <li key={f} className="flex items-center gap-2 text-xs text-foreground">
                        <Check className="w-3.5 h-3.5 text-primary shrink-0" />
                        {f}
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Photo gallery strip */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold text-foreground mb-3">Our Campus</h2>
            <p className="text-muted-foreground">Flowers, fruit trees, and nature on every side.</p>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
            {galleryImages.map((img, i) => (
              <img
                key={i}
                src={img.src}
                alt={img.alt}
                className="rounded-lg w-full object-cover aspect-[4/3]"
              />
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      {testimonials.length > 0 && (
        <section className="py-16 bg-muted">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-10">
              <h2 className="text-3xl font-bold text-foreground mb-4">Guest Experiences</h2>
            </div>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {testimonials.map((t) => (
                <TestimonialCard key={t.id} testimonial={t} />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* CTA */}
      <section className="py-16 bg-primary text-primary-foreground">
        <div className="max-w-3xl mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">Ready to Book Your Stay?</h2>
          <p className="text-primary-foreground/80 mb-6">
            Contact us to check availability and learn about hostel rates. Accommodation is included for all program participants.
          </p>
          <Button
            onClick={() => handleNav('contact')}
            className="bg-primary-foreground text-primary hover:bg-primary-foreground/90"
            size="lg"
          >
            Contact Us for Booking
          </Button>
        </div>
      </section>
    </div>
  )
}
