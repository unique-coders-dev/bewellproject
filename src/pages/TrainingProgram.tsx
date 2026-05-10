import { useEffect, useState } from 'react'
import { Check, BookOpen, Award, ArrowRight, Calendar } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { TestimonialCard } from '@/components/TestimonialCard'
import { supabase, type Testimonial } from '@/lib/supabase'

interface TrainingProps {
  onNavigate: (page: string) => void
}

const modules = [
  { title: 'Foundations of Natural Health', desc: 'The eight laws of health and their scientific basis. Understanding lifestyle as medicine.' },
  { title: 'Plant-Based Nutrition', desc: 'Evidence-based nutrition science. Meal planning, cooking demonstrations, and dietary counseling.' },
  { title: 'Exercise Therapy', desc: 'Therapeutic movement programs tailored to various conditions. Nature-based exercise protocols.' },
  { title: 'Stress & Mental Health', desc: 'Mind-body connection, stress management techniques, sleep improvement strategies.' },
  { title: 'Water Therapy (Hydrotherapy)', desc: 'Practical training in hydrotherapy applications for common conditions.' },
  { title: 'Community Health Outreach', desc: 'How to apply lifestyle medicine principles in community and clinic settings.' },
]

const outcomes = [
  'Certificate in Lifestyle Health Education',
  'Practical skills in natural health counseling',
  'Evidence-based nutritional guidance capability',
  'Ability to lead health education sessions',
  'Network of health professionals',
  'Access to BeWell curriculum resources',
]

const photos = [
  'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&q=80&fit=crop',
  'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=80&fit=crop',
  'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&q=80&fit=crop',
  'https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&q=80&fit=crop',
]

