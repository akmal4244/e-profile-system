-- ============================================================
-- e-Profile System — Supabase Database Schema
-- Run this in: Supabase Dashboard > SQL Editor
-- ============================================================

CREATE TABLE IF NOT EXISTS public.profiles (
    id              BIGSERIAL PRIMARY KEY,
    full_name       TEXT NOT NULL,
    position        TEXT,
    department      TEXT,
    email           TEXT,
    phone           TEXT,
    bio             TEXT,
    location        TEXT,
    website         TEXT,
    photo_url       TEXT,
    skills          JSONB DEFAULT '[]',
    portfolio_links JSONB DEFAULT '[]',
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMPTZ DEFAULT NOW(),
    updated_at      TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Public can view active profiles"
    ON public.profiles FOR SELECT
    USING (is_active = TRUE);

CREATE POLICY "Service role full access"
    ON public.profiles FOR ALL
    USING (auth.role() = 'service_role');

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN NEW.updated_at = NOW(); RETURN NEW; END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_profiles_updated_at
    BEFORE UPDATE ON public.profiles
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE INDEX IF NOT EXISTS idx_profiles_is_active ON public.profiles(is_active);

-- Sample data
INSERT INTO public.profiles (full_name, position, department, email, skills, bio, is_active) VALUES
    ('Ahmad bin Ismail', 'Pengaturcara Komputer', 'Bahagian ICT', 'ahmad@example.com',
     '["PHP", "JavaScript", "MySQL"]', 'Pegawai ICT berpengalaman 5 tahun.', TRUE),
    ('Siti Rahimah bt. Othman', 'Pentadbir Sistem', 'Unit Pentadbiran', 'siti@example.com',
     '["Microsoft Office", "HRMS"]', 'Pakar pentadbiran sumber manusia.', TRUE);