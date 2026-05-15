<?php
/**
 * views/siswa/nilai.php — Halaman Nilai Siswa (view-only)
 */
Auth::requireRole('siswa');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

$kelas = $db->queryOne(
    "SELECT ks.kelas_id, k.nama_kelas, k.tahun_ajaran_id, ta.nama AS nama_ta
     FROM kelas_siswa ks JOIN kelas k ON k.id=ks.kelas_id
     JOIN tahun_ajaran ta ON ta.id=k.tahun_ajaran_id
     WHERE ks.user_id=? AND ta.is_aktif=1 LIMIT 1",
    'i', [$uid]
);

$nilais = [];
if ($kelas) {
    $nilais = $db->queryAll(
        "SELECT na.*, m.nama_mapel, m.kode_mapel, km.nama AS kelompok
         FROM nilai_akhir na
         JOIN mapel m ON m.id=na.mapel_id
         JOIN kelompok_mapel km ON km.id=m.kelompok_mapel_id
         WHERE na.siswa_id=? AND na.kelas_id=? AND na.tahun_ajaran_id=?
         ORDER BY km.id, m.nama_mapel",
        'iii', [$uid, $kelas['kelas_id'], $kelas['tahun_ajaran_id']]
    );
}

$pageTitle = 'Nilai Saya';
ob_start();
?>

<div style="margin-bottom:20px;">
    <h2 style="font-size:1.1rem;font-weight:700;">🌟 Nilai Saya</h2>
    <?php if ($kelas): ?>
    <p style="font-size:0.82rem;color:var(--text-muted);">
        Kelas <?= e($kelas['nama_kelas']) ?> | <?= e($kelas['nama_ta']) ?>
    </p>
    <?php endif; ?>
</div>

<?php if (!$kelas): ?>
<div class="card text-center" style="padding:50px;">
    <p class="text-muted">Anda belum terdaftar di kelas manapun.</p>
</div>
<?php elseif (empty($nilais)): ?>
<div class="card text-center" style="padding:50px;">
    <div style="font-size:40px;margin-bottom:12px;">📭</div>
    <p class="text-muted">Nilai belum diinput oleh guru. Cek kembali nanti.</p>
</div>
<?php else:
    $totalNA = 0; $hitungNA = 0;
    $currentKelompok = '';
?>
<div class="card" style="padding:0;">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Mata Pelajaran</th>
                    <th style="text-align:center;">Harian</th>
                    <th style="text-align:center;">UTS</th>
                    <th style="text-align:center;">UAS</th>
                    <th style="text-align:center;">Nilai Akhir</th>
                    <th style="text-align:center;">Predikat</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; foreach ($nilais as $n):
                if ($n['kelompok'] !== $currentKelompok) {
                    $currentKelompok = $n['kelompok'];
                    echo "<tr><td colspan='7' style='background:var(--surface-2);font-weight:700;font-size:0.8rem;color:var(--primary);padding:8px 16px;'>$currentKelompok</td></tr>";
                }
                $na = $n['nilai_akhir'];
                if ($na !== null) { $totalNA += $na; $hitungNA++; }
                $pred = $n['predikat'];
                $predColor = match($pred) {
                    'A' => 'var(--success)', 'B' => 'var(--info)',
                    'C' => 'var(--warning)', 'D' => 'var(--danger)',
                    default => 'var(--text-muted)'
                };
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <div style="font-weight:600;"><?= e($n['nama_mapel']) ?></div>
                    <div style="font-size:0.75rem;color:var(--text-muted);"><?= e($n['kode_mapel']) ?></div>
                </td>
                <td style="text-align:center;"><?= $n['nilai_harian'] !== null ? number_format($n['nilai_harian'],1) : '—' ?></td>
                <td style="text-align:center;"><?= $n['nilai_uts']    !== null ? number_format($n['nilai_uts'],1)    : '—' ?></td>
                <td style="text-align:center;"><?= $n['nilai_uas']    !== null ? number_format($n['nilai_uas'],1)    : '—' ?></td>
                <td style="text-align:center;font-weight:700;font-size:1.1rem;color:<?= $predColor ?>;">
                    <?= $na !== null ? number_format($na,1) : '—' ?>
                </td>
                <td style="text-align:center;">
                    <?php if ($pred): ?>
                    <span class="badge badge-<?= $pred==='A' ? 'success' : ($pred==='B' ? 'info' : ($pred==='C' ? 'warning' : 'danger')) ?>">
                        <?= $pred ?>
                    </span>
                    <?php else: ?>—<?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background:var(--primary);color:white;">
                    <td colspan="5" style="padding:12px 16px;font-weight:700;text-align:right;">Rata-rata Nilai Akhir:</td>
                    <td style="text-align:center;font-weight:800;font-size:1.2rem;padding:12px;">
                        <?= $hitungNA ? number_format($totalNA/$hitungNA, 2) : '—' ?>
                    </td>
                    <td style="text-align:center;font-weight:700;padding:12px;">
                        <?= $hitungNA ? nilaiKePredikat($totalNA/$hitungNA) : '—' ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
