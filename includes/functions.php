<?php
// ============================================================
// includes/functions.php — Utility Functions
// ============================================================
require_once __DIR__ . '/../config.php';

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function uploadPhoto($file) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > MAX_FILE_SIZE) return ['error' => 'File terlalu besar. Maksimum 2MB.'];
    if (!in_array($file['type'], ALLOWED_TYPES)) return ['error' => 'Format fail tidak disokong.'];

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('photo_', true) . '.' . strtolower($ext);
    $destination = UPLOAD_DIR . $filename;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return UPLOAD_URL . $filename;
    }
    return ['error' => 'Gagal muat naik fail.'];
}

function deletePhoto($photoUrl) {
    if (empty($photoUrl)) return;
    $file = __DIR__ . '/../' . ltrim($photoUrl, '/');
    if (file_exists($file)) unlink($file);
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->d === 0) return 'Hari ini';
    if ($diff->d === 1) return 'Semalam';
    if ($diff->d < 7) return $diff->d . ' hari lalu';
    if ($diff->d < 30) return floor($diff->d / 7) . ' minggu lalu';
    if ($diff->d < 365) return floor($diff->d / 30) . ' bulan lalu';
    return floor($diff->d / 365) . ' tahun lalu';
}

function flashMessage($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function renderFlash() {
    $flash = getFlash();
    if (!$flash) return '';
    $colors = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'error'   => 'bg-red-100 border-red-500 text-red-700',
        'info'    => 'bg-blue-100 border-blue-500 text-blue-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
    ];
    $class = $colors[$flash['type']] ?? $colors['info'];
    return "<div class=\"border-l-4 p-4 mb-4 rounded {$class}\" data-flash>" . sanitize($flash['message']) . "</div>";
}