<?php
Auth::requireRole('guru');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$jadwalList = $db->queryAll(
    "SELECT jm.*, k.nama_kelas, k.tingkat, m.nama_mapel 
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?",
    'ii', [$_SESSION['user_id'], $ta_id]
);

$tugasList = $db->queryAll(
    "SELECT t.*, k.nama_kelas, m.nama_mapel,
            (SELECT COUNT(*) FROM pengumpulan_tugas pt WHERE pt.tugas_id = t.id) as jml_terkumpul
     FROM tugas t
     JOIN jadwal_mengajar jm ON jm.id = t.jadwal_mengajar_id
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?
     ORDER BY t.created_at DESC",
    'ii', [$_SESSION['user_id'], $ta_id]
);

$pageTitle = 'Kelola Tugas';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">📝 Tugas Kelas</span>
        <button class="btn btn-primary btn-sm" onclick="showModal('addTugasModal')">+ Buat Tugas Baru</button>
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th width="150">Dibuat</th>
                    <th>Judul Tugas</th>
                    <th>Kelas & Mapel</th>
                    <th>Deadline</th>
                    <th>Terkumpul</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tugasList)): ?>
                <tr><td colspan="6" class="text-center text-muted">Belum ada tugas yang dibuat.</td></tr>
                <?php else: ?>
                    <?php foreach($tugasList as $t): ?>
                    <tr>
                        <td style="font-size:0.85rem; color:var(--text-muted);"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></td>
                        <td>
                            <strong><?= e($t['judul']) ?></strong>
                            <?php if($t['file_path']): ?>
                            <div><a href="<?= BASE_URL ?>/modules/file_server.php?type=tugas&file=<?= urlencode($t['file_path']) ?>&inline=1" target="_blank" class="badge badge-info"><i class='bx bx-paperclip'></i> Ada Lampiran</a></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-primary"><?= e($t['nama_kelas']) ?></span><br>
                            <small><?= e($t['nama_mapel']) ?></small>
                        </td>
                        <td>
                            <?php if($t['deadline']): ?>
                                <span style="color: <?= strtotime($t['deadline']) < time() ? 'var(--danger)' : 'var(--text)' ?>">
                                    <?= date('d M Y H:i', strtotime($t['deadline'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Tanpa batas waktu</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge badge-warning"><?= $t['jml_terkumpul'] ?> Siswa</span></td>
                        <td>
                            <a href="?page=g_tugas_koreksi&id=<?= $t['id'] ?>" class="btn btn-success btn-sm"><i class='bx bx-check-double'></i> Koreksi</a>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/guru/tugas_handler.php?action=delete&id=<?= $t['id'] ?>')"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Tugas -->
<div id="addTugasModal" class="modal">
    <div class="modal-content" style="max-width:600px;">
        <h3 class="mb-4">Buat Tugas Baru</h3>
        <form action="<?= BASE_URL ?>/modules/guru/tugas_handler.php?action=add" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            
            <div class="form-group">
                <label>Pilih Kelas & Mapel</label>
                <select name="jadwal_mengajar_id" class="form-control" required>
                    <?php foreach($jadwalList as $j): ?>
                    <option value="<?= $j['id'] ?>"><?= e($j['nama_kelas']) ?> - <?= e($j['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Judul Tugas</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi / Instruksi</label>
                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label>Batas Pengumpulan (Deadline) - Opsional</label>
                <input type="datetime-local" name="deadline" class="form-control">
            </div>
            
            <div class="form-group">
                <label>File Lampiran (Opsional, max 10MB)</label>
                <input type="file" name="file_tugas" class="form-control">
                <small class="text-muted">Format: PDF, Word, Excel, Gambar</small>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addTugasModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
