<?php
/**
 * views/admin/users.php — Manajemen Pengguna (Admin)
 * Fitur: Daftar, Tambah, Edit (via modal), Nonaktifkan user
 */
Auth::requireRole('admin');

$db = Database::getInstance();

// ── Ambil semua user + filter role ───────────────────────
$filterRole = $_GET['role'] ?? '';
$search     = trim($_GET['q'] ?? '');

$sql    = "SELECT u.*, ux.total_xp, ux.level 
           FROM users u 
           LEFT JOIN user_xp ux ON ux.user_id = u.id 
           WHERE 1=1";
$types  = '';
$params = [];

if ($filterRole) {
    $sql   .= " AND u.role = ?";
    $types .= 's';
    $params[] = $filterRole;
}
if ($search) {
    $sql   .= " AND (u.nama LIKE ? OR u.nis_nip LIKE ?)";
    $types .= 'ss';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY u.role, u.nama ASC";
$users = $db->queryAll($sql, $types, $params);

$pageTitle = 'Manajemen Pengguna';
ob_start();
?>

<div class="card-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <h2 style="font-size:1.1rem;font-weight:700;">👥 Daftar Pengguna</h2>
    <button class="btn btn-primary" onclick="openModal('modal-tambah-user')">+ Tambah User</button>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
        <input type="hidden" name="page" value="a_users">
        <input type="text" name="q" class="form-control" placeholder="Cari nama atau NIS/NIP..." value="<?= e($search) ?>" style="max-width:280px;">
        <select name="role" class="form-control" style="max-width:160px;" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="admin"  <?= $filterRole==='admin'  ? 'selected':'' ?>>Admin</option>
            <option value="guru"   <?= $filterRole==='guru'   ? 'selected':'' ?>>Guru</option>
            <option value="siswa"  <?= $filterRole==='siswa'  ? 'selected':'' ?>>Siswa</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if ($search || $filterRole): ?>
        <a href="?page=a_users" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel User -->
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Nama</th><th>NIS/NIP</th>
                    <th>Role</th><th>Gamifikasi</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
            <tr><td colspan="7" class="text-center text-muted" style="padding:30px;">Tidak ada data user.</td></tr>
            <?php else: ?>
            <?php foreach ($users as $i => $u): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img src="<?= $u['foto_profil'] ? BASE_URL.'/uploads/profil/'.e($u['foto_profil']) : 'https://ui-avatars.com/api/?name='.urlencode($u['nama']).'&background=1a3a6b&color=fff&size=40' ?>"
                             style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                        <div>
                            <div style="font-weight:600;"><?= e($u['nama']) ?></div>
                            <?php if ($u['last_login']): ?>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Login: <?= date('d M Y', strtotime($u['last_login'])) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td><?= e($u['nis_nip'] ?? '—') ?></td>
                <td>
                    <span class="badge badge-<?= $u['role']==='admin' ? 'danger' : ($u['role']==='guru' ? 'success' : 'info') ?>">
                        <?= ucfirst(e($u['role'])) ?>
                    </span>
                </td>
                <td>
                    <?php if ($u['role'] === 'siswa'): ?>
                        <div style="font-size:0.85rem; font-weight:700; color:var(--primary);">⚡ Lv. <?= $u['level'] ?: 1 ?></div>
                        <div style="font-size:0.75rem; color:var(--text-muted);"><?= number_format((int)$u['total_xp']) ?> XP</div>
                    <?php else: ?>
                        <span style="color:var(--border);">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge <?= $u['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                        <?= $u['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="btn btn-sm btn-outline" onclick='editUser(<?= json_encode($u) ?>)'>✏️ Edit</button>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="<?= BASE_URL ?>/modules/admin/users_handler.php?action=toggle&id=<?= $u['id'] ?>"
                           class="btn btn-sm <?= $u['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                           data-confirm="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> user ini?">
                            <?= $u['is_active'] ? '🚫 Nonaktifkan' : '✅ Aktifkan' ?>
                        </a>
                        <a href="<?= BASE_URL ?>/modules/admin/users_handler.php?action=hapus&id=<?= $u['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('PERINGATAN: Hapus permanen user <?= htmlspecialchars($u['nama']) ?>? Seluruh data terkait (tugas, kuis, nilai, kehadiran) juga akan ikut terhapus secara permanen!')">
                            🗑️ Hapus
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah User -->
<div id="modal-tambah-user" class="modal">
    <div class="modal-content" style="max-width:500px; position:relative;">
            <h3>Tambah User Baru</h3>
            <button type="button" onclick="closeModal('modal-tambah-user')" class="modal-close-btn">&times;</button>
        <form method="POST" action="<?= BASE_URL ?>/modules/admin/users_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="tambah">
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label>NIS / NIP <small style="color:var(--text-muted);">(digunakan untuk login)</small> *</label>
                <input type="text" name="nis_nip" class="form-control" required placeholder="Contoh: 1234 atau 198001012010011001">
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 karakter">
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="role" class="form-control" required>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="hideModal('modal-tambah-user')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modal-edit-user" class="modal">
    <div class="modal-content" style="max-width:500px; position:relative;">
            <h3>Edit User</h3>
            <button type="button" onclick="closeModal('modal-edit-user')" class="modal-close-btn">&times;</button>
        <form method="POST" action="<?= BASE_URL ?>/modules/admin/users_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit-id">
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" id="edit-nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>NIS / NIP <small style="color:var(--text-muted);">(digunakan untuk login)</small> *</label>
                <input type="text" name="nis_nip" id="edit-nis_nip" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password Baru <small style="color:var(--text-muted);">(kosongkan jika tidak diganti)</small></label>
                <input type="password" name="password" class="form-control" minlength="8">
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="role" id="edit-role" class="form-control" required>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="hideModal('modal-edit-user')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-close-btn{position:absolute;top:16px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;color:var(--text-muted);line-height:1;}
</style>
<script>
function openModal(id) { showModal(id); }
function closeModal(id) { hideModal(id); }

function editUser(u) {
    document.getElementById('edit-id').value      = u.id;
    document.getElementById('edit-nama').value    = u.nama;
    document.getElementById('edit-nis_nip').value = u.nis_nip || '';
    document.getElementById('edit-role').value    = u.role;
    showModal('modal-edit-user');
}

// Tutup modal jika klik di luar konten (ditangani oleh main.js global)
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
