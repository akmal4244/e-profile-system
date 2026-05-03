<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/admin_layout.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'full_name'  => sanitize($_POST['full_name'] ?? ''),
        'position'   => sanitize($_POST['position'] ?? ''),
        'department' => sanitize($_POST['department'] ?? ''),
        'email'      => sanitize($_POST['email'] ?? ''),
        'phone'      => sanitize($_POST['phone'] ?? ''),
        'bio'        => sanitize($_POST['bio'] ?? ''),
        'location'   => sanitize($_POST['location'] ?? ''),
        'website'    => sanitize($_POST['website'] ?? ''),
        'is_active'  => isset($_POST['is_active']) ? true : false,
    ];

    if (empty($formData['full_name'])) $errors[] = 'Nama penuh diperlukan.';

    $photoUrl = null;
    if (!empty($_FILES['photo']['name'])) {
        $uploadResult = uploadPhoto($_FILES['photo']);
        if (is_array($uploadResult) && isset($uploadResult['error'])) {
            $errors[] = $uploadResult['error'];
        } else {
            $photoUrl = $uploadResult;
        }
    }

    $portfolioLinks = [];
    $pLinks = $_POST['portfolio_label'] ?? [];
    $pUrls  = $_POST['portfolio_url'] ?? [];
    for ($i = 0; $i < count($pUrls); $i++) {
        if (!empty($pUrls[$i])) {
            $portfolioLinks[] = $pUrls[$i] . '|' . ($pLinks[$i] ?? $pUrls[$i]);
        }
    }

    if (empty($errors)) {
        $skillsArray = array_values(array_filter(array_map('trim', explode(',', $_POST['skills'] ?? ''))));
        $insertData = array_merge($formData, [
            'skills'          => json_encode($skillsArray),
            'portfolio_links' => json_encode($portfolioLinks),
            'photo_url'       => $photoUrl,
        ]);
        $db = new Supabase(true);
        $result = $db->insert('profiles', $insertData);
        if ($result['status'] === 201) {
            flashMessage('success', 'Profil berjaya ditambah!');
            header('Location: /admin/profiles.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan profil. Sila cuba lagi.';
        }
    }
}
?>
<script>document.getElementById('page-title').textContent='Tambah Profil';</script>
<div class="max-w-3xl">
<div class="flex items-center gap-3 mb-6">
    <a href="/admin/profiles.php" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h2 class="text-xl font-bold text-gray-800">Tambah Profil Baru</h2>
</div>
<?php if (!empty($errors)): ?>
<div class="bg-red-50 border border-red-300 text-red-600 text-sm p-4 rounded-xl mb-5">
    <ul class="list-disc list-inside"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6 space-y-5">
    <div class="text-center">
        <div class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 mx-auto overflow-hidden mb-2 cursor-pointer" onclick="document.getElementById('photo').click()">
            <img id="photoPreview" src="/assets/images/default-avatar.svg" class="w-full h-full object-cover">
        </div>
        <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
        <p class="text-xs text-gray-400 mt-1">Klik untuk muat naik gambar (JPG/PNG, maks 2MB)</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penuh <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jawatan</label>
            <input type="text" name="position" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="department" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">E-mel</label>
            <input type="email" name="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
            <input type="text" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="location" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Laman Web</label>
            <input type="url" name="website" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Biografi</label>
        <textarea name="bio" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kemahiran <span class="text-xs text-gray-400">(pisah dengan koma)</span></label>
        <input type="text" name="skills" placeholder="PHP, JavaScript, MySQL" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Pautan Portfolio</label>
        <div id="portfolioList" class="space-y-2">
            <div class="flex gap-2 portfolio-item">
                <input type="url" name="portfolio_url[]" placeholder="https://..." class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <input type="text" name="portfolio_label[]" placeholder="Label" class="w-36 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <button type="button" onclick="removePortfolio(this)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <button type="button" onclick="addPortfolio()" class="mt-2 text-sm text-blue-600 hover:underline"><i class="fas fa-plus"></i> Tambah pautan</button>
    </div>
    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
        <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 accent-blue-600" checked>
        <label for="is_active" class="text-sm font-medium text-gray-700">Aktifkan profil (boleh dilihat awam)</label>
    </div>
    <div class="flex gap-3 pt-2">
        <a href="/admin/profiles.php" class="flex-1 py-2.5 text-center border border-gray-300 text-gray-600 rounded-xl hover:bg-gray-50 text-sm">Batal</a>
        <button type="submit" class="flex-1 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-semibold"><i class="fas fa-save mr-2"></i>Simpan Profil</button>
    </div>
</form>
</div>
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('photoPreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
function addPortfolio() {
    const list = document.getElementById('portfolioList');
    const div = document.createElement('div');
    div.className = 'flex gap-2 portfolio-item';
    div.innerHTML = `<input type="url" name="portfolio_url[]" placeholder="https://..." class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm"><input type="text" name="portfolio_label[]" placeholder="Label" class="w-36 px-3 py-2 border border-gray-300 rounded-xl text-sm"><button type="button" onclick="removePortfolio(this)" class="text-red-400 px-2"><i class="fas fa-times"></i></button>`;
    list.appendChild(div);
}
function removePortfolio(btn) {
    if (document.querySelectorAll('.portfolio-item').length > 1) btn.closest('.portfolio-item').remove();
}
</script>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>