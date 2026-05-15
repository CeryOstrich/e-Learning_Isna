<?php
Auth::requireRole('guru');
$db = Database::getInstance();

$tugas_id = $_GET['id'] ?? 0;
if (!$tugas_id) {
    header('Location: ' . BASE_URL . '/index.php?page=g_tugas');
    exit;
}

// Verifikasi kepemilikan
$tugas = $db->queryOne(
    "SELECT t.*, k.nama_kelas, m.nama_mapel 
     FROM tugas t
     JOIN jadwal_mengajar jm ON jm.id = t.jadwal_mengajar_id
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     WHERE t.id=? AND jm.guru_id=?", 
    'ii', [$tugas_id, $_SESSION['user_id']]
);

if (!$tugas) {
    setFlash('error', 'Tugas tidak ditemukan atau bukan milik Anda.');
    header('Location: ' . BASE_URL . '/index.php?page=g_tugas');
    exit;
}

// Data siswa yang mengumpulkan
$pengumpulan = $db->queryAll(
    "SELECT pt.*, u.nama, u.nis_nip
     FROM pengumpulan_tugas pt
     JOIN users u ON u.id = pt.siswa_id
     WHERE pt.tugas_id = ?
     ORDER BY pt.dikumpulkan_at DESC",
    'i', [$tugas_id]
);

$pageTitle = 'Koreksi Tugas';
ob_start();
?>

<div class="mb-4">
    <a href="?page=g_tugas" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali</a>
</div>

<div class="card mb-6" style="border-left:4px solid var(--primary);">
    <div style="display:flex; justify-content:space-between;">
        <div>
            <h3 class="mb-2"><?= e($tugas['judul']) ?></h3>
            <p class="text-muted" style="font-size:0.9rem;">
                Kelas: <strong><?= e($tugas['nama_kelas']) ?></strong> | Mapel: <strong><?= e($tugas['nama_mapel']) ?></strong><br>
                Deadline: <?= $tugas['deadline'] ? date('d M Y H:i', strtotime($tugas['deadline'])) : 'Tidak ada' ?>
            </p>
        </div>
        <div style="text-align:right;">
            <span class="badge badge-warning" style="font-size:1rem; padding:10px 15px;"><?= count($pengumpulan) ?> Terkumpul</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Daftar Pengumpulan Siswa</span></div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th width="150">Waktu Kumpul</th>
                    <th>Nama Siswa</th>
                    <th>File / Jawaban</th>
                    <th>Nilai</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($pengumpulan)): ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada siswa yang mengumpulkan tugas ini.</td></tr>
                <?php else: ?>
                    <?php foreach($pengumpulan as $p): ?>
                    <tr>
                        <td style="font-size:0.85rem; color: <?= $tugas['deadline'] && strtotime($p['dikumpulkan_at']) > strtotime($tugas['deadline']) ? 'var(--danger)' : 'var(--text)' ?>;">
                            <?= date('d M Y H:i', strtotime($p['dikumpulkan_at'])) ?>
                            <?php if($tugas['deadline'] && strtotime($p['dikumpulkan_at']) > strtotime($tugas['deadline'])): ?>
                                <br><small class="text-danger">Terlambat</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($p['nama']) ?></strong><br>
                            <small class="text-muted"><?= e($p['nis_nip'] ?: '-') ?></small>
                        </td>
                        <td>
                            <?php if($p['file_path']): ?>
                            <a href="<?= BASE_URL ?>/modules/file_server.php?type=tugas&file=<?= urlencode($p['file_path']) ?>&inline=1" target="_blank" class="btn btn-outline btn-sm"><i class='bx bx-file'></i> Lihat Jawaban</a>
                            <?php else: ?>
                            <span class="text-muted">Tidak ada file</span>
                            <?php endif; ?>
                            
                            <?php if($p['catatan']): ?>
                            <div style="margin-top:10px; font-size:0.85rem; padding:8px; background:var(--bg); border-radius:4px;">
                                <strong>Catatan Siswa:</strong> <?= nl2br(e($p['catatan'])) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($p['nilai'] !== null): ?>
                            <span class="badge badge-success" style="font-size:1rem;"><?= $p['nilai'] ?></span>
                            <?php else: ?>
                            <span class="badge badge-danger">Belum dinilai</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick='beriNilai(<?= json_encode($p) ?>)'>Beri Nilai</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Beri Nilai -->
<div id="nilaiModal" class="modal">
    <div class="modal-content" style="max-width:400px;">
        <h3 class="mb-4">Beri Nilai Tugas</h3>
        <form action="<?= BASE_URL ?>/modules/guru/tugas_handler.php?action=nilai" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="pengumpulan_id" id="pengumpulan_id">
            
            <p class="mb-4">Siswa: <strong id="nama_siswa"></strong></p>
            
            <div class="form-group">
                <label>Nilai (0 - 100)</label>
                <input type="number" name="nilai" id="nilai" class="form-control" min="0" max="100" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Feedback / Komentar (Opsional)</label>
                <textarea name="feedback_guru" id="feedback" class="form-control" rows="3"></textarea>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideModal('nilaiModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>

<script>
function beriNilai(p) {
    document.getElementById('pengumpulan_id').value = p.id;
    document.getElementById('nama_siswa').textContent = p.nama;
    document.getElementById('nilai').value = p.nilai !== null ? p.nilai : '';
    document.getElementById('feedback').value = p.feedback_guru || '';
    showModal('nilaiModal');
}
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
