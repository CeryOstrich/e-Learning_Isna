<?php
Auth::requireRole('siswa');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$kelas_id = $db->queryOne("SELECT kelas_id FROM kelas_siswa WHERE user_id=? AND kelas_id IN (SELECT id FROM kelas WHERE tahun_ajaran_id=?)", 'ii', [$_SESSION['user_id'], $ta_id])['kelas_id'] ?? 0;

$tugasList = [];
if ($kelas_id) {
    $tugasList = $db->queryAll(
        "SELECT t.*, mapel.nama_mapel, u.nama as nama_guru,
                pt.id as pt_id, pt.file_path as pt_file, pt.catatan as pt_catatan, pt.nilai as pt_nilai, pt.feedback_guru, pt.dikumpulkan_at
         FROM tugas t
         JOIN jadwal_mengajar jm ON jm.id = t.jadwal_mengajar_id
         JOIN mapel ON mapel.id = jm.mapel_id
         JOIN users u ON u.id = jm.guru_id
         LEFT JOIN pengumpulan_tugas pt ON pt.tugas_id = t.id AND pt.siswa_id = ?
         WHERE jm.kelas_id = ? AND jm.tahun_ajaran_id = ?
         ORDER BY t.created_at DESC",
        'iii', [$_SESSION['user_id'], $kelas_id, $ta_id]
    );
}

$pageTitle = 'Tugas Kelas';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">📝 Tugas Kelas</span></div>
    
    <?php if(!$kelas_id): ?>
        <div class="alert alert-error show">Anda belum terdaftar di kelas manapun pada Tahun Ajaran ini.</div>
    <?php elseif(empty($tugasList)): ?>
        <div class="alert alert-info show">Belum ada tugas dari guru.</div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:20px;">
            <?php foreach($tugasList as $t): ?>
            <div class="card" style="border-left: 4px solid <?= $t['pt_id'] ? 'var(--success)' : 'var(--warning)' ?>;">
                <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h4 style="margin-bottom:8px;"><?= e($t['judul']) ?></h4>
                        <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:10px;">
                            Mapel: <strong><?= e($t['nama_mapel']) ?></strong> | Guru: <?= e($t['nama_guru']) ?><br>
                            Deadline: 
                            <strong style="color: <?= $t['deadline'] && strtotime($t['deadline']) < time() && !$t['pt_id'] ? 'var(--danger)' : 'var(--text)' ?>;">
                                <?= $t['deadline'] ? date('d M Y H:i', strtotime($t['deadline'])) : 'Tanpa Batas Waktu' ?>
                            </strong>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <?php if($t['pt_nilai'] !== null): ?>
                            <span class="badge badge-success mb-2" style="font-size:1rem; padding:8px 12px;">Nilai: <?= $t['pt_nilai'] ?></span><br>
                        <?php elseif($t['pt_id']): ?>
                            <span class="badge badge-info mb-2">Telah Dikumpulkan (Menunggu Nilai)</span><br>
                        <?php else: ?>
                            <span class="badge badge-warning mb-2">Belum Mengumpulkan</span><br>
                        <?php endif; ?>
                    </div>
                </div>
                
                <p style="font-size:0.95rem; line-height:1.5; margin-bottom:15px;"><?= nl2br(e($t['deskripsi'])) ?></p>
                
                <?php if($t['file_path']): ?>
                <div class="mb-4">
                    <a href="<?= BASE_URL ?>/modules/file_server.php?type=tugas&file=<?= urlencode($t['file_path']) ?>&inline=1" target="_blank" class="btn btn-outline btn-sm"><i class='bx bx-file'></i> Lihat File Tugas</a>
                </div>
                <?php endif; ?>
                
                <hr style="border:0; border-top:1px solid var(--border); margin:15px 0;">
                
                <!-- Area Pengumpulan -->
                <div style="background:var(--bg); padding:15px; border-radius:8px;">
                    <h5 class="mb-3"><?= $t['pt_id'] ? 'Ubah Pengumpulan' : 'Kumpulkan Jawaban' ?></h5>
                    
                    <?php if($t['pt_id'] && $t['pt_file']): ?>
                        <div class="mb-3 text-success" style="font-size:0.85rem;"><i class='bx bx-check-circle'></i> File terkirim: <?= e(basename($t['pt_file'])) ?></div>
                    <?php endif; ?>
                    
                    <?php if($t['feedback_guru']): ?>
                        <div class="alert alert-info show mb-3" style="padding:10px; font-size:0.85rem;">
                            <strong>Komentar Guru:</strong><br><?= nl2br(e($t['feedback_guru'])) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/modules/siswa/tugas_submit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        <input type="hidden" name="tugas_id" value="<?= $t['id'] ?>">
                        
                        <div class="form-group mb-2">
                            <label>File Jawaban (Max 10MB)</label>
                            <input type="file" name="file_jawaban" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mb-3">
                            <label>Catatan / Teks Jawaban</label>
                            <textarea name="catatan" class="form-control" rows="2"><?= e($t['pt_catatan'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class='bx bx-send'></i> <?= $t['pt_id'] ? 'Update Jawaban' : 'Kirim Jawaban' ?></button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
