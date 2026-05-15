<?php
Auth::requireRole('admin');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$kelas_id = $_GET['kelas_id'] ?? 0;
$kelasList = $db->queryAll("SELECT id, nama_kelas, tingkat FROM kelas WHERE tahun_ajaran_id=? ORDER BY tingkat, nama_kelas", 'i', [$ta_id]);

$laporan = [];
if ($kelas_id) {
    // Ambil rata-rata nilai akhir per siswa di kelas tersebut
    $laporan = $db->queryAll(
        "SELECT u.nama, u.nis_nip, ks.no_absen,
                (SELECT COUNT(kh.id) 
                 FROM kuis_hasil kh 
                 JOIN modul_item mi ON mi.id = kh.item_id
                 JOIN modul m ON m.id = mi.modul_id
                 JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
                 WHERE kh.siswa_id = u.id AND jm.kelas_id = ks.kelas_id AND jm.tahun_ajaran_id = ?) as jml_kuis,
                 
                (SELECT AVG(kh.skor) 
                 FROM kuis_hasil kh 
                 JOIN modul_item mi ON mi.id = kh.item_id
                 JOIN modul m ON m.id = mi.modul_id
                 JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
                 WHERE kh.siswa_id = u.id AND jm.kelas_id = ks.kelas_id AND jm.tahun_ajaran_id = ?) as rata_rata
                 
         FROM kelas_siswa ks
         JOIN users u ON u.id = ks.user_id
         WHERE ks.kelas_id = ?
         ORDER BY ks.no_absen",
        'iii', [$ta_id, $ta_id, $kelas_id]
    );
}

$pageTitle = 'Laporan Akademik';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">📊 Laporan Nilai Kelas</span>
    </div>
    
    <form action="" method="GET" class="mb-4" style="display:flex; gap:10px; max-width:500px;">
        <input type="hidden" name="page" value="a_laporan">
        <select name="kelas_id" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($kelasList as $k): ?>
            <option value="<?= $k['id'] ?>" <?= $kelas_id == $k['id'] ? 'selected' : '' ?>>Kelas <?= e($k['tingkat']) ?> - <?= e($k['nama_kelas']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    <?php if($kelas_id): ?>
        <?php if(empty($laporan)): ?>
        <div class="alert alert-info show">Belum ada siswa di kelas ini atau nilai belum diinput.</div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No Absen</th>
                        <th>NIS/NIP</th>
                        <th>Nama Siswa</th>
                        <th>Jml Kuis Selesai</th>
                        <th>Rata-rata Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($laporan as $l): ?>
                    <tr>
                        <td><?= $l['no_absen'] ?></td>
                        <td><?= e($l['nis_nip'] ?: '-') ?></td>
                        <td><strong><?= e($l['nama']) ?></strong></td>
                        <td><?= $l['jml_kuis'] ?> Kuis</td>
                        <td>
                            <?php if($l['rata_rata']): ?>
                                <span class="badge badge-<?= $l['rata_rata'] >= 75 ? 'success' : 'danger' ?>"><?= number_format($l['rata_rata'], 2) ?></span>
                            <?php else: ?>
                                <span class="text-muted">Belum ada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="text-muted mt-4" style="font-size:0.85rem;"><i class='bx bx-info-circle'></i> Fitur ini menampilkan rata-rata nilai akhir per siswa di kelas yang dipilih. Untuk melihat rincian per mapel, silakan login sebagai Wali Kelas.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
