<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: ' . BASE_URL . '/index.php?page=a_kelas');
    exit;
}

$kelas = $db->queryOne("SELECT * FROM kelas WHERE id=?", 'i', [$id]);
if (!$kelas) {
    setFlash('error', 'Kelas tidak ditemukan.');
    header('Location: ' . BASE_URL . '/index.php?page=a_kelas');
    exit;
}

// Ambil siswa yang ADA di kelas ini
$siswaKelas = $db->queryAll(
    "SELECT ks.id as ks_id, ks.no_absen, u.nama, u.nis_nip 
     FROM kelas_siswa ks 
     JOIN users u ON u.id = ks.user_id 
     WHERE ks.kelas_id = ? 
     ORDER BY ks.no_absen",
    'i', [$id]
);

// Ambil siswa yang BELUM masuk ke kelas APAPUN pada TA ini
$siswaBebas = $db->queryAll(
    "SELECT id, nama, nis_nip FROM users 
     WHERE role='siswa' AND is_active=1 
     AND id NOT IN (
         SELECT user_id FROM kelas_siswa ks2 
         JOIN kelas k2 ON k2.id = ks2.kelas_id 
         WHERE k2.tahun_ajaran_id = ?
     )
     ORDER BY nama",
    'i', [$kelas['tahun_ajaran_id']]
);

$pageTitle = 'Detail Kelas ' . $kelas['nama_kelas'];
ob_start();
?>

<div class="mb-4">
    <a href="?page=a_kelas" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali ke Kelas</a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">👥 Daftar Siswa - Kelas <?= e($kelas['nama_kelas']) ?> (<?= count($siswaKelas) ?> Siswa)</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addSiswaModal')">+ Masukkan Siswa</button>
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No Absen</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswaKelas)): ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada siswa di kelas ini.</td></tr>
                <?php else: ?>
                    <?php foreach($siswaKelas as $s): ?>
                    <tr>
                        <td><?= $s['no_absen'] ?></td>
                        <td><?= e($s['nis_nip'] ?: '-') ?></td>
                        <td><strong><?= e($s['nama']) ?></strong></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=remove_siswa&ks_id=<?= $s['ks_id'] ?>&kelas_id=<?= $id ?>')"><i class='bx bx-user-minus'></i> Keluarkan</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Siswa ke Kelas -->
<div id="addSiswaModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <h3 class="mb-4">Masukkan Siswa ke Kelas</h3>
        <form action="<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=add_siswa" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="kelas_id" value="<?= $id ?>">
            
            <div class="form-group">
                <label>Pilih Siswa (Yang belum punya kelas di TA ini)</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach($siswaBebas as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= e($s['nama']) ?> (<?= e($s['nis_nip'] ?: 'Tanpa NIS') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addSiswaModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Tambahkan</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
