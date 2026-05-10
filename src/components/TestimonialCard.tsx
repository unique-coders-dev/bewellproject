import { Quote } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/card'
import type { Testimonial } from '@/lib/supabase'

interface TestimonialCardProps {
  testimonial: Testimonial
}

export function TestimonialCard({ testimonial }: TestimonialCardProps) {
  return (
    <Card className="h-full border-border hover:shadow-md transition-shadow">
      <CardContent className="p-6 flex flex-col h-full">
        <Quote className="w-8 h-8 text-primary/30 mb-3 shrink-0" />
        <p className="text-sm text-muted-foreground leading-relaxed flex-1 italic">
          "{testimonial.content}"
        </p>
        <div className="mt-4 pt-4 border-t border-border">
          <p className="font-semibold text-sm text-foreground">{testimonial.name}</p>
          {testimonial.role && (
            <p className="text-xs text-muted-foreground mt-0.5">{testimonial.role}</p>
          )}
        </div>
      </CardContent>
    </Card>
  )
}
