<?php
require_once __DIR__ . '/../config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Direktori Profil</title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body class="min-h-screen bg-gray-50">
<nav class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/index.php" class="flex items-center gap-2 font-bold text-blue-700 text-lg">
            <i class="fas fa-id-card text-blue-600"></i>
            <span><?= APP_NAME ?></span>
        </a>
        <div class="flex items-center gap-4">
            <a href="/index.php" class="text-sm text-gray-600 hover:text-blue-600">Direktori</a>
            <a href="/admin/login.php" class="text-sm bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-lock mr-1"></i>Admin
            </a>
        </div>
    </div>
</nav>