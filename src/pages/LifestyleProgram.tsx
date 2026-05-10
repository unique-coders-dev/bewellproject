import { useEffect, useState } from 'react'
import { Check, Clock, Users, Leaf, ArrowRight } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { TestimonialCard } from '@/components/TestimonialCard'
import { supabase, type Testimonial } from '@/lib/supabase'

interface LifestyleProps {
  onNavigate?: (page: string) => void
}

const included = [
  'Full medical assessment on arrival',
  'Personalized lifestyle treatment plan',
  'Plant-based whole food meals, 3x daily',
  'Daily health lectures and education',
  'Guided nature walks in the hills',
  'Hydrotherapy treatments',
  'Individual counseling sessions',
  'Physical activity program',
  'Discharge plan and follow-up guidance',
  'Accommodation in our peaceful hostel',
]

const conditions = [
  'Heart Disease', 'Type 2 Diabetes', 'High Blood Pressure',
  'Cancer (supportive care)', 'Depression & Anxiety', 'Obesity',
  'Chronic Fatigue', 'Digestive Disorders', 'Arthritis',
  'Kidney Disease', 'Liver Conditions', 'Other Lifestyle Illnesses',
]

const pricing = [
  {
    name: 'Two-Week Program',
    duration: '14 nights / 15 days',
    price: 'Contact for pricing',
    features: ['All meals included', 'Accommodation', 'Medical consultation', 'Daily programs', 'Follow-up plan'],
    recommended: false,
  },
  {
    name: 'Three-Week Program',
    duration: '21 nights / 22 days',
    price: 'Contact for pricing',
    features: ['All meals included', 'Accommodation', 'Medical consultation', 'Daily programs', 'Follow-up plan', 'Additional deep-dive sessions', 'Extended counseling'],
    recommended: true,
  },
]

