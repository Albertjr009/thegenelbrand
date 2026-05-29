import { useEffect, useState } from 'react'
import { getPublishedWorks } from '../lib/portfolioApi'

const Project = ({ title, type, detail, image }) => (
  <article className="project-card">
    <div className="project-image">
      <img src={image} alt={`${title} fashion portfolio project`} />
    </div>
    <div className="project-content">
      <p>{type}</p>
      <h3>{title}</h3>
      <span>{detail}</span>
    </div>
  </article>
)

export default function Portfolio() {
  const [projects, setProjects] = useState([])
  const [loading, setLoading] = useState(true)
  const [usingFallback, setUsingFallback] = useState(false)

  useEffect(() => {
    let mounted = true

    getPublishedWorks().then(({ works, usingFallback: fallback }) => {
      if (!mounted) return
      setProjects(works)
      setUsingFallback(fallback)
      setLoading(false)
    })

    return () => {
      mounted = false
    }
  }, [])

  return (
    <section id="portfolio" className="section section-muted">
      <div className="container-max">
        <div className="section-heading">
          <p className="eyebrow">Selected work</p>
          <h2>Collections, experiments, and styled stories</h2>
          {usingFallback && (
            <p className="section-note">Connect Supabase to replace these sample works.</p>
          )}
        </div>
        {loading ? (
          <p className="load-note">Loading portfolio...</p>
        ) : (
          <div className="project-grid">
            {projects.map((project) => (
              <Project key={project.id || project.title} {...project} />
            ))}
          </div>
        )}
      </div>
    </section>
  )
}
