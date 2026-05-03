<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/admin_layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: /admin/profiles.php'); exit; }
$db = new Supabase(true);
$res = $db->select('profiles', 'id=eq.' . $id);
$p = $res['data'][0] ?? null;
if (!$p) { header('Location: /admin/profiles.php'); exit; }

$skills = is_array($p['skills']) ? $p['skills'] : (json_decode($p['skills'] ?? '[]', true) ?? []);
$photoUrl = !empty($p['photo_url']) ? htmlspecialchars($p['photo_url']) : '/assets/images/default-avatar.svg';
?>
<script>document.getElementById('page-title').textContent='Lihat Profil';</script>
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/profiles.php" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <h2 class="text-xl font-bold text-gray-800">Butiran Profil</h2>
        <div class="ml-auto flex gap-2">
            <a href="/admin/edit.php?id=<?= $p['id'] ?>" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700"><i class="fas fa-edit mr-1"></i>Edit</a>
            <a href="/profile.php?id=<?= $p['id'] ?>" target="_blank" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm hover:bg-gray-200"><i class="fas fa-external-link-alt mr-1"></i>Lihat Awam</a>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="hero-gradient h-28 relative">
            <img src="<?= $photoUrl ?>" class="w-20 h-20 rounded-full border-4 border-white object-cover absolute -bottom-8 left-6 shadow-lg" onerror="this.src='/assets/images/default-avatar.svg'">
            <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium <?= $p['is_active'] ? 'bg-green-400 text-white' : 'bg-gray-300 text-gray-700' ?>"><?= $p['is_active'] ? 'Aktif' : 'Tidak Aktif' ?></span>
        </div>
        <div class="pt-12 pb-6 px-6">
            <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($p['full_name']) ?></h3>
            <p class="text-blue-600 font-medium"><?= htmlspecialchars($p['position'] ?? '-') ?></p>
            <p class="text-gray-400 text-sm"><?= htmlspecialchars($p['department'] ?? '') ?></p>
            <?php if (!empty($p['bio'])): ?>
            <div class="mt-4 p-3 bg-blue-50 rounded-xl text-sm text-gray-600"><?= nl2br(htmlspecialchars($p['bio'])) ?></div>
            <?php endif; ?>
            <?php if (!empty($skills)): ?>
            <div class="mt-4 flex flex-wrap gap-2">
                <?php foreach ($skills as $s): ?>
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full"><?= htmlspecialchars($s) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="mt-4 pt-4 border-t text-xs text-gray-400 flex justify-between">
                <span>ID: #<?= $p['id'] ?></span>
                <span>Dicipta: <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></span>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>