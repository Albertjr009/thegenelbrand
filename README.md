# The Genel Brand Portfolio

A modern fashion design portfolio for Genevieve. The site presents selected works, studio practice, contact details, and a private admin dashboard for managing portfolio projects.

## Overview

The public site is built for a fashion student portfolio with an editorial visual style. It includes:

- Hero section with fashion imagery and design focus
- Studio skills and practice areas
- Portfolio grid powered by Supabase
- About section
- Contact footer with social links
- Admin dashboard for adding, editing, publishing, and deleting works

## Tech Stack

- React
- Vite
- Supabase Auth
- Supabase Database
- Supabase Storage
- Plain CSS

## Getting Started

Install dependencies:

```bash
npm install
```

Start the development server:

```bash
npm.cmd run dev
```

Open:

```text
http://127.0.0.1:5173
```

Admin dashboard:

```text
http://127.0.0.1:5173/admin
```

## Supabase Setup

Create a local `.env` file using `.env.example`:

```env
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=your-public-anon-key
```

The Supabase URL must be the base project URL only. Do not include `/rest/v1/`.

Then follow `SUPABASE_SETUP.md` and run `supabase/schema.sql` in the Supabase SQL Editor.

## Admin Workflow

The admin dashboard lets the portfolio owner:

- Sign in with a Supabase Auth user
- Add a new portfolio work
- Upload or paste an image URL
- Edit title, category, description, order, and publish status
- Delete existing works

Only users added to the `portfolio_admins` table can save changes.

## Scripts

```bash
npm.cmd run dev
npm.cmd run build
npm.cmd run lint
npm.cmd run preview
```

## Project Structure

```text
src/components/       Page sections and admin dashboard
src/lib/              Supabase client and portfolio API
src/data/             Sample fallback portfolio data
supabase/schema.sql   Database, security, storage, and seed setup
SUPABASE_SETUP.md     Supabase setup guide
```

## Notes

When Supabase is not configured, the public portfolio shows sample works. Once `.env` is connected and the schema has been run, the public portfolio loads published works from Supabase.
