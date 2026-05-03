<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/admin_layout.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_password') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';
    if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
        $errors[] = 'Sila isi semua medan.';
    } elseif (strlen($newPass) < 8) {
        $errors[] = 'Kata laluan baru mesti sekurang-kurangnya 8 aksara.';
    } elseif ($newPass !== $confirmPass) {
        $errors[] = 'Kata laluan baru tidak sepadan.';
    } else {
        $db = new Supabase();
        $verify = $db->signIn(getAdminEmail(), $currentPass);
        if ($verify['status'] !== 200) {
            $errors[] = 'Kata laluan semasa tidak sah.';
        } else {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => SUPABASE_URL . '/auth/v1/user',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode(['password' => $newPass]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'apikey: ' . SUPABASE_ANON_KEY,
                    'Authorization: Bearer ' . $verify['data']['access_token'],
                ],
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($code === 200) { flashMessage('success', 'Kata laluan berjaya ditukar. Sila log masuk semula.'); logoutAdmin(); }
            else $errors[] = 'Gagal menukar kata laluan.';
        }
    }
}
?>
<script>document.getElementById('page-title').textContent='Tetapan';</script>
<div class="max-w-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tetapan Akaun</h2>
    <div class="bg-white rounded-2xl shadow p-6 mb-5">
        <h3 class="font-semibold text-gray-700 mb-3"><i class="fas fa-user text-blue-500 mr-2"></i>Maklumat Admin</h3>
        <div class="text-sm space-y-2">
            <div class="flex justify-between py-2 border-b"><span class="text-gray-500">Nama</span><span class="font-medium"><?= htmlspecialchars(getAdminName()) ?></span></div>
            <div class="flex justify-between py-2"><span class="text-gray-500">E-mel</span><span class="font-medium"><?= htmlspecialchars(getAdminEmail()) ?></span></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-lock text-blue-500 mr-2"></i>Tukar Kata Laluan</h3>
        <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-300 text-red-600 text-sm p-3 rounded-lg mb-4">
            <?php foreach ($errors as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="change_password">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan Semasa</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan Baru <span class="text-xs text-gray-400">(min 8 aksara)</span></label>
                <input type="password" name="new_password" required minlength="8" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sahkan Kata Laluan Baru</label>
                <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-semibold"><i class="fas fa-key mr-2"></i>Tukar Kata Laluan</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>