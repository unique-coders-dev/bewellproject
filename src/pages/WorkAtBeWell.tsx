import { useState } from 'react'
import { Heart, Leaf, BookOpen, Star, ArrowRight, Check } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

interface WorkProps {
  onNavigate: (page: string) => void
}

const openings = [
  { title: 'Lifestyle Health Counselor', type: 'Full-time', description: 'Guide program guests through their healing journey. Requires health background and passion for natural medicine.' },
  { title: 'Farm Worker / Agricultural Assistant', type: 'Full-time', description: 'Care for our organic farm, manage daily harvests, and maintain best-practice farming standards.' },
  { title: 'Kitchen / Nutrition Staff', type: 'Full-time', description: 'Prepare nutritious plant-based meals for our guests. Interest in therapeutic nutrition highly valued.' },
  { title: 'Hostel / Guest Services', type: 'Full-time', description: 'Ensure our guests feel welcomed, comfortable, and cared for throughout their stay.' },
  { title: 'Health Educator / Lecturer', type: 'Part-time', description: 'Teach our daily health lectures to program participants. Strong knowledge of natural health principles required.' },
  { title: 'Administrative Staff', type: 'Full-time', description: 'Support the administrative operations of BE WELL ALWAYS LTD. Organizational skills and hospitality mindset essential.' },
]

const values = [
  { icon: Heart, title: 'Compassionate Service', desc: 'We serve our guests as we would serve our own family — with deep care and attention to each person.' },
  { icon: Leaf, title: 'Natural Principles', desc: 'We believe in the healing power of nature and the importance of living in harmony with natural law.' },
  { icon: BookOpen, title: 'Continual Learning', desc: 'Our team is always growing. We encourage ongoing education and personal development for all staff.' },
  { icon: Star, title: 'Excellence in All Things', desc: 'Whether it is the cleanliness of a room or the accuracy of health information, we aim for the highest standard.' },
]

