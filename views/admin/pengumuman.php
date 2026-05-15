<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$pengumuman = $db->queryAll("SELECT p.*, u.nama as pembuat FROM pengumuman p JOIN users u ON u.id = p.created_by ORDER BY p.created_at DESC");

$pageTitle = 'Pengumuman';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">📢 Broadcast Pengumuman</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addPengumumanModal')">+ Buat Pengumuman</button>
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th width="200">Tanggal</th>
                    <th>Judul & Isi</th>
                    <th>Target</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($pengumuman)): ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada pengumuman.</td></tr>
                <?php else: ?>
                    <?php foreach($pengumuman as $p): ?>
                    <tr>
                        <td style="font-size:0.85rem; color:var(--text-muted);"><?= date('d M Y H:i', strtotime($p['created_at'])) ?><br>Oleh: <?= e($p['pembuat']) ?></td>
                        <td>
                            <strong><?= e($p['judul']) ?></strong>
                            <p style="font-size:0.85rem; color:var(--text-muted); margin-top:5px;"><?= nl2br(e(substr($p['isi'], 0, 100))) ?><?= strlen($p['isi']) > 100 ? '...' : '' ?></p>
                        </td>
                        <td>
                            <?php
                            $badge = ['semua' => 'info', 'guru' => 'success', 'siswa' => 'warning'];
                            ?>
                            <span class="badge badge-<?= $badge[$p['target_role']] ?? 'secondary' ?>"><?= ucfirst($p['target_role']) ?></span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick='editPengumuman(<?= json_encode($p) ?>)'><i class='bx bx-edit'></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/admin/pengumuman_handler.php?action=delete&id=<?= $p['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Pengumuman -->
<div id="addPengumumanModal" class="modal">
    <div class="modal-content" style="max-width:600px;">
        <h3 class="mb-4" id="modalTitle">Buat Pengumuman</h3>
        <form action="<?= BASE_URL ?>/modules/admin/pengumuman_handler.php?action=add" method="POST" id="pengumumanForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="pengumuman_id">
            
            <div class="form-group">
                <label>Judul Pengumuman</label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Isi Pengumuman</label>
                <textarea name="isi" id="isi" class="form-control" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Target Audience</label>
                <select name="target_role" id="target_role" class="form-control" required>
                    <option value="semua">Semua (Guru & Siswa)</option>
                    <option value="guru">Hanya Guru</option>
                    <option value="siswa">Hanya Siswa</option>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addPengumumanModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Siarkan Pengumuman</button>
            </div>
        </form>
    </div>
</div>

<script>
function editPengumuman(p) {
    document.getElementById('modalTitle').textContent = 'Edit Pengumuman';
    document.getElementById('pengumumanForm').action = '<?= BASE_URL ?>/modules/admin/pengumuman_handler.php?action=edit';
    document.getElementById('pengumuman_id').value = p.id;
    document.getElementById('judul').value = p.judul;
    document.getElementById('isi').value = p.isi;
    document.getElementById('target_role').value = p.target_role;
    showModal('addPengumumanModal');
}

document.querySelector('[onclick="showModal(\'addPengumumanModal\')"]').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Buat Pengumuman';
    document.getElementById('pengumumanForm').action = '<?= BASE_URL ?>/modules/admin/pengumuman_handler.php?action=add';
    document.getElementById('pengumuman_id').value = '';
    document.getElementById('judul').value = '';
    document.getElementById('isi').value = '';
    document.getElementById('target_role').value = 'semua';
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
