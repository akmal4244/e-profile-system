<?php
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

$q = sanitize($_GET['q'] ?? '');
if (strlen($q) < 2) { echo json_encode([]); exit; }

$db = new Supabase();
$query = 'is_active=eq.true&or=(full_name.ilike.*' . urlencode($q) . '*,position.ilike.*' . urlencode($q) . '*)&select=id,full_name,position,photo_url&limit=5';
$result = $db->select('profiles', $query);
$profiles = $result['data'] ?? [];

$output = array_map(fn($p) => [
    'id' => $p['id'],
    'full_name' => $p['full_name'],
    'position' => $p['position'] ?? '',
    'url' => '/profile.php?id=' . $p['id'],
], $profiles);

echo json_encode($output);