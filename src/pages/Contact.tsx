import { useState } from 'react'
import { MapPin, Phone, Mail, Clock, Check, ArrowRight } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { supabase } from '@/lib/supabase'

interface ContactProps {
  onNavigate: (page: string) => void
}

const contactInfo = [
  {
    icon: MapPin,
    title: 'Our Location',
    lines: ['Near Choto Daragar Hat', 'Beautiful hillside campus', 'Bangladesh'],
  },
  {
    icon: Phone,
    title: 'Phone',
    lines: ['+880 17 0000-0000', '+880 18 0000-0000'],
  },
  {
    icon: Mail,
    title: 'Email',
    lines: ['info@bewell.org', 'programs@bewell.org'],
  },
  {
    icon: Clock,
    title: 'Office Hours',
    lines: ['Sunday – Thursday', '8:00 AM – 5:00 PM', 'Closed Friday evening & Saturday'],
  },
]

const subjects = [
  'Lifestyle Program Inquiry',
  'Training Program Inquiry',
  'Hostel / Accommodation',
  'Farm Products Order',
  'Employment Inquiry',
  'General Question',
  'Other',
]

export function Contact({ onNavigate: _onNavigate }: ContactProps) {
  const [formData, setFormData] = useState({
    name: '', email: '', phone: '', subject: '', message: '',
    honeypot: '', // anti-spam hidden field
  })
  const [submitted, setSubmitted] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setError('')

    // Honeypot check — bots fill this field, humans don't
    if (formData.honeypot) {
      setSubmitted(true)
      return
    }

    if (!formData.name || !formData.email || !formData.subject || !formData.message) {
      setError('Please fill in all required fields.')
      return
    }

    setLoading(true)
    const { error: dbError } = await supabase.from('contact_messages').insert({
      name: formData.name,
      email: formData.email,
      subject: formData.subject,
      message: `${formData.phone ? '[Phone: ' + formData.phone + '] ' : ''}${formData.message}`,
    })

    setLoading(false)
    if (dbError) {
      setError('Something went wrong. Please try again or call us directly.')
    } else {
      setSubmitted(true)
    }
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative pt-16 min-h-[40vh] flex items-center bg-primary">
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          <Badge className="mb-4 bg-primary-foreground/15 text-primary-foreground border-primary-foreground/20">Contact Us</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-primary-foreground mb-4 max-w-2xl leading-tight">
            We'd Love to Hear From You
          </h1>
          <p className="text-primary-foreground/80 text-lg max-w-xl leading-relaxed">
            Whether you have questions about our programs, want to book a stay, or just want to learn more — we are here to help.
          </p>
        </div>
      </section>

      {/* Contact Info + Form */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-3 gap-10">
            {/* Left: Contact details */}
            <div className="lg:col-span-1">
              <h2 className="text-2xl font-bold text-foreground mb-6">Get in Touch</h2>
              <div className="space-y-5">
                {contactInfo.map((info) => (
                  <div key={info.title} className="flex items-start gap-3">
                    <div className="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                      <info.icon className="w-4 h-4 text-primary" />
                    </div>
                    <div>
                      <p className="text-sm font-semibold text-foreground mb-0.5">{info.title}</p>
                      {info.lines.map((line) => (
                        <p key={line} className="text-sm text-muted-foreground">{line}</p>
                      ))}
                    </div>
                  </div>
                ))}
              </div>

              <div className="mt-8 p-4 rounded-lg bg-muted border border-border">
                <p className="text-sm font-medium text-foreground mb-1">Note on Sabbath Observance</p>
                <p className="text-sm text-muted-foreground leading-relaxed">
                  We observe the biblical Sabbath from Friday at sundown to Saturday at sundown. Our office is closed during this time. We will respond to all inquiries on Sunday.
                </p>
              </div>
            </div>

            {/* Right: Form */}
            <div className="lg:col-span-2">
              <h2 className="text-2xl font-bold text-foreground mb-6">Send Us a Message</h2>

              {submitted ? (
                <Card className="border-border">
                  <CardContent className="p-8 text-center">
                    <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-4">
                      <Check className="w-7 h-7 text-primary" />
                    </div>
                    <h3 className="text-xl font-semibold text-foreground mb-2">Message Sent!</h3>
                    <p className="text-muted-foreground max-w-sm mx-auto">
                      Thank you for reaching out. We will respond to your message within 1–2 business days.
                    </p>
                  </CardContent>
                </Card>
              ) : (
                <Card className="border-border">
                  <CardContent className="p-6 sm:p-8">
                    <form onSubmit={handleSubmit} className="space-y-5">
                      {/* Honeypot field - hidden from real users */}
                      <div style={{ position: 'absolute', left: '-9999px', opacity: 0, height: 0, overflow: 'hidden' }} aria-hidden="true">
                        <input
                          tabIndex={-1}
                          autoComplete="off"
                          value={formData.honeypot}
                          onChange={(e) => setFormData({ ...formData, honeypot: e.target.value })}
                          name="website"
                          type="text"
                        />
                      </div>

                      <div className="grid sm:grid-cols-2 gap-4">
                        <div className="space-y-1.5">
                          <Label htmlFor="c-name">Your Name *</Label>
                          <Input
                            id="c-name"
                            required
                            value={formData.name}
                            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                            placeholder="Full name"
                          />
                        </div>
                        <div className="space-y-1.5">
                          <Label htmlFor="c-phone">Phone Number</Label>
                          <Input
                            id="c-phone"
                            type="tel"
                            value={formData.phone}
                            onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                            placeholder="+880..."
                          />
                        </div>
                      </div>

                      <div className="space-y-1.5">
                        <Label htmlFor="c-email">Email Address *</Label>
                        <Input
                          id="c-email"
                          required
                          type="email"
                          value={formData.email}
                          onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                          placeholder="your@email.com"
                        />
                      </div>

                      <div className="space-y-1.5">
                        <Label>Subject *</Label>
                        <Select
                          required
                          onValueChange={(v) => setFormData({ ...formData, subject: v })}
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="What is this regarding?" />
                          </SelectTrigger>
                          <SelectContent>
                            {subjects.map((s) => (
                              <SelectItem key={s} value={s}>{s}</SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>

                      <div className="space-y-1.5">
                        <Label htmlFor="c-message">Your Message *</Label>
                        <Textarea
                          id="c-message"
                          required
                          rows={5}
                          value={formData.message}
                          onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                          placeholder="Please describe how we can help you..."
                        />
                      </div>

                      {error && (
                        <p className="text-sm text-destructive">{error}</p>
                      )}

                      <Button type="submit" className="w-full" size="lg" disabled={loading}>
                        {loading ? 'Sending...' : 'Send Message'}
                        {!loading && <ArrowRight className="ml-2 w-4 h-4" />}
                      </Button>

                      <p className="text-xs text-muted-foreground text-center">
                        Your message is kept private. We do not share your contact information.
                      </p>
                    </form>
                  </CardContent>
                </Card>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Location Map Placeholder */}
      <section className="py-0">
        <div className="h-64 bg-muted flex items-center justify-center border-t border-border">
          <div className="text-center">
            <MapPin className="w-8 h-8 text-primary mx-auto mb-2" />
            <p className="text-foreground font-medium">Near Choto Daragar Hat</p>
            <p className="text-muted-foreground text-sm mt-1">Directions available upon inquiry</p>
          </div>
        </div>
      </section>
    </div>
  )
}
