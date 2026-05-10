import { useEffect, useState } from 'react'
import { Leaf, Phone, Check } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { supabase, type FarmProduct } from '@/lib/supabase'

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
  { src: 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?w=600&q=80&fit=crop', alt: 'Farm landscape' },
  { src: 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=600&q=80&fit=crop', alt: 'Fresh vegetables' },
  { src: 'https://images.unsplash.com/photo-1444392061186-9fc38f16d8d4?w=600&q=80&fit=crop', alt: 'Fruit trees' },
  { src: 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600&q=80&fit=crop', alt: 'Garden herbs' },
]

const categories = ['all', 'fruits', 'vegetables', 'herbs']

export function Farm({ onNavigate }: FarmProps) {
  const [products, setProducts] = useState<FarmProduct[]>([])
  const [activeCategory, setActiveCategory] = useState('all')
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    supabase
      .from('farm_products')
      .select('*')
      .order('category')
      .then(({ data }) => {
        if (data) setProducts(data)
        setLoading(false)
      })
  }, [])

  const filtered = activeCategory === 'all'
    ? products
    : products.filter((p) => p.category === activeCategory)

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
          style={{ backgroundImage: "url('https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?w=1400&q=80&fit=crop')" }}
        />
        <div className="absolute inset-0 bg-foreground/55" />
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

      {/* Products */}
      <section className="py-16 bg-muted">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <Badge className="mb-4 bg-accent text-accent-foreground border-0">What We Grow</Badge>
            <h2 className="text-3xl font-bold text-foreground mb-4">Farm Products</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Available for purchase from our farm. Prices and availability change with the season — contact us to order.
            </p>
          </div>

          <Tabs value={activeCategory} onValueChange={setActiveCategory} className="w-full">
            <div className="flex justify-center mb-8">
              <TabsList>
                {categories.map((cat) => (
                  <TabsTrigger key={cat} value={cat} className="capitalize">
                    {cat === 'all' ? 'All Products' : cat}
                  </TabsTrigger>
                ))}
              </TabsList>
            </div>

            {categories.map((cat) => (
              <TabsContent key={cat} value={cat}>
                {loading ? (
                  <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {Array.from({ length: 8 }).map((_, i) => (
                      <div key={i} className="h-48 bg-background rounded-lg animate-pulse" />
                    ))}
                  </div>
                ) : (
                  <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {filtered.map((product) => (
                      <Card key={product.id} className={`border-border ${!product.is_available ? 'opacity-60' : ''}`}>
                        <CardContent className="p-5">
                          <div className="flex items-start justify-between mb-3">
                            <div className="w-10 h-10 rounded-lg bg-primary/15 flex items-center justify-center">
                              <Leaf className="w-5 h-5 text-primary" />
                            </div>
                            <Badge
                              variant={product.is_available ? 'default' : 'secondary'}
                              className={`text-xs ${product.is_available ? 'bg-primary/15 text-primary border-0' : ''}`}
                            >
                              {product.is_available ? 'Available' : 'Seasonal'}
                            </Badge>
                          </div>
                          <h3 className="font-semibold text-foreground mb-1">{product.name}</h3>
                          {product.description && (
                            <p className="text-xs text-muted-foreground mb-3 leading-relaxed">{product.description}</p>
                          )}
                          <div className="flex items-center justify-between mt-auto">
                            <div>
                              <span className="text-sm font-semibold text-primary">{product.price}</span>
                              {product.unit && (
                                <span className="text-xs text-muted-foreground ml-1">/ {product.unit}</span>
                              )}
                            </div>
                            <Badge variant="outline" className="text-xs capitalize">{product.category}</Badge>
                          </div>
                        </CardContent>
                      </Card>
                    ))}
                  </div>
                )}
                {!loading && filtered.length === 0 && (
                  <p className="text-center text-muted-foreground py-12">No products in this category currently.</p>
                )}
              </TabsContent>
            ))}
          </Tabs>
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
                    src="https://images.unsplash.com/photo-1560493676-04071c5f467b?w=600&q=80&fit=crop"
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
