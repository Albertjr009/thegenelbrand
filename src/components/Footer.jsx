const InstagramLogo = () => (
  <svg className="app-logo" viewBox="0 0 24 24" aria-hidden="true">
    <rect x="3" y="3" width="18" height="18" rx="5" />
    <circle cx="12" cy="12" r="4" />
    <circle cx="17.5" cy="6.5" r="1.2" />
  </svg>
)

const LinkedInLogo = () => (
  <svg className="app-logo app-logo-linkedin" viewBox="0 0 24 24" aria-hidden="true">
    <path d="M6.5 9.5v8" />
    <path d="M6.5 6.5h.01" />
    <path d="M11 17.5v-8" />
    <path d="M11 13.2c0-2.4 1.4-3.9 3.5-3.9 2 0 3 1.3 3 3.6v4.6" />
    <rect x="3" y="3" width="18" height="18" rx="2.5" />
  </svg>
)

export default function Footer() {
  return (
    <footer id="contact" className="site-footer">
      <div className="container-max footer-inner">
        <div className="footer-grid">
          <div>
            <div className="footer-name">Genevieve</div>
            <div className="footer-muted">Fashion Design Portfolio</div>
          </div>
          <div>
            <div className="footer-title">Contact</div>
            <a href="mailto:genevieve@example.com" className="footer-link">
              genevieve@example.com
            </a>
            <a href="tel:+233000000000" className="footer-link">
              +233 000 000 000
            </a>
          </div>
          <div>
            <div className="footer-title">Applications</div>
            <div className="app-button-row">
              <a href="https://www.instagram.com/thegenell_brand" className="app-logo-link" aria-label="Instagram">
                <InstagramLogo />
              </a>
              <a href="https://www.linkedin.com/" className="app-logo-link" aria-label="LinkedIn">
                <LinkedInLogo />
              </a>
            </div>
          </div>
        </div>
        <div className="copyright">
          Copyright {new Date().getFullYear()} thegenelbrand. All rights reserved.
        </div>
      </div>
    </footer>
  )
}
