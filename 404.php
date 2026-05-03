<?php
http_response_code(404);
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Dijumpai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-blue-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-8xl font-black text-blue-200 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Halaman Tidak Dijumpai</h1>
        <p class="text-gray-500 mb-8">Maaf, halaman yang anda cari tidak wujud.</p>
        <a href="/index.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition font-medium">← Kembali ke Laman Utama</a>
    </div>
</body>
</html>