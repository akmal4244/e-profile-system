<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/profiles.php'); exit; }

$id = (int)($_POST['id'] ?? 0);
if (!$id) { flashMessage('error', 'ID tidak sah.'); header('Location: /admin/profiles.php'); exit; }

$db = new Supabase(true);
$profile = $db->select('profiles', 'id=eq.' . $id);
$photoUrl = $profile['data'][0]['photo_url'] ?? null;

$result = $db->delete('profiles', $id);
if ($result['status'] === 200 || $result['status'] === 204) {
    if ($photoUrl) deletePhoto($photoUrl);
    flashMessage('success', 'Profil berjaya dipadam.');
} else {
    flashMessage('error', 'Gagal memadam profil.');
}

header('Location: /admin/profiles.php');
exit;