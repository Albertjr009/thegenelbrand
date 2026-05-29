import { useEffect, useMemo, useState } from 'react'
import { portfolioBucket, portfolioTable, isSupabaseConfigured, supabase } from '../lib/supabaseClient'

const emptyWork = {
  title: '',
  category: '',
  description: '',
  image_url: '',
  sort_order: 0,
  is_published: true,
}

const getCleanFileName = (fileName) =>
  fileName.toLowerCase().replace(/[^a-z0-9.]+/g, '-').replace(/(^-|-$)/g, '')

const fetchWorks = async () => {
  const { data, error } = await supabase
    .from(portfolioTable)
    .select('*')
    .order('sort_order', { ascending: true })
    .order('created_at', { ascending: false })

  if (error) throw error
  return data
}

const fetchAdminAccess = async (userId) => {
  const { data, error } = await supabase
    .from('portfolio_admins')
    .select('user_id')
    .eq('user_id', userId)
    .maybeSingle()

  return Boolean(data) && !error
}

export default function Admin() {
  const [session, setSession] = useState(null)
  const [authLoading, setAuthLoading] = useState(isSupabaseConfigured)
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [works, setWorks] = useState([])
  const [form, setForm] = useState(emptyWork)
  const [imageFile, setImageFile] = useState(null)
  const [editingId, setEditingId] = useState(null)
  const [status, setStatus] = useState('')
  const [busy, setBusy] = useState(false)
  const [isAdmin, setIsAdmin] = useState(false)
  const publishedCount = works.filter((work) => work.is_published).length
  const draftCount = works.length - publishedCount
  const previewUploadUrl = useMemo(() => {
    if (!imageFile) return ''
    return URL.createObjectURL(imageFile)
  }, [imageFile])
  const previewImage = previewUploadUrl || form.image_url

  useEffect(() => {
    return () => {
      if (previewUploadUrl) URL.revokeObjectURL(previewUploadUrl)
    }
  }, [previewUploadUrl])

  const loadWorks = async () => {
    try {
      setWorks(await fetchWorks())
    } catch (error) {
      setStatus(error.message)
    }
  }

  useEffect(() => {
    if (!isSupabaseConfigured) return

    supabase.auth.getSession().then(({ data }) => {
      setSession(data.session)
      setAuthLoading(false)
    })

    const { data } = supabase.auth.onAuthStateChange((_event, nextSession) => {
      setSession(nextSession)
    })

    return () => {
      data.subscription.unsubscribe()
    }
  }, [])

  useEffect(() => {
    if (!session) return

    let mounted = true

    Promise.all([fetchAdminAccess(session.user.id), fetchWorks()])
      .then(([adminAccess, nextWorks]) => {
        if (!mounted) return
        setIsAdmin(adminAccess)
        setWorks(nextWorks)
      })
      .catch((error) => {
        if (!mounted) return
        setStatus(error.message)
      })

    return () => {
      mounted = false
    }
  }, [session])

  const handleLogin = async (event) => {
    event.preventDefault()
    setBusy(true)
    setStatus('')

    const { error } = await supabase.auth.signInWithPassword({ email, password })
    setBusy(false)

    if (error) {
      setStatus(error.message)
    }
  }

  const handleLogout = async () => {
    await supabase.auth.signOut()
    setWorks([])
    setIsAdmin(false)
  }

  const updateForm = (field, value) => {
    setForm((current) => ({ ...current, [field]: value }))
  }

  const resetForm = () => {
    setForm(emptyWork)
    setImageFile(null)
    setEditingId(null)
    setStatus('')
  }

  const editWork = (work) => {
    setEditingId(work.id)
    setForm({
      title: work.title,
      category: work.category,
      description: work.description,
      image_url: work.image_url,
      sort_order: work.sort_order,
      is_published: work.is_published,
    })
    setImageFile(null)
    setStatus('')
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const uploadImage = async () => {
    if (!imageFile) return form.image_url.trim()

    const filePath = `${session.user.id}/${Date.now()}-${getCleanFileName(imageFile.name)}`
    const { error } = await supabase.storage
      .from(portfolioBucket)
      .upload(filePath, imageFile, { cacheControl: '3600', upsert: false })

    if (error) throw error

    const { data } = supabase.storage.from(portfolioBucket).getPublicUrl(filePath)
    return data.publicUrl
  }

  const saveWork = async (event) => {
    event.preventDefault()
    setBusy(true)
    setStatus('')

    try {
      const imageUrl = await uploadImage()
      const payload = {
        title: form.title.trim(),
        category: form.category.trim(),
        description: form.description.trim(),
        image_url: imageUrl,
        sort_order: Number(form.sort_order) || 0,
        is_published: form.is_published,
      }

      if (!payload.title || !payload.category || !payload.description || !payload.image_url) {
        throw new Error('Please fill title, category, description, and image.')
      }

      const request = editingId
        ? supabase.from(portfolioTable).update(payload).eq('id', editingId)
        : supabase.from(portfolioTable).insert(payload)

      const { error } = await request
      if (error) throw error

      setStatus(editingId ? 'Work updated.' : 'Work added.')
      resetForm()
      await loadWorks()
    } catch (error) {
      setStatus(error.message)
    } finally {
      setBusy(false)
    }
  }

  const deleteWork = async (work) => {
    if (!window.confirm(`Delete "${work.title}"?`)) return

    setBusy(true)
    setStatus('')

    const { error } = await supabase.from(portfolioTable).delete().eq('id', work.id)
    setBusy(false)

    if (error) {
      setStatus(error.message)
      return
    }

    setStatus('Work deleted.')
    await loadWorks()
  }

  if (!isSupabaseConfigured) {
    return (
      <main className="admin-page">
        <section className="admin-card admin-login">
          <p className="eyebrow">Supabase setup needed</p>
          <h1>Connect the project first</h1>
          <p className="admin-help">
            Add your Supabase URL and anon key to a local `.env` file, then restart the dev server.
            Use `.env.example` as the template.
          </p>
        </section>
      </main>
    )
  }

  if (authLoading) {
    return <main className="admin-page"><p className="load-note">Checking session...</p></main>
  }

  if (!session) {
    return (
      <main className="admin-page">
        <section className="admin-login-shell">
          <div className="admin-login-art" aria-hidden="true">
            <img
              src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80"
              alt=""
            />
            <div>
              <span>Studio Control</span>
              <strong>Update the portfolio without touching code.</strong>
            </div>
          </div>
          <div className="admin-card admin-login">
            <p className="eyebrow">Portfolio admin</p>
            <h1>Sign in</h1>
            <form className="admin-form" onSubmit={handleLogin}>
              <label>
                Email
                <input value={email} onChange={(event) => setEmail(event.target.value)} type="email" />
              </label>
              <label>
                Password
                <input
                  value={password}
                  onChange={(event) => setPassword(event.target.value)}
                  type="password"
                />
              </label>
              <button className="button button-dark" disabled={busy} type="submit">
                {busy ? 'Signing in...' : 'Sign in'}
              </button>
            </form>
            {status && <p className="admin-status">{status}</p>}
          </div>
        </section>
      </main>
    )
  }

  return (
    <main className="admin-page">
      <div className="container-max">
        <div className="admin-topbar">
          <div>
            <p className="eyebrow">Portfolio admin</p>
            <h1>Studio dashboard</h1>
          </div>
          <div className="admin-actions">
            <a className="button button-light" href="/">View site</a>
            <button className="button button-dark" onClick={handleLogout} type="button">Sign out</button>
          </div>
        </div>

        <section className="admin-stats" aria-label="Portfolio summary">
          <div>
            <span>Total works</span>
            <strong>{works.length}</strong>
          </div>
          <div>
            <span>Published</span>
            <strong>{publishedCount}</strong>
          </div>
          <div>
            <span>Drafts</span>
            <strong>{draftCount}</strong>
          </div>
        </section>

        {!isAdmin && (
          <div className="admin-warning">
            You are signed in, but this user is not listed in `portfolio_admins` yet. Add the user ID
            in Supabase before saving changes.
          </div>
        )}

        <section className="admin-layout">
          <form className="admin-card admin-form" onSubmit={saveWork}>
            <h2>{editingId ? 'Edit work' : 'Add work'}</h2>
            <div className="admin-preview">
              {previewImage ? (
                <img src={previewImage} alt="" />
              ) : (
                <div>
                  <span>Image preview</span>
                </div>
              )}
            </div>
            <div className="form-grid">
              <label>
                Title
                <input value={form.title} onChange={(event) => updateForm('title', event.target.value)} />
              </label>
              <label>
                Category
                <input
                  value={form.category}
                  onChange={(event) => updateForm('category', event.target.value)}
                  placeholder="Tailoring capsule"
                />
              </label>
            </div>
            <label>
              Description
              <textarea
                value={form.description}
                onChange={(event) => updateForm('description', event.target.value)}
                rows="4"
              />
            </label>
            <label>
              Image URL
              <input
                value={form.image_url}
                onChange={(event) => updateForm('image_url', event.target.value)}
                placeholder="Paste an image URL or upload below"
              />
            </label>
            <label>
              Upload image
              <input
                accept="image/*"
                onChange={(event) => setImageFile(event.target.files?.[0] || null)}
                type="file"
              />
            </label>
            <div className="form-grid">
              <label>
                Sort order
                <input
                  value={form.sort_order}
                  onChange={(event) => updateForm('sort_order', event.target.value)}
                  type="number"
                />
              </label>
              <label className="checkbox-row">
                <input
                  checked={form.is_published}
                  onChange={(event) => updateForm('is_published', event.target.checked)}
                  type="checkbox"
                />
                Published
              </label>
            </div>
            <div className="admin-actions">
              <button className="button button-dark" disabled={busy || !isAdmin} type="submit">
                {busy ? 'Saving...' : editingId ? 'Update work' : 'Add work'}
              </button>
              {editingId && (
                <button className="button button-light" onClick={resetForm} type="button">
                  Cancel edit
                </button>
              )}
            </div>
            {status && <p className="admin-status">{status}</p>}
          </form>

          <section className="admin-card">
            <h2>Existing works</h2>
            <div className="work-list">
              {works.map((work) => (
                <article className="work-list-item" key={work.id}>
                  <img src={work.image_url} alt="" />
                  <div>
                    <p>{work.category}</p>
                    <h3>{work.title}</h3>
                    <span>{work.is_published ? 'Published' : 'Draft'}</span>
                  </div>
                  <div className="work-actions">
                    <button onClick={() => editWork(work)} type="button">Edit</button>
                    <button onClick={() => deleteWork(work)} type="button">Delete</button>
                  </div>
                </article>
              ))}
              {works.length === 0 && <p className="load-note">No works added yet.</p>}
            </div>
          </section>
        </section>
      </div>
    </main>
  )
}
