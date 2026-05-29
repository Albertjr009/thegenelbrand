import Header from './components/Header'
import Hero from './components/Hero'
import Services from './components/Services'
import Portfolio from './components/Portfolio'
import About from './components/About'
import Footer from './components/Footer'

export default function App() {
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
