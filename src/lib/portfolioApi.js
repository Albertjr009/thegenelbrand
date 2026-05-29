import { sampleProjects } from '../data/sampleProjects'
import { isSupabaseConfigured, portfolioTable, supabase } from './supabaseClient'

const mapWork = (work) => ({
  id: work.id,
  title: work.title,
  type: work.category,
  detail: work.description,
  image: work.image_url,
  sort_order: work.sort_order,
  is_published: work.is_published,
})

export async function getPublishedWorks() {
  if (!isSupabaseConfigured) {
    return { works: sampleProjects, usingFallback: true }
  }

  const { data, error } = await supabase
    .from(portfolioTable)
    .select('id,title,category,description,image_url,sort_order,is_published')
    .eq('is_published', true)
    .order('sort_order', { ascending: true })
    .order('created_at', { ascending: false })

  if (error) {
    console.error('Could not load portfolio works:', error)
    return { works: sampleProjects, usingFallback: true }
  }

  return { works: data.map(mapWork), usingFallback: false }
}
