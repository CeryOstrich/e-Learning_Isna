<?php
Auth::requireRole('siswa');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$kelas_id = $db->queryOne("SELECT kelas_id FROM kelas_siswa WHERE user_id=? AND kelas_id IN (SELECT id FROM kelas WHERE tahun_ajaran_id=?)", 'ii', [$_SESSION['user_id'], $ta_id])['kelas_id'] ?? 0;

$presensiList = [];
if ($kelas_id) {
    // Ambil sesi presensi yang buka di kelas ini
    $presensiList = $db->queryAll(
        "SELECT p.*, m.nama_mapel, u.nama as nama_guru, ps.status_hadir, ps.waktu_absen
         FROM presensi p
         JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id
         JOIN mapel m ON m.id = jm.mapel_id
         JOIN users u ON u.id = jm.guru_id
         LEFT JOIN presensi_siswa ps ON ps.presensi_id = p.id AND ps.siswa_id = ?
         WHERE jm.kelas_id = ? AND jm.tahun_ajaran_id = ? AND (p.status = 'buka' OR ps.id IS NOT NULL)
         ORDER BY p.tanggal DESC, p.pertemuan_ke DESC",
        'iii', [$_SESSION['user_id'], $kelas_id, $ta_id]
    );
}

$pageTitle = 'Kehadiran Kelas';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">📅 Kehadiran Anda</span></div>
    
    <?php if(!$kelas_id): ?>
        <div class="alert alert-error show">Anda belum terdaftar di kelas manapun pada Tahun Ajaran ini.</div>
    <?php elseif(empty($presensiList)): ?>
        <div class="alert alert-info show">Belum ada sesi presensi dari guru Anda.</div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelas & Mapel</th>
                        <th>Pertemuan / Topik</th>
                        <th>Waktu Absen</th>
                        <th width="150">Status Anda</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($presensiList as $p): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                        <td><span class="badge badge-info"><?= e($p['nama_mapel']) ?></span><br><small><?= e($p['nama_guru']) ?></small></td>
                        <td>Ke-<?= $p['pertemuan_ke'] ?>: <?= e($p['topik']) ?></td>
                        <td><?= $p['waktu_absen'] ? date('H:i', strtotime($p['waktu_absen'])) : '-' ?></td>
                        <td>
                            <?php 
                            if ($p['status_hadir']) {
                                $warna = ['hadir'=>'success', 'izin'=>'info', 'sakit'=>'warning', 'alpa'=>'danger'];
                                echo "<span class='badge badge-{$warna[$p['status_hadir']]}'>".ucfirst($p['status_hadir'])."</span>";
                            } else {
                                echo "<span class='badge badge-secondary'>Belum Absen</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if($p['status'] === 'buka'): ?>
                                <?php if(!$p['status_hadir']): ?>
                                    <form action="<?= BASE_URL ?>/modules/siswa/presensi_submit.php" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                        <input type="hidden" name="presensi_id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-sm"><i class='bx bx-check'></i> Hadir</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-success" style="font-size:0.85rem;"><i class='bx bx-check-double'></i> Tercatat</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-danger">Ditutup</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
