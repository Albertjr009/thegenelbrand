# Supabase Setup

1. Create a Supabase project.
2. Open **SQL Editor** and run `supabase/schema.sql`.
3. Go to **Authentication > Users** and create the student admin user with email and password.
4. Copy that user's UUID from the Users table.
5. In **SQL Editor**, run:

```sql
insert into public.portfolio_admins (user_id)
values ('PASTE-STUDENT-USER-ID-HERE')
on conflict (user_id) do nothing;
```

6. Go to **Project Settings > API** and copy the project URL and anon/public key.
7. Create `.env` from `.env.example`:

```env
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=your-public-anon-key
```

8. Restart the dev server.
9. Visit `/admin`, sign in, then add, edit, publish/unpublish, or delete portfolio works.
