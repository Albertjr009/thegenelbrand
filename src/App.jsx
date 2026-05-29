import Header from './components/Header'
import Hero from './components/Hero'
import Services from './components/Services'
import Portfolio from './components/Portfolio'
import About from './components/About'
import Footer from './components/Footer'
import Admin from './components/Admin'

export default function App() {
  const isAdminRoute = window.location.pathname.replace(/\/$/, '') === '/admin'

  if (isAdminRoute) {
    return <Admin />
  }

  return (
    <div className="site-shell">
      <Header />
      <main className="flex-1">
        <Hero />
        <Services />
        <Portfolio />
        <About />
      </main>
      <Footer />
    </div>
  )
}
