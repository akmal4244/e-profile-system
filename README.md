# 📇 e-Profile System

Sistem direktori profil profesional dengan admin panel lengkap.

## 🔧 Tech Stack
- **Frontend**: HTML5 + Tailwind CSS (CDN) + Vanilla JavaScript
- **Backend**: PHP 8+
- **Database**: Supabase (PostgreSQL)
- **Auth**: Supabase Auth (email/password)

## 📂 Struktur Folder
```
/e-profile-system
  /admin          → Login, Dashboard, CRUD pages
  /api            → PHP API handlers
  /assets         → CSS, JS, Images
  /database       → SQL schema
  /includes       → Shared PHP components
  /uploads        → User uploaded photos
  config.php      → Configuration
  index.php       → Public profile listing
  profile.php     → Single profile view
```

## ⚙️ Setup

### 1. Run SQL Schema
Supabase Dashboard → SQL Editor → Run `database/schema.sql`

### 2. Buat Admin User
Supabase Dashboard → Authentication → Users → Add user

### 3. Edit `config.php`
```php
define('SUPABASE_URL', 'https://XXXXXX.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGci...');
define('SUPABASE_SERVICE_KEY', 'eyJhbGci...');
```

### 4. Upload ke Hosting
Extract ke `public_html/` dan pastikan `uploads/` ada permission `755`

## 🔐 Ciri Keselamatan
- Supabase Auth (bcrypt)
- PHP Session guard
- Row Level Security (RLS)
- Upload validation
- Security headers