export function WorkAtBeWell({ onNavigate: _onNavigate }: WorkProps) {
  const [formData, setFormData] = useState({ name: '', email: '', phone: '', position: '', experience: '', motivation: '' })
  const [submitted, setSubmitted] = useState(false)

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setSubmitted(true)
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative pt-16 min-h-[55vh] flex items-center">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: "url('/images/buildings/IMG_3865.JPG')" }}
        />
        <div className="absolute inset-0 bg-foreground/75" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <Badge className="mb-4 bg-primary/80 text-primary-foreground border-0">Join Our Team</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
            Work With<br />
            <span className="text-primary">BE WELL ALWAYS</span>
          </h1>
          <p className="text-white/85 text-lg max-w-xl leading-relaxed">
            Join a mission-driven team dedicated to transforming lives through natural healing. We are more than an organization — we are a community of healers.
          </p>
        </div>
      </section>

      {/* Philosophy */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Our Philosophy</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">A Ministry of Healing</h2>
              <p className="text-muted-foreground mb-4 leading-relaxed">
                BE WELL ALWAYS LTD is rooted in the belief that true health is a gift meant for every person, and that we can cooperate with God's design for the human body to see remarkable healing.
              </p>
              <p className="text-muted-foreground mb-4 leading-relaxed">
                We operate from a faith-based foundation that values the sanctity of human life and the responsibility we have to care for our bodies as temples. Our program draws on the principles found in Scripture and confirmed by modern science.
              </p>
              <p className="text-muted-foreground leading-relaxed">
                Every member of our team is an integral part of this healing ministry. We believe that a warm, prayerful, compassionate environment is itself therapeutic — and our staff creates that environment every day.
              </p>
            </div>
            <div>
              <img
                src="/images/buildings/IMG_3865.JPG"
                alt="Team working together at BeWell"
                className="rounded-xl shadow-lg w-full object-cover aspect-[4/3]"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Values */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Our Values</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">What We Stand For</h2>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {values.map((v) => (
              <Card key={v.title} className="border-border text-center">
                <CardContent className="p-5">
                  <div className="w-11 h-11 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-3">
                    <v.icon className="w-5 h-5 text-primary" />
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">{v.title}</h3>
                  <p className="text-sm text-muted-foreground leading-relaxed">{v.desc}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Faith Statement */}
      <section className="py-16 bg-background">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <Card className="border-border">
            <CardContent className="p-8">
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Faith Foundation</Badge>
              <h2 className="text-2xl font-bold text-foreground mb-4">Our Religious Foundation</h2>
              <p className="text-muted-foreground mb-4 leading-relaxed">
                BE WELL is operated by a team of Seventh-day Adventist health professionals who believe that health is not merely the absence of disease, but a state of complete physical, mental, and spiritual wellbeing.
              </p>
              <p className="text-muted-foreground mb-4 leading-relaxed">
                We believe the Sabbath — the seventh day of the week — is a sacred time of rest and restoration. Our program incorporates spiritual dimensions of healing, including prayer, meditation on Scripture, and time in nature.
              </p>
              <p className="text-muted-foreground leading-relaxed">
                Staff members are expected to be in sympathy with our health philosophy and faith foundation. We welcome people of all backgrounds as guests, and we offer our ministry to all who seek healing.
              </p>
            </CardContent>
          </Card>
        </div>
      </section>

      {/* Open Positions */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Opportunities</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Current Openings</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              We are building our team. If you are passionate about natural health and serving others, we would love to hear from you.
            </p>
          </div>
          <div className="grid md:grid-cols-2 gap-4 max-w-5xl mx-auto">
            {openings.map((job) => (
              <Card key={job.title} className="border-border hover:shadow-md transition-shadow">
                <CardContent className="p-5">
                  <div className="flex items-start justify-between mb-2">
                    <h3 className="font-semibold text-foreground">{job.title}</h3>
                    <Badge variant="secondary" className="text-xs ml-2 shrink-0">{job.type}</Badge>
                  </div>
                  <p className="text-sm text-muted-foreground leading-relaxed">{job.description}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Application Form */}
      <section className="py-16 bg-background">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Apply</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Apply to Join the Team</h2>
            <p className="text-muted-foreground">Tell us about yourself and why you want to be part of BE WELL.</p>
          </div>

          {submitted ? (
            <Card className="border-border">
              <CardContent className="p-8 text-center">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-4">
                  <Check className="w-7 h-7 text-primary" />
                </div>
                <h3 className="text-xl font-semibold text-foreground mb-2">Application Received!</h3>
                <p className="text-muted-foreground">Thank you for your interest. We will review your application and be in touch soon.</p>
              </CardContent>
            </Card>
          ) : (
            <Card className="border-border">
              <CardContent className="p-6 sm:p-8">
                <form onSubmit={handleSubmit} className="space-y-5">
                  <div className="grid sm:grid-cols-2 gap-4">
                    <div className="space-y-1.5">
                      <Label htmlFor="w-name">Full Name *</Label>
                      <Input id="w-name" required value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} placeholder="Your full name" />
                    </div>
                    <div className="space-y-1.5">
                      <Label htmlFor="w-phone">Phone Number *</Label>
                      <Input id="w-phone" required type="tel" value={formData.phone} onChange={(e) => setFormData({ ...formData, phone: e.target.value })} placeholder="+880..." />
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="w-email">Email Address *</Label>
                    <Input id="w-email" required type="email" value={formData.email} onChange={(e) => setFormData({ ...formData, email: e.target.value })} placeholder="your@email.com" />
                  </div>
                  <div className="space-y-1.5">
                    <Label>Position of Interest</Label>
                    <Select onValueChange={(v) => setFormData({ ...formData, position: v })}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select a position" />
                      </SelectTrigger>
                      <SelectContent>
                        {openings.map((job) => (
                          <SelectItem key={job.title} value={job.title}>{job.title}</SelectItem>
                        ))}
                        <SelectItem value="other">Other / General Interest</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="w-experience">Relevant Experience</Label>
                    <Textarea id="w-experience" rows={3} value={formData.experience} onChange={(e) => setFormData({ ...formData, experience: e.target.value })} placeholder="Describe your relevant background and skills..." />
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="w-motivation">Why do you want to work at BE WELL?</Label>
                    <Textarea id="w-motivation" rows={3} value={formData.motivation} onChange={(e) => setFormData({ ...formData, motivation: e.target.value })} placeholder="Share your motivation and how you align with our mission..." />
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
    </div>
  )
}
