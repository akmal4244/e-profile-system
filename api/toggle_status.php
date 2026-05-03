<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/supabase.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false]); exit; }

$id = (int)($_POST['id'] ?? 0);
$status = ($_POST['status'] ?? '0') === '1';
if (!$id) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

$db = new Supabase(true);
$result = $db->update('profiles', $id, ['is_active' => $status]);
echo json_encode($result['status'] === 200 ? ['success'=>true,'is_active'=>$status] : ['success'=>false]);