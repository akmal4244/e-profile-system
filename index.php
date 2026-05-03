<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/supabase.php';
require_once __DIR__ . '/includes/functions.php';

$db = new Supabase();
$search = sanitize($_GET['search'] ?? '');
$filter = sanitize($_GET['filter'] ?? '');

$query = 'is_active=eq.true&order=created_at.desc';
if ($search) {
    $query .= '&or=(full_name.ilike.*' . urlencode($search) . '*,position.ilike.*' . urlencode($search) . '*)';
}

$result = $db->select('profiles', $query);
$profiles = $result['data'] ?? [];

$allResult = $db->select('profiles', 'is_active=eq.true&select=position');
$positions = array_unique(array_column($allResult['data'] ?? [], 'position'));
sort($positions);
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-3"><i class="fas fa-id-card mr-3"></i>Direktori e-Profil</h1>
        <p class="text-blue-100 text-lg mb-8">Temui profil profesional kakitangan organisasi</p>
        <form method="GET" class="max-w-xl mx-auto flex gap-2">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Cari nama atau jawatan..."
                class="flex-1 px-4 py-3 rounded-xl text-gray-800 shadow focus:outline-none">
            <button type="submit" class="bg-white text-blue-700 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($search): ?>
            <a href="/index.php" class="bg-red-500 text-white px-4 py-3 rounded-xl hover:bg-red-600 transition">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </form>
    </div>
</section>

<div class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between flex-wrap gap-2">
        <p class="text-sm text-gray-600">
            <i class="fas fa-users text-blue-500 mr-1"></i>
            Menunjukkan <strong><?= count($profiles) ?></strong> profil
        </p>
        <div class="flex gap-2 flex-wrap">
            <a href="/index.php" class="text-xs px-3 py-1 rounded-full border <?= !$filter ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300' ?>">Semua</a>
            <?php foreach ($positions as $pos): ?>
            <a href="?filter=<?= urlencode($pos) ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="text-xs px-3 py-1 rounded-full border <?= $filter===$pos ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300' ?>">
                <?= htmlspecialchars($pos) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 py-10">
    <?php if (empty($profiles)): ?>
    <div class="text-center py-20 text-gray-400">
        <i class="fas fa-user-slash text-6xl mb-4 block"></i>
        <p class="text-xl font-semibold">Tiada profil dijumpai</p>
        <a href="/index.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg">Lihat Semua</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($profiles as $p):
            $skills = is_array($p['skills']) ? $p['skills'] : (json_decode($p['skills'], true) ?? []);
            $photoUrl = !empty($p['photo_url']) ? htmlspecialchars($p['photo_url']) : '/assets/images/default-avatar.svg';
        ?>
        <a href="/profile.php?id=<?= $p['id'] ?>" class="profile-card bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-20 relative">
                <img src="<?= $photoUrl ?>" alt="<?= htmlspecialchars($p['full_name']) ?>"
                     class="w-20 h-20 rounded-full border-4 border-white object-cover absolute -bottom-8 left-1/2 transform -translate-x-1/2 shadow-md"
                     onerror="this.src='/assets/images/default-avatar.svg'">
            </div>
            <div class="pt-10 pb-5 px-4 text-center">
                <h3 class="font-bold text-gray-800 text-lg truncate"><?= htmlspecialchars($p['full_name']) ?></h3>
                <p class="text-blue-600 text-sm font-medium truncate"><?= htmlspecialchars($p['position'] ?? '-') ?></p>
                <p class="text-gray-400 text-xs mt-1 truncate"><?= htmlspecialchars($p['department'] ?? '') ?></p>
                <?php if (!empty($skills)): ?>
                <div class="mt-3 flex flex-wrap justify-center gap-1">
                    <?php foreach (array_slice((array)$skills, 0, 3) as $skill): ?>
                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full border border-blue-100">
                        <?= htmlspecialchars($skill) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <p class="mt-3 text-xs text-blue-500 font-medium">Lihat Profil →</p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>