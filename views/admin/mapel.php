<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$mapelList = $db->queryAll(
    "SELECT m.*, km.nama as nama_kelompok 
     FROM mapel m 
     JOIN kelompok_mapel km ON km.id = m.kelompok_mapel_id 
     ORDER BY km.id, m.nama_mapel"
);

$kelompokList = $db->queryAll("SELECT * FROM kelompok_mapel");

$pageTitle = 'Mata Pelajaran';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">📚 Kelola Mata Pelajaran</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addMapelModal')">+ Tambah Mapel</button>
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($mapelList)): ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada data mata pelajaran.</td></tr>
                <?php else: ?>
                    <?php $no=1; foreach($mapelList as $m): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><code><?= e($m['kode_mapel'] ?: '-') ?></code></td>
                        <td><strong><?= e($m['nama_mapel']) ?></strong></td>
                        <td><span class="badge badge-info"><?= e($m['nama_kelompok']) ?></span></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick='editMapel(<?= json_encode($m) ?>)'><i class='bx bx-edit'></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/mapel_handler.php?action=delete&id=<?= $m['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Mapel -->
<div id="addMapelModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <h3 class="mb-4" id="modalTitle">Tambah Mata Pelajaran</h3>
        <form action="<?= BASE_URL ?>/modules/admin/mapel_handler.php?action=add" method="POST" id="mapelForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="mapel_id">
            
            <div class="form-group">
                <label>Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" id="nama_mapel" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Kode Mapel (Opsional)</label>
                <input type="text" name="kode_mapel" id="kode_mapel" class="form-control">
            </div>
            
            <div class="form-group">
                <label>Kelompok Mapel</label>
                <select name="kelompok_mapel_id" id="kelompok_mapel_id" class="form-control" required>
                    <?php foreach($kelompokList as $km): ?>
                    <option value="<?= $km['id'] ?>"><?= e($km['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addMapelModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editMapel(m) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('mapelForm').action = '<?= BASE_URL ?>/modules/admin/mapel_handler.php?action=edit';
    document.getElementById('mapel_id').value = m.id;
    document.getElementById('nama_mapel').value = m.nama_mapel;
    document.getElementById('kode_mapel').value = m.kode_mapel;
    document.getElementById('kelompok_mapel_id').value = m.kelompok_mapel_id;
    showModal('addMapelModal');
}

// Reset form
document.querySelector('[onclick="showModal(\'addMapelModal\')"]').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('mapelForm').action = '<?= BASE_URL ?>/modules/admin/mapel_handler.php?action=add';
    document.getElementById('mapel_id').value = '';
    document.getElementById('nama_mapel').value = '';
    document.getElementById('kode_mapel').value = '';
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
