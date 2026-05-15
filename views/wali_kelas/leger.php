<?php
/**
 * views/wali_kelas/leger.php
 * Matriks Leger Nilai 2D: Baris=Siswa, Kolom=Mata Pelajaran
 * Hanya bisa diakses oleh Guru yang terdaftar sebagai Wali Kelas TA aktif.
 */
Auth::requireRole('guru');
$waliData = Auth::getWaliKelas();
if (!$waliData) {
    http_response_code(403);
    include ROOT_PATH . '/views/errors/403.php';
    exit;
}

$db       = Database::getInstance();
$kelas_id = $waliData['kelas_id'];
$ta_id    = $waliData['tahun_ajaran_id'];

// Ambil semua siswa di kelas ini (urut no absen)
$siswas = $db->queryAll(
    "SELECT ks.no_absen, u.id, u.nama, u.nis_nip
     FROM kelas_siswa ks JOIN users u ON u.id=ks.user_id
     WHERE ks.kelas_id=? ORDER BY ks.no_absen, u.nama",
    'i', [$kelas_id]
);

// Ambil semua mapel yang diajar di kelas ini pada TA ini
$mapels = $db->queryAll(
    "SELECT DISTINCT m.id, m.nama_mapel, m.kode_mapel, u.nama AS nama_guru
     FROM jadwal_mengajar jm
     JOIN mapel m ON m.id=jm.mapel_id
     JOIN users u ON u.id=jm.guru_id
     WHERE jm.kelas_id=? AND jm.tahun_ajaran_id=?
     ORDER BY m.nama_mapel",
    'ii', [$kelas_id, $ta_id]
);

// Ambil semua nilai akhir untuk kelas + TA ini (indexed: siswa_id → mapel_id)
$nilaiRows = $db->queryAll(
    "SELECT siswa_id, mapel_id, nilai_akhir, nilai_harian, nilai_uts, nilai_uas, predikat
     FROM nilai_akhir WHERE kelas_id=? AND tahun_ajaran_id=?",
    'ii', [$kelas_id, $ta_id]
);

$nilaiMap = [];
foreach ($nilaiRows as $n) {
    $nilaiMap[$n['siswa_id']][$n['mapel_id']] = $n;
}

$pageTitle = 'Leger Nilai — ' . $waliData['nama_kelas'];
ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:1.1rem;font-weight:700;">📊 Leger Nilai Kelas <?= e($waliData['nama_kelas']) ?></h2>
        <p style="font-size:0.82rem;color:var(--text-muted);">TA: <?= e($waliData['nama_ta']) ?> | <?= count($siswas) ?> Siswa | <?= count($mapels) ?> Mata Pelajaran</p>
    </div>
    <a href="?page=wk_rapor" class="btn btn-primary">📄 Export Rapor PDF</a>
</div>

<!-- Keterangan predikat -->
<div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap;font-size:0.78rem;">
    <span>Predikat: </span>
    <span class="badge badge-success">A ≥ 90</span>
    <span class="badge badge-info">B ≥ 75</span>
    <span class="badge badge-warning">C ≥ 60</span>
    <span class="badge badge-danger">D &lt; 60</span>
</div>

<div class="card" style="padding:0;">
    <div style="overflow-x:auto;">
        <table style="min-width:max-content;">
            <thead>
                <tr style="background:var(--primary);color:white;">
                    <th style="padding:12px 14px;white-space:nowrap;position:sticky;left:0;background:var(--primary);z-index:10;">No</th>
                    <th style="padding:12px 14px;white-space:nowrap;position:sticky;left:40px;background:var(--primary);z-index:10;min-width:180px;">Nama Siswa</th>
                    <th style="padding:12px 14px;white-space:nowrap;position:sticky;left:220px;background:var(--primary);z-index:10;">NIS</th>
                    <?php foreach ($mapels as $mp): ?>
                    <th style="padding:10px 8px;text-align:center;white-space:nowrap;font-size:0.78rem;">
                        <?= e($mp['kode_mapel']) ?><br>
                        <span style="font-weight:400;opacity:0.8;font-size:0.7rem;"><?= e($mp['nama_mapel']) ?></span>
                    </th>
                    <?php endforeach; ?>
                    <th style="padding:10px 12px;text-align:center;background:#1a3a8b;">Rata-rata</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $noUrut = 1;
            foreach ($siswas as $siswa):
                $totalNilai = 0;
                $hitungMapel = 0;
                foreach ($mapels as $mp) {
                    $n = $nilaiMap[$siswa['id']][$mp['id']] ?? null;
                    if ($n && $n['nilai_akhir'] !== null) {
                        $totalNilai += $n['nilai_akhir'];
                        $hitungMapel++;
                    }
                }
                $rataRata = $hitungMapel ? round($totalNilai / $hitungMapel, 1) : null;
            ?>
            <tr class="<?= $noUrut % 2 === 0 ? 'even-row' : '' ?>">
                <td style="text-align:center;color:var(--text-muted);position:sticky;left:0;background:var(--surface);z-index:5;"><?= $siswa['no_absen'] ?: $noUrut ?></td>
                <td style="font-weight:600;white-space:nowrap;position:sticky;left:40px;background:var(--surface);z-index:5;"><?= e($siswa['nama']) ?></td>
                <td style="font-size:0.82rem;color:var(--text-muted);position:sticky;left:220px;background:var(--surface);z-index:5;"><?= e($siswa['nis_nip'] ?? '—') ?></td>

                <?php foreach ($mapels as $mp):
                    $nilai = $nilaiMap[$siswa['id']][$mp['id']] ?? null;
                    $na    = $nilai ? $nilai['nilai_akhir'] : null;
                    $pred  = $nilai ? $nilai['predikat']   : null;
                    $color = match($pred) {
                        'A' => 'var(--success)',
                        'B' => 'var(--info)',
                        'C' => 'var(--warning)',
                        'D' => 'var(--danger)',
                        default => 'var(--text-muted)'
                    };
                ?>
                <td style="text-align:center;padding:10px 8px;">
                    <?php if ($na !== null): ?>
                    <div style="font-weight:700;color:<?= $color ?>;"><?= number_format($na, 1) ?></div>
                    <?php if ($pred): ?><div style="font-size:0.7rem;color:<?= $color ?>;"><?= $pred ?></div><?php endif; ?>
                    <?php else: ?>
                    <span style="color:var(--text-light);font-size:0.8rem;">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>

                <td style="text-align:center;font-weight:800;font-size:1.05rem;color:var(--primary);">
                    <?= $rataRata !== null ? number_format($rataRata, 1) : '—' ?>
                </td>
            </tr>
            <?php $noUrut++; endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<p style="margin-top:12px;font-size:0.78rem;color:var(--text-muted);">
    ⚠️ Data nilai diinput oleh masing-masing guru pengampu. Kosong (—) berarti nilai belum diinput.
</p>

<style>
thead th{border-right:1px solid rgba(255,255,255,0.2);}
tbody td{border-bottom:1px solid var(--border);border-right:1px solid var(--border);}
tr.even-row td{background:var(--surface-2);}
tr.even-row td:nth-child(-n+3){background:var(--surface-2)!important;}
</style>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
