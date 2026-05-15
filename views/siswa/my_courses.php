<?php
Auth::requireRole('siswa');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$kelas_id = $db->queryOne("SELECT kelas_id FROM kelas_siswa WHERE user_id=? AND kelas_id IN (SELECT id FROM kelas WHERE tahun_ajaran_id=?)", 'ii', [$_SESSION['user_id'], $ta_id])['kelas_id'] ?? 0;

$courseList = [];
if ($kelas_id) {
    // Ambil daftar course (jadwal_mengajar)
    $courseList = $db->queryAll(
        "SELECT jm.*, m.nama_mapel, u.nama as nama_guru,
                (SELECT COUNT(mi.id) FROM modul_item mi JOIN modul md ON md.id=mi.modul_id WHERE md.jadwal_mengajar_id=jm.id) as total_item,
                (SELECT COUNT(pm.id) FROM progress_materi pm JOIN modul_item mi ON mi.id=pm.item_id JOIN modul md ON md.id=mi.modul_id WHERE md.jadwal_mengajar_id=jm.id AND pm.siswa_id=?) as selesai_materi,
                (SELECT COUNT(kh.id) FROM kuis_hasil kh JOIN modul_item mi ON mi.id=kh.item_id JOIN modul md ON md.id=mi.modul_id WHERE md.jadwal_mengajar_id=jm.id AND kh.siswa_id=?) as selesai_kuis
         FROM jadwal_mengajar jm
         JOIN mapel m ON m.id = jm.mapel_id
         JOIN users u ON u.id = jm.guru_id
         WHERE jm.kelas_id = ? AND jm.tahun_ajaran_id = ?",
        'iiii', [$_SESSION['user_id'], $_SESSION['user_id'], $kelas_id, $ta_id]
    );
}

$pageTitle = 'Kelas Saya';
ob_start();
?>

<div class="mb-4">
    <h2>E-Learning: Mata Pelajaran</h2>
    <p class="text-muted">Pilih mata pelajaran untuk mulai belajar secara mandiri.</p>
</div>

<?php if(!$kelas_id): ?>
    <div class="alert alert-error show">Anda belum terdaftar di kelas manapun pada Tahun Ajaran ini.</div>
<?php elseif(empty($courseList)): ?>
    <div class="alert alert-info show">Belum ada mata pelajaran untuk kelas Anda.</div>
<?php else: ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
        <?php foreach($courseList as $c): ?>
            <?php 
            $total_selesai = $c['selesai_materi'] + $c['selesai_kuis'];
            $progress_persen = $c['total_item'] > 0 ? round(($total_selesai / $c['total_item']) * 100) : 0;
            ?>
            <div class="card" style="border-top:4px solid var(--primary); display:flex; flex-direction:column;">
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span class="badge badge-info">Mapel</span>
                    </div>
                    <h4 style="margin-bottom:5px; font-size:1.2rem;"><?= e($c['nama_mapel']) ?></h4>
                    <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px;"><i class='bx bx-user'></i> Guru: <?= e($c['nama_guru']) ?></p>
                    
                    <div style="margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; font-size:0.8rem; margin-bottom:5px;">
                            <span>Progress Belajar</span>
                            <strong><?= $progress_persen ?>%</strong>
                        </div>
                        <div style="width:100%; height:8px; background:var(--border); border-radius:4px; overflow:hidden;">
                            <div style="height:100%; width:<?= $progress_persen ?>%; background:var(--success); transition: width 0.3s ease;"></div>
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:5px; text-align:right;">
                            <?= $total_selesai ?> / <?= $c['total_item'] ?> Modul Selesai
                        </div>
                    </div>
                </div>
                
                <a href="?page=s_belajar&jm_id=<?= $c['id'] ?>" class="btn btn-primary" style="text-align:center; display:block; width:100%;">
                    <?= $progress_persen > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
