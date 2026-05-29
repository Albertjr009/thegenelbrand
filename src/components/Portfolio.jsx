const projects = [
  {
    title: 'Quiet Structure',
    type: 'Tailoring capsule',
    detail: 'Wool suiting, hand-finished hems, detachable collar study',
    image:
      'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'Second Skin',
    type: 'Sustainable textiles',
    detail: 'Reworked denim, natural dye tests, zero-waste panel layout',
    image:
      'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'Studio Movement',
    type: 'Editorial lookbook',
    detail: 'Six-look story exploring volume, shadow, and motion',
    image:
      'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'Drape Notes',
    type: 'Process study',
    detail: 'Muslin experiments translated into asymmetric daywear',
    image:
      'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80',
  },
]

const Project = ({ title, type, detail, image }) => (
  <article className="project-card">
    <img src={image} alt={`${title} fashion portfolio project`} />
    <div>
      <p>{type}</p>
      <h3>{title}</h3>
      <span>{detail}</span>
    </div>
  </article>
)

export default function Portfolio() {
  return (
    <section id="portfolio" className="section section-muted">
      <div className="container-max">
        <div className="section-heading">
          <p className="eyebrow">Selected work</p>
          <h2>Collections, experiments, and styled stories</h2>
        </div>
        <div className="project-grid">
          {projects.map((project) => (
            <Project key={project.title} {...project} />
          ))}
        </div>
      </div>
    </section>
  )
}
