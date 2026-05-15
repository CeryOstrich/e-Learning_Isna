<?php
Auth::requireRole('siswa');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$kelas_id = $db->queryOne("SELECT kelas_id FROM kelas_siswa WHERE user_id=? AND kelas_id IN (SELECT id FROM kelas WHERE tahun_ajaran_id=?)", 'ii', [$_SESSION['user_id'], $ta_id])['kelas_id'] ?? 0;

$ujianList = [];
if ($kelas_id) {
    // Ambil semua ujian yang aktif atau yang siswa sudah kerjakan
    $ujianList = $db->queryAll(
        "SELECT u.*, m.nama_mapel, g.nama as nama_guru,
                su.status as status_siswa, su.skor_total, su.id as sesi_id
         FROM ujian u
         JOIN jadwal_mengajar jm ON jm.id = u.jadwal_mengajar_id
         JOIN mapel m ON m.id = jm.mapel_id
         JOIN users g ON g.id = jm.guru_id
         LEFT JOIN sesi_ujian su ON su.ujian_id = u.id AND su.siswa_id = ?
         WHERE jm.kelas_id = ? AND jm.tahun_ajaran_id = ? 
         AND (u.status = 'aktif' OR su.id IS NOT NULL)
         ORDER BY u.created_at DESC",
        'iii', [$_SESSION['user_id'], $kelas_id, $ta_id]
    );
}

$pageTitle = 'Daftar Ujian CBT';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">🖥️ Ujian & Kuis (CBT)</span></div>
    
    <?php if(!$kelas_id): ?>
        <div class="alert alert-error show">Anda belum terdaftar di kelas manapun pada Tahun Ajaran ini.</div>
    <?php elseif(empty($ujianList)): ?>
        <div class="alert alert-info show">Belum ada jadwal ujian atau kuis saat ini.</div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Judul Ujian</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Durasi</th>
                        <th>Status Anda</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ujianList as $u): ?>
                    <tr>
                        <td><span class="badge badge-info"><?= e($u['nama_mapel']) ?></span><br><small><?= e($u['nama_guru']) ?></small></td>
                        <td><strong><?= e($u['judul']) ?></strong><br><small><?= ucfirst(str_replace('_', ' ', $u['tipe'])) ?></small></td>
                        <td style="font-size:0.85rem;">
                            <?= $u['waktu_mulai'] ? date('d M Y H:i', strtotime($u['waktu_mulai'])) : 'Kapan saja' ?> <br> s/d <br> 
                            <?= $u['waktu_selesai'] ? date('d M Y H:i', strtotime($u['waktu_selesai'])) : 'Tidak ditentukan' ?>
                        </td>
                        <td><?= $u['durasi_menit'] ?> Menit</td>
                        <td>
                            <?php if($u['status_siswa'] === 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                                <?php if($u['tampil_hasil']): ?>
                                    <div class="mt-1">Skor: <strong><?= $u['skor_total'] ?></strong></div>
                                <?php endif; ?>
                            <?php elseif($u['status_siswa'] === 'berlangsung'): ?>
                                <span class="badge badge-warning">Sedang Dikerjakan</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Belum Dikerjakan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $sekarang = time();
                            $bisaMulai = true;
                            if ($u['waktu_mulai'] && strtotime($u['waktu_mulai']) > $sekarang) $bisaMulai = false;
                            if ($u['waktu_selesai'] && strtotime($u['waktu_selesai']) < $sekarang) $bisaMulai = false;
                            
                            if ($u['status_siswa'] === 'selesai'): 
                            ?>
                                <button class="btn btn-outline btn-sm" disabled>Selesai</button>
                            <?php elseif ($u['status_siswa'] === 'berlangsung'): ?>
                                <a href="?page=s_ujian_kerjakan&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Lanjutkan</a>
                            <?php elseif ($u['status'] === 'aktif' && $bisaMulai): ?>
                                <a href="?page=s_ujian_kerjakan&id=<?= $u['id'] ?>" class="btn btn-primary btn-sm">Mulai Ujian</a>
                            <?php else: ?>
                                <button class="btn btn-outline btn-sm" disabled><?= $u['status'] === 'selesai' || !$bisaMulai ? 'Ditutup' : 'Belum Mulai' ?></button>
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
