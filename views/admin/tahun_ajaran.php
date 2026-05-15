<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$taList = $db->queryAll("SELECT * FROM tahun_ajaran ORDER BY id DESC");

$pageTitle = 'Tahun Ajaran';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">📅 Kelola Tahun Ajaran</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addTAModal')">+ Tambah TA</button>
    </div>
    
    <div class="alert alert-info show mb-4">
        <i class='bx bx-info-circle'></i> Hanya satu Tahun Ajaran yang bisa aktif pada satu waktu.
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($taList)): ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada data tahun ajaran.</td></tr>
                <?php else: ?>
                    <?php $no=1; foreach($taList as $ta): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= e($ta['nama']) ?></strong></td>
                        <td>
                            <?php if($ta['is_aktif']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!$ta['is_aktif']): ?>
                            <a href="<?= BASE_URL ?>/modules/admin/tahun_ajaran_handler.php?action=set_aktif&id=<?= $ta['id'] ?>" class="btn btn-success btn-sm"><i class='bx bx-check'></i> Aktifkan</a>
                            <?php endif; ?>
                            <button class="btn btn-warning btn-sm" onclick='editTA(<?= json_encode($ta) ?>)'><i class='bx bx-edit'></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/tahun_ajaran_handler.php?action=delete&id=<?= $ta['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit TA -->
<div id="addTAModal" class="modal">
    <div class="modal-content" style="max-width:400px;">
        <h3 class="mb-4" id="modalTitle">Tambah Tahun Ajaran</h3>
        <form action="<?= BASE_URL ?>/modules/admin/tahun_ajaran_handler.php?action=add" method="POST" id="taForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="ta_id">
            
            <div class="form-group">
                <label>Nama Tahun Ajaran</label>
                <input type="text" name="nama" id="nama_ta" class="form-control" placeholder="Contoh: 2024/2025 Ganjil" required>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addTAModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editTA(ta) {
    document.getElementById('modalTitle').textContent = 'Edit Tahun Ajaran';
    document.getElementById('taForm').action = '<?= BASE_URL ?>/modules/admin/tahun_ajaran_handler.php?action=edit';
    document.getElementById('ta_id').value = ta.id;
    document.getElementById('nama_ta').value = ta.nama;
    showModal('addTAModal');
}

// Reset form
document.querySelector('[onclick="showModal(\'addTAModal\')"]').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Tahun Ajaran';
    document.getElementById('taForm').action = '<?= BASE_URL ?>/modules/admin/tahun_ajaran_handler.php?action=add';
    document.getElementById('ta_id').value = '';
    document.getElementById('nama_ta').value = '';
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
