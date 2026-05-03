<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/admin_layout.php';

$db = new Supabase(true);
$all    = $db->select('profiles', 'select=id,is_active,created_at,position');
$total  = count($all['data'] ?? []);
$active = count(array_filter($all['data'] ?? [], fn($p) => $p['is_active']));
$today  = count(array_filter($all['data'] ?? [], fn($p) => date('Y-m-d', strtotime($p['created_at'])) === date('Y-m-d')));

$recent = $db->select('profiles', 'order=created_at.desc&limit=5&select=id,full_name,position,photo_url,is_active,created_at');
$recentProfiles = $recent['data'] ?? [];
?>
<script>document.getElementById('page-title').textContent='Dashboard';</script>
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800"><?= $total ?></p>
                <p class="text-sm text-gray-500">Jumlah Profil</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800"><?= $active ?></p>
                <p class="text-sm text-gray-500">Profil Aktif</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-day text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800"><?= $today ?></p>
                <p class="text-sm text-gray-500">Profil Hari Ini</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-clock text-blue-500 mr-2"></i>Profil Terkini</h3>
            <a href="/admin/profiles.php" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y">
            <?php if (empty($recentProfiles)): ?>
            <p class="text-center text-gray-400 py-8 text-sm">Tiada profil lagi.</p>
            <?php else: foreach ($recentProfiles as $p):
                $photo = !empty($p['photo_url']) ? htmlspecialchars($p['photo_url']) : '/assets/images/default-avatar.svg';
            ?>
            <div class="flex items-center gap-4 px-6 py-3">
                <img src="<?= $photo ?>" class="w-10 h-10 rounded-full object-cover" onerror="this.src='/assets/images/default-avatar.svg'">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm truncate"><?= htmlspecialchars($p['full_name']) ?></p>
                    <p class="text-xs text-gray-400 truncate"><?= htmlspecialchars($p['position'] ?? '-') ?></p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full <?= $p['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                    <?= $p['is_active'] ? 'Aktif' : 'Tidak Aktif' ?>
                </span>
                <div class="flex gap-1">
                    <a href="/admin/view.php?id=<?= $p['id'] ?>" class="p-1.5 text-gray-400 hover:text-blue-600 text-sm"><i class="fas fa-eye"></i></a>
                    <a href="/admin/edit.php?id=<?= $p['id'] ?>" class="p-1.5 text-gray-400 hover:text-yellow-500 text-sm"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="/admin/create.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition text-sm font-semibold">
            <i class="fas fa-plus mr-2"></i>Tambah Profil Baru
        </a>
        <a href="/index.php" target="_blank" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl hover:bg-gray-200 transition text-sm">
            <i class="fas fa-eye mr-2"></i>Lihat Laman Awam
        </a>
    </div>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>