const heroImage =
  'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=980&q=80'
const detailImage =
  'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=520&q=80'
const studioImage =
  'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=520&q=80'

export default function Hero() {
  return (
    <section className="hero-section">
      <div className="container-max hero-inner">
        <div className="hero-backdrop" aria-hidden="true">
          <span />
          <span />
          <span />
        </div>
        <div className="hero-grid">
          <div className="hero-copy-block animate-in">
            <p className="eyebrow">BSc Fashion Design Portfolio</p>
            <h1>Genevieve designs garments with movement, texture, and mood.</h1>
            <p className="hero-copy">
              A modern fashion portfolio shaped around pattern cutting, sustainable
              textile decisions, and editorial storytelling for womenswear.
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
          <div className="hero-collage animate-in delay-1">
            <figure className="hero-image-wrap">
              <img
                src={heroImage}
                alt="Editorial fashion look used as a portfolio mood image"
                className="hero-image"
              />
            </figure>
            <figure className="hero-mini hero-mini-top">
              <img src={detailImage} alt="Fabric and garment detail" />
            </figure>
            <figure className="hero-mini hero-mini-bottom">
              <img src={studioImage} alt="Fashion studio styling detail" />
            </figure>
            <div className="hero-note">
              <span>Current focus</span>
              <strong>Tailoring, sustainable textiles, and portfolio-ready lookbooks.</strong>
            </div>
            <div className="hero-swatches" aria-hidden="true">
              <span />
              <span />
              <span />
              <span />
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
