<?php
Auth::requireRole('siswa');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

// Cari kelas siswa
$kelas_id = $db->queryOne("SELECT kelas_id FROM kelas_siswa WHERE user_id=? AND kelas_id IN (SELECT id FROM kelas WHERE tahun_ajaran_id=?)", 'ii', [$_SESSION['user_id'], $ta_id])['kelas_id'] ?? 0;

$materiList = [];
if ($kelas_id) {
    $materiList = $db->queryAll(
        "SELECT m.*, jm.hari, mapel.nama_mapel, u.nama as nama_guru
         FROM materi m
         JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
         JOIN mapel ON mapel.id = jm.mapel_id
         JOIN users u ON u.id = jm.guru_id
         WHERE jm.kelas_id = ? AND jm.tahun_ajaran_id = ?
         ORDER BY m.created_at DESC",
        'ii', [$kelas_id, $ta_id]
    );
}

$pageTitle = 'Materi Belajar';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">📖 Daftar Materi Belajar</span></div>
    
    <?php if(!$kelas_id): ?>
        <div class="alert alert-error show">Anda belum terdaftar di kelas manapun pada Tahun Ajaran ini.</div>
    <?php elseif(empty($materiList)): ?>
        <div class="alert alert-info show">Belum ada materi yang dibagikan oleh guru.</div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
            <?php foreach($materiList as $m): ?>
            <div class="card" style="border-top:4px solid var(--primary);">
                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span class="badge badge-info"><?= e($m['nama_mapel']) ?></span>
                    <small class="text-muted"><?= date('d M Y', strtotime($m['created_at'])) ?></small>
                </div>
                <h4 style="margin-bottom:5px; font-size:1.1rem; line-height:1.4;"><?= e($m['judul']) ?></h4>
                <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:15px;"><i class='bx bx-user'></i> Guru: <?= e($m['nama_guru']) ?></p>
                
                <?php if($m['deskripsi']): ?>
                <p style="font-size:0.9rem; margin-bottom:15px; line-height:1.5; color:var(--text);"><?= nl2br(e($m['deskripsi'])) ?></p>
                <?php endif; ?>
                
                <div style="display:flex; gap:10px;">
                    <?php if($m['file_path']): ?>
                    <a href="<?= BASE_URL ?>/uploads/materi/<?= e($m['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm" style="flex:1; text-align:center;"><i class='bx bxs-file-pdf'></i> Buka Materi</a>
                    <?php endif; ?>
                    
                    <?php if($m['link_eksternal']): ?>
                    <a href="<?= e($m['link_eksternal']) ?>" target="_blank" class="btn btn-outline btn-sm" style="flex:1; text-align:center;"><i class='bx bx-link-external'></i> Buka Link</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
