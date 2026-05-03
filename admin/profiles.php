<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/admin_layout.php';

$db = new Supabase(true);
$search       = sanitize($_GET['search'] ?? '');
$filterStatus = $_GET['status'] ?? '';

$query = 'order=created_at.desc';
if ($filterStatus === 'active')   $query .= '&is_active=eq.true';
if ($filterStatus === 'inactive') $query .= '&is_active=eq.false';
if ($search) $query .= '&or=(full_name.ilike.*' . urlencode($search) . '*,position.ilike.*' . urlencode($search) . '*)'
;
$result = $db->select('profiles', $query);
$profiles = $result['data'] ?? [];
?>
<script>document.getElementById('page-title').textContent='Semua Profil';</script>
<div class="space-y-4">
    <div class="flex flex-wrap items-center gap-3">
        <form method="GET" class="flex gap-2 flex-1 min-w-0">
            <input type="text" name="search" id="liveSearch" value="<?= htmlspecialchars($search) ?>"
                   placeholder="Cari nama atau jawatan..."
                   class="flex-1 px-4 py-2 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-xl text-sm focus:outline-none">
                <option value="">Semua Status</option>
                <option value="active" <?= $filterStatus==='active'?'selected':'' ?>>Aktif</option>
                <option value="inactive" <?= $filterStatus==='inactive'?'selected':'' ?>>Tidak Aktif</option>
            </select>
        </form>
        <a href="/admin/create.php" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition whitespace-nowrap">
            <i class="fas fa-plus mr-1"></i>Tambah Profil
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Profil</th>
                    <th class="px-4 py-3 text-left hide-mobile">Jabatan</th>
                    <th class="px-4 py-3 text-left hide-mobile">E-mel</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if (empty($profiles)): ?>
                <tr><td colspan="6" class="text-center py-10 text-gray-400"><i class="fas fa-inbox text-3xl mb-2 block"></i>Tiada profil dijumpai.</td></tr>
                <?php else: foreach ($profiles as $i => $p):
                    $photo = !empty($p['photo_url']) ? htmlspecialchars($p['photo_url']) : '/assets/images/default-avatar.svg';
                ?>
                <tr id="row-<?= $p['id'] ?>">
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= $i+1 ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?= $photo ?>" class="w-9 h-9 rounded-full object-cover flex-shrink-0" onerror="this.src='/assets/images/default-avatar.svg'">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate"><?= htmlspecialchars($p['full_name']) ?></p>
                                <p class="text-xs text-gray-400 truncate"><?= htmlspecialchars($p['position'] ?? '-') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 hide-mobile text-xs"><?= htmlspecialchars($p['department'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-gray-500 hide-mobile text-xs"><?= htmlspecialchars($p['email'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="status-badge px-2 py-0.5 rounded-full text-xs font-medium <?= $p['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $p['is_active'] ? 'Aktif' : 'Tidak Aktif' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="/admin/view.php?id=<?= $p['id'] ?>" class="p-1.5 text-gray-400 hover:text-blue-600" title="Lihat"><i class="fas fa-eye"></i></a>
                            <a href="/admin/edit.php?id=<?= $p['id'] ?>" class="p-1.5 text-gray-400 hover:text-yellow-500" title="Edit"><i class="fas fa-edit"></i></a>
                            <button onclick="toggleStatus(<?= $p['id'] ?>, <?= $p['is_active'] ? 'true' : 'false' ?>, document.getElementById('row-<?= $p['id'] ?>'))"
                                    class="p-1.5 text-gray-400 hover:text-purple-500" title="Tukar Status">
                                <i class="fas fa-toggle-<?= $p['is_active'] ? 'on' : 'off' ?>"></i>
                            </button>
                            <form method="POST" action="/api/delete_profile.php" class="inline" onsubmit="return confirmAction('Padam profil ini? Tindakan ini tidak boleh diundur.')">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500" title="Padam"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-400 text-right">Jumlah: <?= count($profiles) ?> profil ditemui</p>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>