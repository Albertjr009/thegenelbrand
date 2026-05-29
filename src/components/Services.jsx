const ServiceCard = ({ title, children }) => (
  <div className="skill-card">
    <h3>{title}</h3>
    <p>{children}</p>
  </div>
)

export default function Services() {
  return (
    <section className="section">
      <div className="container-max">
        <div className="section-heading">
          <p className="eyebrow">Practice</p>
          <h2>Skills built in the studio</h2>
        </div>
        <div className="skill-grid">
          <ServiceCard title="Research and Concept">
            Moodboards, market research, visual narratives, and collection direction.
          </ServiceCard>
          <ServiceCard title="Pattern and Draping">
            Toile development, flat pattern cutting, drape studies, and fit refinement.
          </ServiceCard>
          <ServiceCard title="Styling and Lookbooks">
            Editorial styling, garment documentation, shoot planning, and art direction.
          </ServiceCard>
        </div>
      </div>
    </section>
  )
}
