const InstagramLogo = () => (
  <svg className="app-logo" viewBox="0 0 24 24" aria-hidden="true">
    <rect x="3" y="3" width="18" height="18" rx="5" />
    <circle cx="12" cy="12" r="4" />
    <circle cx="17.5" cy="6.5" r="1.2" />
  </svg>
)

const BehanceLogo = () => (
  <svg className="app-logo app-logo-behance" viewBox="0 0 48 24" aria-hidden="true">
    <text x="3" y="17">Be</text>
    <rect x="27" y="5" width="12" height="2" rx="1" />
  </svg>
)

export default function Footer() {
  return (
    <footer id="contact" className="site-footer">
      <div className="container-max footer-inner">
        <div className="footer-grid">
          <div>
            <div className="footer-name">Alex Morgan</div>
            <div className="footer-muted">Fashion Design Portfolio</div>
          </div>
          <div>
            <div className="footer-title">Contact</div>
            <a href="mailto:alex.morgan@example.com" className="footer-link">
              alex.morgan@example.com
            </a>
            <a href="tel:+447000000000" className="footer-link">
              +44 7000 000000
            </a>
          </div>
          <div>
            <div className="footer-title">Applications</div>
            <div className="app-button-row">
              <a href="https://www.instagram.com/" className="app-logo-link" aria-label="Instagram">
                <InstagramLogo />
              </a>
              <a href="https://www.behance.net/" className="app-logo-link" aria-label="Behance">
                <BehanceLogo />
              </a>
            </div>
          </div>
        </div>
        <div className="copyright">
          Copyright {new Date().getFullYear()} Alex Morgan. All rights reserved.
        </div>
      </div>
    </footer>
  )
}
