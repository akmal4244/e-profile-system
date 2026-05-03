<?php
// ============================================================
// config.php — Supabase Configuration
// ============================================================
define('SUPABASE_URL', 'https://YOUR_PROJECT_REF.supabase.co');
define('SUPABASE_ANON_KEY', 'YOUR_SUPABASE_ANON_KEY');
define('SUPABASE_SERVICE_KEY', 'YOUR_SUPABASE_SERVICE_ROLE_KEY');
define('APP_NAME', 'e-Profile System');
define('APP_VERSION', '1.0.0');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', '/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);