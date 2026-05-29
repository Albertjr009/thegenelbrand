const heroImage =
  'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=980&q=80'

export default function Hero() {
  return (
    <section className="hero-section">
      <div className="container-max hero-inner">
        <div className="hero-grid">
          <div>
            <p className="eyebrow">BSc Fashion Design Portfolio</p>
            <h1>Alex Morgan</h1>
            <p className="hero-copy">
              I design clothing through cut, texture, and research. My work explores
              responsible materials, precise patternmaking, and strong visual storytelling
              for womenswear and editorial styling.
            </p>
            <div className="hero-actions">
              <a href="#portfolio" className="button button-dark">View Work</a>
              <a href="#about" className="button button-light">Design Approach</a>
            </div>
            <dl className="hero-stats">
              <div>
                <dt className="stat">06</dt>
                <dd>Collections</dd>
              </div>
              <div>
                <dt className="stat">14</dt>
                <dd>Garments</dd>
              </div>
              <div>
                <dt className="stat">2026</dt>
                <dd>Graduate year</dd>
              </div>
            </dl>
          </div>
          <div className="hero-image-wrap">
            <img
              src={heroImage}
              alt="Editorial fashion look used as a portfolio mood image"
              className="hero-image"
            />
            <div className="hero-note">
              <span>Current focus</span>
              <strong>Tailoring, sustainable textiles, and portfolio-ready lookbooks.</strong>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
