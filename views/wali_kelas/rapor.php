<?php
/**
 * views/wali_kelas/rapor.php — Export Rapor PDF per Siswa
 * Menggunakan FPDF (lightweight, no Composer required)
 * Jalankan: composer require setasign/fpdf  atau letakkan fpdf.php manual di vendor/
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

// Jika ada ?generate=1&siswa_id=X → generate PDF untuk 1 siswa
if (isset($_GET['generate']) && isset($_GET['siswa_id'])) {
    $siswa_id = (int)$_GET['siswa_id'];
    generateRaporPDF($db, $siswa_id, $kelas_id, $ta_id, $waliData);
    exit;
}

// Tampilkan daftar siswa untuk dipilih
$siswas = $db->queryAll(
    "SELECT ks.no_absen, u.id, u.nama, u.nis_nip
     FROM kelas_siswa ks JOIN users u ON u.id=ks.user_id
     WHERE ks.kelas_id=? ORDER BY ks.no_absen, u.nama",
    'i', [$kelas_id]
);

$pageTitle = 'Export Rapor PDF';
ob_start();
?>

<div style="margin-bottom:20px;">
    <h2 style="font-size:1.1rem;font-weight:700;">📄 Export Rapor PDF</h2>
    <p style="font-size:0.82rem;color:var(--text-muted);">Pilih siswa untuk mengunduh Rapor PDF individual.</p>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No Absen</th><th>Nama Siswa</th><th>NIS</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($siswas as $s): ?>
            <tr>
                <td style="text-align:center;"><?= $s['no_absen'] ?? '—' ?></td>
                <td style="font-weight:600;"><?= e($s['nama']) ?></td>
                <td><?= e($s['nis_nip'] ?? '—') ?></td>
                <td>
                    <a href="?page=wk_rapor&generate=1&siswa_id=<?= $s['id'] ?>"
                       class="btn btn-sm btn-danger" target="_blank">
                        📄 Download PDF
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';

// ── Fungsi Generator PDF (FPDF) ─────────────────────────────────────────────
function generateRaporPDF(Database $db, int $siswa_id, int $kelas_id, int $ta_id, array $waliData): void
{
    $fpdfPath = ROOT_PATH . '/vendor/fpdf/fpdf.php';
    if (!file_exists($fpdfPath)) {
        die('Library FPDF tidak ditemukan. Letakkan fpdf.php di vendor/fpdf/fpdf.php atau jalankan: composer require setasign/fpdf');
    }
    require_once $fpdfPath;

    // Ambil data siswa
    $siswa = $db->queryOne("SELECT * FROM users WHERE id=?", 'i', [$siswa_id]);
    $ta    = $db->queryOne("SELECT * FROM tahun_ajaran WHERE id=?", 'i', [$ta_id]);

    // Nilai per mapel
    $nilais = $db->queryAll(
        "SELECT na.*, m.nama_mapel, m.kode_mapel, km.nama AS kelompok, u.nama AS nama_guru
         FROM nilai_akhir na
         JOIN mapel m ON m.id=na.mapel_id
         JOIN kelompok_mapel km ON km.id=m.kelompok_mapel_id
         JOIN users u ON u.id=na.guru_id
         WHERE na.siswa_id=? AND na.kelas_id=? AND na.tahun_ajaran_id=?
         ORDER BY km.id, m.nama_mapel",
        'iii', [$siswa_id, $kelas_id, $ta_id]
    );

    // ── FPDF ──────────────────────────────────────────────
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 20);

    // Header Sekolah
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, 'LAPORAN HASIL BELAJAR SISWA', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 6, 'Madrasah Tsanawiyah', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, $waliData['nama_kelas'] . ' | ' . ($ta['nama'] ?? ''), 0, 1, 'C');
    $pdf->Ln(4);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(4);

    // Info Siswa
    $pdf->SetFont('Arial', 'B', 10);
    $infoSiswa = [
        ['Nama Siswa', $siswa['nama'] ?? '—'],
        ['NIS',        $siswa['nis_nip'] ?? '—'],
        ['Kelas',      $waliData['nama_kelas']],
        ['Wali Kelas', $_SESSION['nama']],
    ];
    foreach ($infoSiswa as [$label, $val]) {
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, $label . ':', 0, 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 6, $val, 0, 1);
    }
    $pdf->Ln(4);

    // Tabel Nilai
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(26, 58, 107);
    $pdf->SetTextColor(255);
    $pdf->Cell(8,  8, 'No',       1, 0, 'C', true);
    $pdf->Cell(70, 8, 'Mata Pelajaran', 1, 0, 'C', true);
    $pdf->Cell(25, 8, 'Harian',   1, 0, 'C', true);
    $pdf->Cell(22, 8, 'UTS',      1, 0, 'C', true);
    $pdf->Cell(22, 8, 'UAS',      1, 0, 'C', true);
    $pdf->Cell(22, 8, 'Akhir',    1, 0, 'C', true);
    $pdf->Cell(11, 8, 'Pred',     1, 1, 'C', true);
    $pdf->SetTextColor(0);

    $no = 1;
    $totalNA = 0;
    $hitungNA = 0;
    $currentKelompok = '';

    foreach ($nilais as $n) {
        // Sub-header kelompok mapel
        if ($n['kelompok'] !== $currentKelompok) {
            $currentKelompok = $n['kelompok'];
            $pdf->SetFont('Arial', 'BI', 8);
            $pdf->SetFillColor(230, 236, 255);
            $pdf->Cell(0, 6, '  ' . $currentKelompok, 1, 1, 'L', true);
            $pdf->SetFont('Arial', '', 9);
        }

        $na = $n['nilai_akhir'];
        if ($na !== null) { $totalNA += $na; $hitungNA++; }

        $pdf->SetFillColor($no % 2 === 0 ? 248 : 255, $no % 2 === 0 ? 249 : 255, $no % 2 === 0 ? 250 : 255);
        $pdf->Cell(8,  7, $no++,                                        1, 0, 'C', true);
        $pdf->Cell(70, 7, $n['nama_mapel'],                             1, 0, 'L', true);
        $pdf->Cell(25, 7, $n['nilai_harian'] !== null ? number_format($n['nilai_harian'],1) : '-', 1, 0, 'C', true);
        $pdf->Cell(22, 7, $n['nilai_uts']    !== null ? number_format($n['nilai_uts'],1)    : '-', 1, 0, 'C', true);
        $pdf->Cell(22, 7, $n['nilai_uas']    !== null ? number_format($n['nilai_uas'],1)    : '-', 1, 0, 'C', true);
        $pdf->Cell(22, 7, $na !== null ? number_format($na, 1) : '-',  1, 0, 'C', true);
        $pdf->Cell(11, 7, $n['predikat'] ?? '-',                       1, 1, 'C', true);
    }

    // Rata-rata
    $rataRata = $hitungNA ? round($totalNA / $hitungNA, 2) : 0;
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(26, 58, 107);
    $pdf->SetTextColor(255);
    $pdf->Cell(125, 7, 'RATA-RATA NILAI AKHIR', 1, 0, 'R', true);
    $pdf->Cell(55, 7, number_format($rataRata, 2), 1, 1, 'C', true);
    $pdf->SetTextColor(0);

    // Tanda tangan wali kelas
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'Mengetahui,', 0, 1, 'R');
    $pdf->Cell(0, 5, 'Wali Kelas ' . $waliData['nama_kelas'], 0, 1, 'R');
    $pdf->Ln(15);
    $pdf->Cell(0, 5, '( ' . ($_SESSION['nama']) . ' )', 0, 1, 'R');

    // Footer
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetY(-15);
    $pdf->Cell(0, 5, 'Dicetak oleh sistem E-Learning MTs | ' . date('d/m/Y H:i'), 0, 0, 'C');

    $filename = 'Rapor_' . preg_replace('/[^A-Za-z0-9]/', '_', $siswa['nama'] ?? 'siswa') . '.pdf';
    $pdf->Output('D', $filename);
}
