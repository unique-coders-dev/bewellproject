import { Leaf, Phone, Check } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
interface FarmProps {
  onNavigate: (page: string) => void
}

const farmPractices = [
  'No chemical pesticides or fertilizers',
  'Composting and organic soil enrichment',
  'Rainwater harvesting and conservation',
  'Crop rotation for soil health',
  'Intercropping for biodiversity',
  'Hand-harvested daily for freshness',
]

const farmImages = [
  { src: '/images/garden/IMG_3887.JPG', alt: 'Farm landscape at BE WELL' },
  { src: '/images/garden/IMG_3893.JPG', alt: 'Fresh vegetables from our garden' },
  { src: '/images/garden/IMG_3895.JPG', alt: 'Fruit trees on campus' },
  { src: '/images/garden/IMG_3914.JPG', alt: 'Garden herbs growing at BE WELL' },
]

export function Farm({ onNavigate }: FarmProps) {

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
          style={{ backgroundImage: "url('/images/garden/IMG_3924.JPG')" }}
        />
        <div className="absolute inset-0 bg-foreground/75" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <Badge className="mb-4 bg-primary/80 text-primary-foreground border-0">BE WELL Farm</Badge>
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
            Fresh From Our<br />
            <span className="text-primary">Best-Practices Farm</span>
          </h1>
          <p className="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
            Our organic farm grows fruits, vegetables, and medicinal herbs using sustainable, chemical-free methods. All produce feeds our guests and is available for purchase.
          </p>
          <div className="flex gap-6 flex-wrap">
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Leaf className="w-4 h-4 text-primary" />
              100% chemical free
            </div>
            <div className="flex items-center gap-2 text-white/80 text-sm">
              <Check className="w-4 h-4 text-primary" />
              Hand-harvested daily
            </div>
          </div>
        </div>
      </section>

      {/* About the Farm */}
      <section className="py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <Badge className="mb-4 bg-accent text-accent-foreground border-0">Our Approach</Badge>
              <h2 className="text-3xl font-bold text-foreground mb-4">Best-Practices Farming</h2>
              <p className="text-muted-foreground mb-5 leading-relaxed">
                Our farm is a demonstration of what agriculture can look like when it respects the natural order. We grow a wide variety of fruits, vegetables, and medicinal herbs without any chemical inputs.
              </p>
              <p className="text-muted-foreground mb-6 leading-relaxed">
                The food grown here feeds every guest on campus, and the surplus is available for purchase by the local community.
              </p>
              <ul className="space-y-2">
                {farmPractices.map((p) => (
                  <li key={p} className="flex items-center gap-2.5 text-sm text-foreground">
                    <Check className="w-4 h-4 text-primary shrink-0" />
                    {p}
                  </li>
                ))}
              </ul>
            </div>
            <div className="grid grid-cols-2 gap-3">
              {farmImages.map((img, i) => (
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


      {/* Order Info */}
      <section className="py-16 bg-background">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <Card className="border-border">
            <CardContent className="p-8">
              <div className="grid md:grid-cols-2 gap-8 items-center">
                <div>
                  <Badge className="mb-3 bg-accent text-accent-foreground border-0">How to Order</Badge>
                  <h2 className="text-2xl font-bold text-foreground mb-3">Get Fresh Farm Produce</h2>
                  <p className="text-muted-foreground mb-4 leading-relaxed">
                    To purchase produce from the BE WELL farm, simply contact us by phone or visit our campus. Bulk orders welcome. We can arrange delivery for large orders.
                  </p>
                  <ul className="space-y-2 mb-5">
                    <li className="flex items-center gap-2 text-sm text-foreground">
                      <Check className="w-4 h-4 text-primary shrink-0" />
                      Fresh harvest available most mornings
                    </li>
                    <li className="flex items-center gap-2 text-sm text-foreground">
                      <Check className="w-4 h-4 text-primary shrink-0" />
                      Walk-in purchases welcome
                    </li>
                    <li className="flex items-center gap-2 text-sm text-foreground">
                      <Check className="w-4 h-4 text-primary shrink-0" />
                      Bulk and wholesale inquiries welcome
                    </li>
                  </ul>
                  <Button onClick={() => handleNav('contact')}>
                    <Phone className="mr-2 w-4 h-4" />
                    Contact to Order
                  </Button>
                </div>
                <div>
                  <img
                    src="/images/garden/IMG_4028.JPG"
                    alt="Fresh farm produce available at BeWell"
                    className="rounded-xl w-full object-cover aspect-[4/3]"
                  />
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </section>
    </div>
  )
}