export function TrainingProgram({ onNavigate: _onNavigate }: TrainingProps) {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])
  const [formData, setFormData] = useState({ name: '', email: '', phone: '', background: '', message: '' })
  const [submitted, setSubmitted] = useState(false)

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        const { data } = await supabase
          .from('testimonials')
          .select('*')
          .eq('program_type', 'training')
          .order('created_at', { ascending: false })

        if (data && data.length > 0) {
          setTestimonials(data)
        } else {
          // Fallback mock data
          setTestimonials([
            {
              id: 't1',
              name: 'Dr. Robert Miller',
              role: 'Medical Doctor',
              content: 'This program provided the practical lifestyle medicine tools that were missing from my medical school education. I now feel much better equipped to help my patients with chronic diseases.',
              program_type: 'training',
              is_featured: true,
              created_at: new Date().toISOString()
            },
            {
              id: 't2',
              name: 'Grace Amena',
              role: 'Community Health Worker',
              content: 'Learning the eight laws of health has transformed my outreach work. The simple, natural principles are easy to explain and produce real results in the villages.',
              program_type: 'training',
              is_featured: true,
              created_at: new Date().toISOString()
            }
          ])
        }
      } catch (error) {
        setTestimonials([
          {
            id: 't1',
            name: 'Dr. Robert Miller',
            role: 'Medical Doctor',
            content: 'This program provided the practical lifestyle medicine tools that were missing from my medical school education. I now feel much better equipped to help my patients with chronic diseases.',
            program_type: 'training',
            is_featured: true,
            created_at: new Date().toISOString()
          },
          {
            id: 't2',
            name: 'Grace Amena',
            role: 'Community Health Worker',
            content: 'Learning the eight laws of health has transformed my outreach work. The simple, natural principles are easy to explain and produce real results in the villages.',
            program_type: 'training',
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
  }

  return (
    <div>
      {/* Hero */}
      <section className="relative pt-16 min-h-[55vh] flex items-center">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: "url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1400&q=80&fit=crop')" }}
        />
        <div className="absolute inset-0 bg-foreground/60" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <Badge className="mb-4 bg-primary/80 text-primary-foreground border-0">Training Program</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
            Equip Yourself to<br />
            <span className="text-primary">Help Others Heal</span>
          </h1>
          <p className="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
            Our comprehensive training program equips health professionals, educators, and community workers with practical natural health and lifestyle medicine skills.
          </p>
          <div className="flex gap-6 flex-wrap">
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Calendar className="w-4 h-4 text-primary" />
              Sessions held throughout the year
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <BookOpen className="w-4 h-4 text-primary" />
              Practical &amp; theoretical training
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Award className="w-4 h-4 text-primary" />
              Certificate awarded
            </div>
          </div>
        </div>
      </section>

      {/* Who Should Attend */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Who Should Attend</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">Built for Health Champions</h2>
              <p className="text-muted-foreground mb-5 leading-relaxed">
                Whether you are a nurse, doctor, pastor, teacher, or community health worker — this program will give you practical tools to guide others toward better health.
              </p>
              <ul className="space-y-2.5">
                {['Nurses and community health workers', 'Doctors and medical professionals', 'Pastors and community leaders', 'Teachers and educators', 'Health-conscious individuals', 'Anyone passionate about natural health'].map((item) => (
                  <li key={item} className="flex items-center gap-2.5 text-sm text-foreground">
                    <Check className="w-4 h-4 text-primary shrink-0" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>
            <div className="grid grid-cols-2 gap-3">
              {photos.slice(0, 4).map((photo, i) => (
                <img
                  key={i}
                  src={photo}
                  alt={`Training session ${i + 1}`}
                  className="rounded-lg w-full object-cover aspect-square"
                />
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Curriculum */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Curriculum</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">What You Will Learn</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Six comprehensive modules covering every aspect of natural lifestyle medicine.
            </p>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {modules.map((module, i) => (
              <Card key={module.title} className="border-border">
                <CardContent className="p-5">
                  <div className="w-8 h-8 rounded-md bg-primary/15 flex items-center justify-center mb-3">
                    <span className="text-sm font-bold text-primary">0{i + 1}</span>
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">{module.title}</h3>
                  <p className="text-sm text-muted-foreground leading-relaxed">{module.desc}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Outcomes */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <img
                src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&q=80&fit=crop"
                alt="Participants learning at BeWell training"
                className="rounded-xl shadow-lg w-full object-cover aspect-[4/3]"
              />
            </div>
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Outcomes</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">What You Will Leave With</h2>
              <p className="text-muted-foreground mb-6 leading-relaxed">
                Graduates of our training program are equipped to positively impact their communities through evidence-based natural health education.
              </p>
              <ul className="space-y-3">
                {outcomes.map((outcome) => (
                  <li key={outcome} className="flex items-center gap-3 text-sm text-foreground">
                    <div className="w-5 h-5 rounded-full bg-primary/15 flex items-center justify-center shrink-0">
                      <Check className="w-3 h-3 text-primary" />
                    </div>
                    {outcome}
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Application Form */}
      <section className="py-16 bg-muted">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">Apply</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Apply for Training</h2>
            <p className="text-muted-foreground">We will contact you about upcoming training dates.</p>
          </div>

          {submitted ? (
            <Card className="border-border">
              <CardContent className="p-8 text-center">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-4">
                  <Check className="w-7 h-7 text-primary" />
                </div>
                <h3 className="text-xl font-semibold text-foreground mb-2">Application Received!</h3>
                <p className="text-muted-foreground">We will be in touch with upcoming training schedules and details.</p>
              </CardContent>
            </Card>
          ) : (
            <Card className="border-border">
              <CardContent className="p-6 sm:p-8">
                <form onSubmit={handleSubmit} className="space-y-5">
                  <div className="grid sm:grid-cols-2 gap-4">
                    <div className="space-y-1.5">
                      <Label htmlFor="t-name">Full Name *</Label>
                      <Input id="t-name" required value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} placeholder="Your full name" />
                    </div>
                    <div className="space-y-1.5">
                      <Label htmlFor="t-phone">Phone Number *</Label>
                      <Input id="t-phone" required type="tel" value={formData.phone} onChange={(e) => setFormData({ ...formData, phone: e.target.value })} placeholder="+880..." />
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="t-email">Email Address *</Label>
                    <Input id="t-email" required type="email" value={formData.email} onChange={(e) => setFormData({ ...formData, email: e.target.value })} placeholder="your@email.com" />
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="t-background">Professional Background</Label>
                    <Input id="t-background" value={formData.background} onChange={(e) => setFormData({ ...formData, background: e.target.value })} placeholder="e.g. Nurse, Teacher, Community Worker..." />
                  </div>
                  <div className="space-y-1.5">
                    <Label htmlFor="t-message">Why do you want to attend this training?</Label>
                    <Textarea id="t-message" rows={4} value={formData.message} onChange={(e) => setFormData({ ...formData, message: e.target.value })} placeholder="Tell us about your goals..." />
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
              <h2 className="text-3xl font-bold text-foreground mb-4">What Trainees Say</h2>
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
