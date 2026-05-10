import { useState, useEffect } from 'react'
import { Navbar } from '@/components/Navbar'
import { Footer } from '@/components/Footer'
import { Home } from '@/pages/Home'
import { LifestyleProgram } from '@/pages/LifestyleProgram'
import { TrainingProgram } from '@/pages/TrainingProgram'
import { HostelServices } from '@/pages/HostelServices'
import { Farm } from '@/pages/Farm'
import { WorkAtBeWell } from '@/pages/WorkAtBeWell'
import { Contact } from '@/pages/Contact'

type Page = 'home' | 'lifestyle' | 'training' | 'hostel' | 'farm' | 'work' | 'contact'

const pageTitles: Record<Page, string> = {
  home: 'BE WELL - Center of Health & Healing',
  lifestyle: 'Lifestyle Program - BE WELL',
  training: 'Training Program - BE WELL',
  hostel: 'Hostel Services - BE WELL',
  farm: 'BE WELL Farm - Fresh Organic Produce',
  work: 'Work With Us - BE WELL ALWAYS LTD',
  contact: 'Contact Us - BE WELL',
}

const validPages: Page[] = ['home', 'lifestyle', 'training', 'hostel', 'farm', 'work', 'contact']

function getInitialPage(): Page {
  const hash = window.location.hash.slice(1) as Page
  return validPages.includes(hash) ? hash : 'home'
}

export function App() {
  const [currentPage, setCurrentPage] = useState<Page>(getInitialPage)

  useEffect(() => {
    document.title = pageTitles[currentPage]
    history.replaceState(null, '', currentPage === 'home' ? '#' : `#${currentPage}`)
  }, [currentPage])

  useEffect(() => {
    const handleHashChange = () => {
      const page = window.location.hash.slice(1) as Page
      setCurrentPage(validPages.includes(page) ? page : 'home')
    }
    window.addEventListener('hashchange', handleHashChange)
    return () => window.removeEventListener('hashchange', handleHashChange)
  }, [])

  const navigate = (page: string) => {
    setCurrentPage(page as Page)
  }

  const renderPage = () => {
    switch (currentPage) {
      case 'home': return <Home onNavigate={navigate} />
      case 'lifestyle': return <LifestyleProgram onNavigate={navigate} />
      case 'training': return <TrainingProgram onNavigate={navigate} />
      case 'hostel': return <HostelServices onNavigate={navigate} />
      case 'farm': return <Farm onNavigate={navigate} />
      case 'work': return <WorkAtBeWell onNavigate={navigate} />
      case 'contact': return <Contact onNavigate={navigate} />
      default: return <Home onNavigate={navigate} />
    }
  }

  return (
    <div className="min-h-screen flex flex-col">
      <Navbar currentPage={currentPage} onNavigate={navigate} />
      <main className="flex-1">
        {renderPage()}
      </main>
      <Footer onNavigate={navigate} />
    </div>
  )
}

export default App
