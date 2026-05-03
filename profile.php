<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/supabase.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: /index.php'); exit; }

$db = new Supabase();
$result = $db->select('profiles', 'id=eq.' . $id . '&is_active=eq.true');
$p = $result['data'][0] ?? null;

if (!$p) {
    http_response_code(404);
    include __DIR__ . '/includes/header.php';
    echo '<div class="max-w-xl mx-auto py-20 text-center text-gray-400"><i class="fas fa-user-slash text-6xl mb-4 block"></i><p class="text-xl">Profil tidak dijumpai.</p><a href="/index.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg">Kembali</a></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$skills = is_array($p['skills']) ? $p['skills'] : (json_decode($p['skills'] ?? '[]', true) ?? []);
$portfolios = is_array($p['portfolio_links']) ? $p['portfolio_links'] : (json_decode($p['portfolio_links'] ?? '[]', true) ?? []);
$photoUrl = !empty($p['photo_url']) ? htmlspecialchars($p['photo_url']) : '/assets/images/default-avatar.svg';
include __DIR__ . '/includes/header.php';
?>
<div class="max-w-3xl mx-auto px-4 py-10">
    <a href="/index.php" class="text-sm text-blue-600 hover:underline mb-6 inline-flex items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali ke Direktori
    </a>
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="hero-gradient h-40 relative">
            <img src="<?= $photoUrl ?>" alt="<?= htmlspecialchars($p['full_name']) ?>"
                 class="w-28 h-28 rounded-full border-4 border-white object-cover absolute -bottom-12 left-8 shadow-xl"
                 onerror="this.src='/assets/images/default-avatar.svg'">
        </div>
        <div class="pt-16 pb-8 px-8">
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($p['full_name']) ?></h1>
            <p class="text-blue-600 font-medium text-lg"><?= htmlspecialchars($p['position'] ?? '') ?></p>
            <p class="text-gray-400 text-sm"><?= htmlspecialchars($p['department'] ?? '') ?></p>

            <?php if (!empty($p['bio'])): ?>
            <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                <h3 class="font-semibold text-gray-700 mb-2 text-sm"><i class="fas fa-user mr-2 text-blue-500"></i>Tentang Saya</h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($p['bio'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <?php
                $contacts = [
                    ['fas fa-envelope','E-mel',$p['email'] ?? '','mailto:'],
                    ['fas fa-phone','Telefon',$p['phone'] ?? '','tel:'],
                    ['fas fa-map-marker-alt','Lokasi',$p['location'] ?? '',''],
                    ['fas fa-globe','Laman Web',$p['website'] ?? '',''],
                ];
                foreach ($contacts as [$icon, $label, $value, $scheme]):
                    if (!$value) continue;
                ?>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <i class="<?= $icon ?> text-blue-500 w-4"></i>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400"><?= $label ?></p>
                        <?php if ($scheme): ?>
                        <a href="<?= $scheme . htmlspecialchars($value) ?>" class="text-blue-600 hover:underline truncate block"><?= htmlspecialchars($value) ?></a>
                        <?php else: ?>
                        <p class="text-gray-700 truncate"><?= htmlspecialchars($value) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($skills)): ?>
            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm"><i class="fas fa-star mr-2 text-yellow-500"></i>Kemahiran</h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($skills as $s): ?>
                    <span class="skill-badge bg-blue-100 text-blue-700 text-xs px-3 py-1.5 rounded-full border border-blue-200"><?= htmlspecialchars($s) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($portfolios)): ?>
            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm"><i class="fas fa-briefcase mr-2 text-purple-500"></i>Portfolio</h3>
                <div class="space-y-2">
                    <?php foreach ($portfolios as $link):
                        $parts = explode('|', $link, 2);
                        $url = $parts[0]; $label = $parts[1] ?? $url;
                    ?>
                    <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition">
                        <i class="fas fa-link text-blue-400 text-xs"></i>
                        <span class="text-sm text-blue-600 truncate"><?= htmlspecialchars($label) ?></span>
                        <i class="fas fa-external-link-alt text-gray-300 text-xs ml-auto"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>