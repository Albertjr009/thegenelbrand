-- Paste this whole file into Supabase SQL Editor and run it.
-- After creating the admin auth user, replace the placeholder user id near the bottom.

create extension if not exists pgcrypto;

create schema if not exists private;

create table if not exists public.portfolio_admins (
  user_id uuid primary key references auth.users(id) on delete cascade,
  created_at timestamptz not null default now()
);

create table if not exists public.portfolio_works (
  id uuid primary key default gen_random_uuid(),
  title text not null check (length(title) between 1 and 120),
  category text not null check (length(category) between 1 and 80),
  description text not null check (length(description) between 1 and 500),
  image_url text not null,
  sort_order integer not null default 0,
  is_published boolean not null default true,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create or replace function public.set_updated_at()
returns trigger
language plpgsql
as $$
begin
  new.updated_at = now();
  return new;
end;
$$;

drop trigger if exists portfolio_works_set_updated_at on public.portfolio_works;

create trigger portfolio_works_set_updated_at
before update on public.portfolio_works
for each row
execute function public.set_updated_at();

create or replace function private.is_portfolio_admin()
returns boolean
language sql
security definer
set search_path = public
as $$
  select exists (
    select 1
    from public.portfolio_admins
    where user_id = (select auth.uid())
  );
$$;

revoke all on function private.is_portfolio_admin() from public;
grant usage on schema private to authenticated;
grant execute on function private.is_portfolio_admin() to authenticated;

alter table public.portfolio_admins enable row level security;
alter table public.portfolio_works enable row level security;

drop policy if exists "Admins can read their own admin row" on public.portfolio_admins;
create policy "Admins can read their own admin row"
on public.portfolio_admins
for select
to authenticated
using (user_id = (select auth.uid()));

drop policy if exists "Anyone can read published portfolio works" on public.portfolio_works;
create policy "Anyone can read published portfolio works"
on public.portfolio_works
for select
to anon, authenticated
using (is_published = true);

drop policy if exists "Admins can read every portfolio work" on public.portfolio_works;
create policy "Admins can read every portfolio work"
on public.portfolio_works
for select
to authenticated
using (private.is_portfolio_admin());

drop policy if exists "Admins can add portfolio works" on public.portfolio_works;
create policy "Admins can add portfolio works"
on public.portfolio_works
for insert
to authenticated
with check (private.is_portfolio_admin());

drop policy if exists "Admins can update portfolio works" on public.portfolio_works;
create policy "Admins can update portfolio works"
on public.portfolio_works
for update
to authenticated
using (private.is_portfolio_admin())
with check (private.is_portfolio_admin());

drop policy if exists "Admins can delete portfolio works" on public.portfolio_works;
create policy "Admins can delete portfolio works"
on public.portfolio_works
for delete
to authenticated
using (private.is_portfolio_admin());

insert into storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
values (
  'portfolio-images',
  'portfolio-images',
  true,
  5242880,
  array['image/jpeg', 'image/png', 'image/webp', 'image/gif']
)
on conflict (id) do update
set public = excluded.public,
    file_size_limit = excluded.file_size_limit,
    allowed_mime_types = excluded.allowed_mime_types;

drop policy if exists "Anyone can read portfolio images" on storage.objects;
create policy "Anyone can read portfolio images"
on storage.objects
for select
to public
using (bucket_id = 'portfolio-images');

drop policy if exists "Admins can upload portfolio images" on storage.objects;
create policy "Admins can upload portfolio images"
on storage.objects
for insert
to authenticated
with check (bucket_id = 'portfolio-images' and private.is_portfolio_admin());

drop policy if exists "Admins can update portfolio images" on storage.objects;
create policy "Admins can update portfolio images"
on storage.objects
for update
to authenticated
using (bucket_id = 'portfolio-images' and private.is_portfolio_admin())
with check (bucket_id = 'portfolio-images' and private.is_portfolio_admin());

drop policy if exists "Admins can delete portfolio images" on storage.objects;
create policy "Admins can delete portfolio images"
on storage.objects
for delete
to authenticated
using (bucket_id = 'portfolio-images' and private.is_portfolio_admin());

insert into public.portfolio_works
  (id, title, category, description, image_url, sort_order, is_published)
values
  (
    '00000000-0000-0000-0000-000000000001',
    'Quiet Structure',
    'Tailoring capsule',
    'Wool suiting, hand-finished hems, detachable collar study',
    'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80',
    10,
    true
  ),
  (
    '00000000-0000-0000-0000-000000000002',
    'Second Skin',
    'Sustainable textiles',
    'Reworked denim, natural dye tests, zero-waste panel layout',
    'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=900&q=80',
    20,
    true
  ),
  (
    '00000000-0000-0000-0000-000000000003',
    'Studio Movement',
    'Editorial lookbook',
    'Six-look story exploring volume, shadow, and motion',
    'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
    30,
    true
  ),
  (
    '00000000-0000-0000-0000-000000000004',
    'Drape Notes',
    'Process study',
    'Muslin experiments translated into asymmetric daywear',
    'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80',
    40,
    true
  )
on conflict (id) do nothing;

-- Run this after creating the admin user in Authentication > Users.
-- Replace the UUID with that user's ID, then run only this insert:
-- insert into public.portfolio_admins (user_id)
-- values ('00000000-0000-0000-0000-000000000000')
-- on conflict (user_id) do nothing;
