<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$waliList = $db->queryAll(
    "SELECT wk.*, g.nama as nama_guru, k.nama_kelas, k.tingkat 
     FROM wali_kelas wk
     JOIN users g ON g.id = wk.guru_id
     JOIN kelas k ON k.id = wk.kelas_id
     WHERE wk.tahun_ajaran_id = ?
     ORDER BY k.tingkat, k.nama_kelas",
    'i', [$ta_id]
);

$guruList = $db->queryAll("SELECT id, nama FROM users WHERE role='guru' AND is_active=1 ORDER BY nama");
$kelasList = $db->queryAll("SELECT id, nama_kelas, tingkat FROM kelas WHERE tahun_ajaran_id=? ORDER BY tingkat, nama_kelas", 'i', [$ta_id]);
$semuaTA = $db->queryAll("SELECT * FROM tahun_ajaran ORDER BY id DESC");

$pageTitle = 'Wali Kelas';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">🎓 Kelola Wali Kelas</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addWaliModal')">+ Set Wali Kelas</button>
    </div>

    <?php if (!$taAktif): ?>
        <div class="alert alert-error show mb-4">
            <i class='bx bx-error-circle'></i> Tidak ada Tahun Ajaran yang aktif.
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Nama Guru</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($waliList)): ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada data wali kelas di TA ini.</td></tr>
                <?php else: ?>
                    <?php $no=1; foreach($waliList as $w): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge badge-info">Kelas <?= e($w['tingkat']) ?></span> <strong><?= e($w['nama_kelas']) ?></strong></td>
                        <td><?= e($w['nama_guru']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick='editWali(<?= json_encode($w) ?>)'><i class='bx bx-edit'></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/wali_kelas_handler.php?action=delete&id=<?= $w['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Wali Kelas -->
<div id="addWaliModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <h3 class="mb-4" id="modalTitle">Set Wali Kelas</h3>
        <form action="<?= BASE_URL ?>/modules/admin/wali_kelas_handler.php?action=add" method="POST" id="waliForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="wali_id">
            
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="ta_id" class="form-control" required>
                    <?php foreach($semuaTA as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $ta_id ? 'selected' : '' ?>><?= e($ta['nama']) ?> <?= $ta['is_aktif'] ? '(Aktif)' : '' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pilih Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control" required>
                    <?php foreach($kelasList as $k): ?>
                    <option value="<?= $k['id'] ?>">Kelas <?= e($k['tingkat']) ?> - <?= e($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pilih Guru</label>
                <select name="guru_id" id="guru_id" class="form-control" required>
                    <?php foreach($guruList as $g): ?>
                    <option value="<?= $g['id'] ?>"><?= e($g['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addWaliModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editWali(w) {
    document.getElementById('modalTitle').textContent = 'Edit Wali Kelas';
    document.getElementById('waliForm').action = '<?= BASE_URL ?>/modules/admin/wali_kelas_handler.php?action=edit';
    document.getElementById('wali_id').value = w.id;
    document.getElementById('ta_id').value = w.tahun_ajaran_id;
    document.getElementById('kelas_id').value = w.kelas_id;
    document.getElementById('guru_id').value = w.guru_id;
    showModal('addWaliModal');
}

document.querySelector('[onclick="showModal(\'addWaliModal\')"]').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Set Wali Kelas';
    document.getElementById('waliForm').action = '<?= BASE_URL ?>/modules/admin/wali_kelas_handler.php?action=add';
    document.getElementById('wali_id').value = '';
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