export function LifestyleProgram({ onNavigate: _onNavigate }: LifestyleProps) {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])
  const [formData, setFormData] = useState({
    name: '', email: '', phone: '', condition: '', program: '', message: ''
  })
  const [submitted, setSubmitted] = useState(false)

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        const { data } = await supabase
          .from('testimonials')
          .select('*')
          .eq('program_type', 'lifestyle')
          .order('created_at', { ascending: false })

        if (data && data.length > 0) {
          setTestimonials(data)
        } else {
          // Fallback mock data
          setTestimonials([
            {
              id: 'l1',
              name: 'James Wilson',
              role: 'Recovery from Depression',
              content: 'The Lifestyle Program at BE WELL gave me the tools I needed to overcome deep-seated depression. The connection with nature and the structured daily routine were life-saving.',
              program_type: 'lifestyle',
              is_featured: true,
              created_at: new Date().toISOString()
            },
            {
              id: 'l2',
              name: 'Maria Garcia',
              role: 'Weight Loss Success',
              content: 'I lost 8kg in three weeks, but more importantly, I learned how to eat and live to keep it off. My energy levels haven\'t been this high in years.',
              program_type: 'lifestyle',
              is_featured: true,
              created_at: new Date().toISOString()
            }
          ])
        }
      } catch (error) {
        setTestimonials([
          {
            id: 'l1',
            name: 'James Wilson',
            role: 'Recovery from Depression',
            content: 'The Lifestyle Program at BE WELL gave me the tools I needed to overcome deep-seated depression. The connection with nature and the structured daily routine were life-saving.',
            program_type: 'lifestyle',
            is_featured: true,
            created_at: new Date().toISOString()
          },
          {
            id: 'l2',
            name: 'Maria Garcia',
            role: 'Weight Loss Success',
            content: 'I lost 8kg in three weeks, but more importantly, I learned how to eat and live to keep it off. My energy levels haven\'t been this high in years.',
            program_type: 'lifestyle',
            is_featured: true,
            created_at: new Date().toISOString()
          }
        ])
      }
    }

    fetchTestimonials()
  }, [])

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setSubmitted(true)
    setFormData({ name: '', email: '', phone: '', condition: '', program: '', message: '' })
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative pt-16 min-h-[60vh] flex items-center">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: "url('https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1400&q=80&fit=crop')" }}
        />
        <div className="absolute inset-0 bg-foreground/55" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <Badge className="mb-4 bg-primary/80 text-primary-foreground border-0">Lifestyle Program</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
            Reverse Chronic Illness<br />
            <span className="text-primary">Naturally</span>
          </h1>
          <p className="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
            A focused two to three week intensive program where your body learns to heal. Expert care, natural food, fresh air, and daily education.
          </p>
          <div className="flex gap-6 flex-wrap">
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Clock className="w-4 h-4 text-primary" />
              2–3 weeks residential
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Users className="w-4 h-4 text-primary" />
              Small groups, personal care
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Leaf className="w-4 h-4 text-primary" />
              All meals included
            </div>
          </div>
        </div>
      </section>

      {/* Conditions */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Who We Help</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">Conditions We Address</h2>
              <p className="text-muted-foreground mb-6 leading-relaxed">
                Our program has helped hundreds of people with serious lifestyle-related illnesses. Using a comprehensive approach, we help the body heal from the inside out.
              </p>
              <div className="grid grid-cols-2 gap-2">
                {conditions.map((c) => (
                  <div key={c} className="flex items-center gap-2 text-sm text-foreground">
                    <Check className="w-4 h-4 text-primary shrink-0" />
                    {c}
                  </div>
                ))}
              </div>
            </div>
            <div>
              <img
                src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=800&q=80&fit=crop"
                alt="Fresh healthy food at BeWell"
                className="rounded-xl shadow-lg w-full object-cover aspect-[4/3]"
              />
            </div>
          </div>
        </div>
      </section>

      {/* What's Included */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Program Details</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">What Is Included</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Everything you need for a complete healing experience is provided.
            </p>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
            {included.map((item) => (
              <div key={item} className="flex items-center gap-3 bg-background rounded-lg px-4 py-3 border border-border">
                <div className="w-6 h-6 rounded-full bg-primary/15 flex items-center justify-center shrink-0">
                  <Check className="w-3.5 h-3.5 text-primary" />
                </div>
                <span className="text-sm text-foreground">{item}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Pricing */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Pricing</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Program Options</h2>
            <p className="text-muted-foreground max-w-xl mx-auto">
              We offer two program lengths. Contact us for current pricing and availability.
            </p>
          </div>
          <div className="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            {pricing.map((plan) => (
              <Card key={plan.name} className={`relative border-2 ${plan.recommended ? 'border-primary' : 'border-border'}`}>
                {plan.recommended && (
                  <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                    <Badge className="bg-primary text-primary-foreground">Recommended</Badge>
                  </div>
                )}
                <CardHeader className="pb-3">
                  <CardTitle className="text-xl">{plan.name}</CardTitle>
                  <p className="text-muted-foreground text-sm">{plan.duration}</p>
                  <p className="text-2xl font-bold text-primary mt-1">{plan.price}</p>
                </CardHeader>
                <CardContent>
                  <ul className="space-y-2 mb-5">
                    {plan.features.map((f) => (
                      <li key={f} className="flex items-center gap-2 text-sm text-foreground">
                        <Check className="w-4 h-4 text-primary shrink-0" />
                        {f}
                      </li>
                    ))}
                  </ul>
                  <Button
                    className="w-full"
                    variant={plan.recommended ? 'default' : 'outline'}
                    onClick={() => {
                      document.getElementById('apply-form')?.scrollIntoView({ behavior: 'smooth' })
                    }}
                  >
                    Apply Now
                    <ArrowRight className="ml-2 w-4 h-4" />
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Application Form */}
      <section id="apply-form" className="py-16 bg-muted">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Apply</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Apply for the Program</h2>
            <p className="text-muted-foreground">
              Fill out the form below and our team will contact you within 24–48 hours.
            </p>
          </div>

          {submitted ? (
            <Card className="border-border">
              <CardContent className="p-8 text-center">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-4">
                  <Check className="w-7 h-7 text-primary" />
                </div>
                <h3 className="text-xl font-semibold text-foreground mb-2">Application Received!</h3>
                <p className="text-muted-foreground">
                  Thank you for applying. Our team will contact you shortly to discuss your needs and answer any questions.
                </p>
              </CardContent>
            </Card>
          ) : (
            <Card className="border-border">
              <CardContent className="p-6 sm:p-8">
                <form onSubmit={handleSubmit} className="space-y-5">
                  <div className="grid sm:grid-cols-2 gap-4">
                    <div className="space-y-1.5">
                      <Label htmlFor="name">Full Name *</Label>
                      <Input
                        id="name"
                        required
                        value={formData.name}
                        onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                        placeholder="Your full name"
                      />
                    </div>
                    <div className="space-y-1.5">
                      <Label htmlFor="phone">Phone Number *</Label>
                      <Input
                        id="phone"
                        required
                        type="tel"
                        value={formData.phone}
                        onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                        placeholder="+880..."
                      />
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="email">Email Address</Label>
                    <Input
                      id="email"
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      placeholder="your@email.com"
                    />
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="condition">Primary Health Condition *</Label>
                    <Input
                      id="condition"
                      required
                      value={formData.condition}
                      onChange={(e) => setFormData({ ...formData, condition: e.target.value })}
                      placeholder="e.g. Diabetes, Heart Disease..."
                    />
                  </div>
                  <div className="space-y-1.5">
                    <Label>Preferred Program Length</Label>
                    <Select onValueChange={(v) => setFormData({ ...formData, program: v })}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select program length" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="2-week">Two-Week Program</SelectItem>
                        <SelectItem value="3-week">Three-Week Program</SelectItem>
                        <SelectItem value="unsure">Not sure yet</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="message">Tell Us About Yourself</Label>
                    <Textarea
                      id="message"
                      rows={4}
                      value={formData.message}
                      onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                      placeholder="Please share a brief description of your health situation and any questions you have..."
                    />
                  </div>
                  <Button type="submit" className="w-full" size="lg">
                    Submit Application
                    <ArrowRight className="ml-2 w-4 h-4" />
                  </Button>
                </form>
              </CardContent>
            </Card>
          )}
        </div>
      </section>

      {/* Testimonials */}
      {testimonials.length > 0 && (
        <section className="py-16 bg-background">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-10">
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Testimonials</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">What Our Guests Say</h2>
            </div>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {testimonials.map((t) => (
                <TestimonialCard key={t.id} testimonial={t} />
              ))}
            </div>
          </div>
        </section>
      )}
    </div>
  )
}
