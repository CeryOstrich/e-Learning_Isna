<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

// Ambil data kelas untuk TA aktif
$kelasList = $db->queryAll(
    "SELECT k.*, 
            (SELECT COUNT(*) FROM kelas_siswa WHERE kelas_id = k.id) as jml_siswa 
     FROM kelas k 
     WHERE k.tahun_ajaran_id = ? 
     ORDER BY k.tingkat, k.nama_kelas",
    'i', [$ta_id]
);

$semuaTA = $db->queryAll("SELECT * FROM tahun_ajaran ORDER BY id DESC");

$pageTitle = 'Manajemen Kelas';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">🏫 Kelola Kelas</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addKelasModal')">+ Tambah Kelas</button>
    </div>
    
    <?php if (!$taAktif): ?>
        <div class="alert alert-error show mb-4">
            <i class='bx bx-error-circle'></i> Tidak ada Tahun Ajaran yang aktif. Silakan aktifkan terlebih dahulu di menu Tahun Ajaran.
        </div>
    <?php else: ?>
        <p class="mb-4 text-muted">Menampilkan kelas untuk Tahun Ajaran aktif: <strong><?= e($taAktif['nama']) ?></strong></p>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($kelasList)): ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada data kelas untuk TA ini.</td></tr>
                <?php else: ?>
                    <?php $no=1; foreach($kelasList as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge badge-info">Kelas <?= e($k['tingkat']) ?></span></td>
                        <td><strong><?= e($k['nama_kelas']) ?></strong></td>
                        <td><?= $k['jml_siswa'] ?> Siswa</td>
                        <td>
                            <a href="?page=a_kelas_detail&id=<?= $k['id'] ?>" class="btn btn-outline btn-sm">👥 Siswa</a>
                            <button class="btn btn-warning btn-sm" onclick='editKelas(<?= json_encode($k) ?>)'><i class='bx bx-edit'></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=delete&id=<?= $k['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Kelas -->
<div id="addKelasModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <h3 class="mb-4" id="modalTitle">Tambah Kelas</h3>
        <form action="<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=add" method="POST" id="kelasForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="kelas_id">
            
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="ta_id" class="form-control" required>
                    <?php foreach($semuaTA as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $ta_id ? 'selected' : '' ?>><?= e($ta['nama']) ?> <?= $ta['is_aktif'] ? '(Aktif)' : '' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tingkat</label>
                <select name="tingkat" id="tingkat" class="form-control" required>
                    <option value="7">Kelas 7</option>
                    <option value="8">Kelas 8</option>
                    <option value="9">Kelas 9</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" placeholder="Contoh: VII-A" required>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addKelasModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<script>
function editKelas(k) {
    document.getElementById('modalTitle').textContent = 'Edit Kelas';
    document.getElementById('kelasForm').action = '<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=edit';
    document.getElementById('kelas_id').value = k.id;
    document.getElementById('ta_id').value = k.tahun_ajaran_id;
    document.getElementById('tingkat').value = k.tingkat;
    document.getElementById('nama_kelas').value = k.nama_kelas;
    showModal('addKelasModal');
}

// Reset form when opening to add
document.querySelector('[onclick="showModal(\'addKelasModal\')"]').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Kelas';
    document.getElementById('kelasForm').action = '<?= BASE_URL ?>/modules/admin/kelas_handler.php?action=add';
    document.getElementById('kelas_id').value = '';
    document.getElementById('nama_kelas').value = '';
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
