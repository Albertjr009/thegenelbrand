export default function Header() {
  return (
    <header className="site-header">
      <div className="container-max header-inner">
        <div className="brand-lockup">
          <div className="brand-mark">GB</div>
          <div>
            <div className="brand-name">Genevieve</div>
            <div className="brand-role">Fashion design student</div>
          </div>
        </div>
        <nav className="site-nav">
          <a href="#portfolio" className="nav-link">Work</a>
          <a href="#about" className="nav-link">About</a>
          <a href="#contact" className="button button-dark">Contact</a>
        </nav>
      </div>
    </header>
  )
}
