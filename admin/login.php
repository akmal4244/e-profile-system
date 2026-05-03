<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../config.php';

if (isLoggedIn()) { header('Location: /admin/dashboard.php'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Sila isi semua medan.';
    } else {
        $db = new Supabase();
        $result = $db->signIn($email, $password);

        if ($result['status'] === 200 && !empty($result['data']['access_token'])) {
            $userData = $result['data']['user'] ?? [];
            $name = $userData['user_metadata']['name'] ?? explode('@', $email)[0];
            loginAdmin($email, $name, $result['data']['access_token']);
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            $error = 'E-mel atau kata laluan tidak sah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-700 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center text-white mb-8">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-id-card text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold"><?= APP_NAME ?></h1>
            <p class="text-blue-200 text-sm mt-1">Portal Pentadbir</p>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Log Masuk Admin</h2>
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-300 text-red-600 text-sm p-3 rounded-lg mb-5">
                <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
            </div>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mel</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                        <input type="email" name="email" required placeholder="admin@contoh.com"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                        <input type="password" name="password" id="password" required
                               class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" onclick="togglePass()" class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Log Masuk
                </button>
            </form>
            <p class="mt-4 text-center text-xs text-gray-400">
                <a href="/index.php" class="text-blue-600 hover:underline">← Kembali ke Laman Utama</a>
            </p>
        </div>
    </div>
    <script>
    function togglePass() {
        const i = document.getElementById('password');
        const e = document.getElementById('eyeIcon');
        i.type = i.type==='password'?'text':'password';
        e.className = i.type==='password'?'fas fa-eye text-sm':'fas fa-eye-slash text-sm';
    }
    </script>
</body>
</html>