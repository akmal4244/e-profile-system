<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$flash = renderFlash();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Admin — <?= APP_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <style>.sidebar-item.active,.sidebar-item:hover{background:rgba(255,255,255,0.15);color:white;}.sidebar-item{color:#bfdbfe;}</style>
</head>
<body class="min-h-screen bg-gray-100">
<div class="flex h-screen">
    <aside class="w-60 bg-gradient-to-b from-blue-900 to-blue-800 text-white flex flex-col flex-shrink-0">
        <div class="px-5 py-5 border-b border-blue-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-sm"></i>
                </div>
                <div><p class="font-bold text-sm"><?= APP_NAME ?></p><p class="text-blue-300 text-xs">Admin Panel</p></div>
            </div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="/admin/dashboard.php" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm <?= $currentPage==='dashboard'?'active':'' ?>">
                <i class="fas fa-chart-pie w-4"></i> Dashboard
            </a>
            <a href="/admin/profiles.php" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm <?= $currentPage==='profiles'?'active':'' ?>">
                <i class="fas fa-users w-4"></i> Profil
            </a>
            <a href="/admin/create.php" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm <?= $currentPage==='create'?'active':'' ?>">
                <i class="fas fa-user-plus w-4"></i> Tambah Profil
            </a>
            <div class="border-t border-blue-700 my-2"></div>
            <a href="/admin/settings.php" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm <?= $currentPage==='settings'?'active':'' ?>">
                <i class="fas fa-cog w-4"></i> Tetapan
            </a>
            <a href="/index.php" target="_blank" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                <i class="fas fa-external-link-alt w-4"></i> Laman Awam
            </a>
            <div class="border-t border-blue-700 my-2"></div>
            <a href="/admin/logout.php" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-300 hover:text-white">
                <i class="fas fa-sign-out-alt w-4"></i> Log Keluar
            </a>
        </nav>
        <div class="px-4 py-4 border-t border-blue-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                    <?= strtoupper(substr(getAdminName(), 0, 2)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-white truncate"><?= htmlspecialchars(getAdminName()) ?></p>
                    <p class="text-xs text-blue-300 truncate"><?= htmlspecialchars(getAdminEmail()) ?></p>
                </div>
            </div>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b px-6 py-3 flex items-center justify-between shadow-sm">
            <h1 id="page-title" class="text-lg font-semibold text-gray-800">Admin Panel</h1>
            <p class="text-sm text-gray-400"><?= date('l, d F Y') ?></p>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
            <?= $flash ?>