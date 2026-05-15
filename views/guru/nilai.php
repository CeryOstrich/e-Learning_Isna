<?php
/**
 * views/guru/nilai.php — Input Nilai Akhir per Siswa per Mapel
 */
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

// Guru pilih kelas & mapel via jadwal_mengajar_id
$jm_id = (int)($_GET['jm_id'] ?? 0);

// Ambil semua jadwal mengajar guru ini
$jadwals = $db->queryAll(
    "SELECT jm.id, k.nama_kelas, m.nama_mapel, m.id AS mapel_id, k.id AS kelas_id
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id=jm.kelas_id JOIN mapel m ON m.id=jm.mapel_id
     JOIN tahun_ajaran ta ON ta.id=jm.tahun_ajaran_id
     WHERE jm.guru_id=? AND ta.is_aktif=1",
    'i', [$uid]
);

$selJM = null;
$siswas = [];
$nilaiMap = [];

if ($jm_id) {
    $selJM = $db->queryOne(
        "SELECT jm.*, k.nama_kelas, m.nama_mapel, m.id AS mapel_id, k.id AS kelas_id, ta.id AS ta_id
         FROM jadwal_mengajar jm JOIN kelas k ON k.id=jm.kelas_id JOIN mapel m ON m.id=jm.mapel_id
         JOIN tahun_ajaran ta ON ta.id=jm.tahun_ajaran_id
         WHERE jm.id=? AND jm.guru_id=?",
        'ii', [$jm_id, $uid]
    );

    if ($selJM) {
        $siswas = $db->queryAll(
            "SELECT ks.no_absen, u.id, u.nama, u.nis_nip
             FROM kelas_siswa ks JOIN users u ON u.id=ks.user_id
             WHERE ks.kelas_id=? ORDER BY ks.no_absen, u.nama",
            'i', [$selJM['kelas_id']]
        );

        $nilaiRows = $db->queryAll(
            "SELECT * FROM nilai_akhir WHERE kelas_id=? AND mapel_id=? AND tahun_ajaran_id=?",
            'iii', [$selJM['kelas_id'], $selJM['mapel_id'], $selJM['ta_id']]
        );
        foreach ($nilaiRows as $n) $nilaiMap[$n['siswa_id']] = $n;
    }
}

$pageTitle = 'Input Nilai Akhir';
ob_start();
?>

<div class="card-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <h2 style="font-size:1.1rem;font-weight:700;">🌟 Input Nilai Akhir</h2>
</div>

<!-- Pilih Kelas & Mapel -->
<div class="card mb-4">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="page" value="g_nilai">
        <select name="jm_id" class="form-control" style="max-width:320px;" onchange="this.form.submit()">
            <option value="">— Pilih Kelas & Mata Pelajaran —</option>
            <?php foreach ($jadwals as $j): ?>
            <option value="<?= $j['id'] ?>" <?= $jm_id == $j['id'] ? 'selected' : '' ?>>
                <?= e($j['nama_kelas']) ?> — <?= e($j['nama_mapel']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($selJM && !empty($siswas)): ?>
<!-- Panduan perhitungan -->
<div class="card mb-4" style="background:var(--info-bg);border:1px solid var(--info);padding:14px 18px;font-size:0.82rem;">
    ℹ️ <strong>Formula Nilai Akhir:</strong> (30% × Harian) + (30% × UTS) + (40% × UAS)
    &nbsp;|&nbsp; Predikat: A≥90, B≥75, C≥60, D&lt;60
</div>

<form method="POST" action="<?= BASE_URL ?>/modules/guru/nilai_handler.php">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="jm_id" value="<?= $jm_id ?>">
    <div class="card" style="padding:0;">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th><th>Nama Siswa</th><th>NIS</th>
                        <th>Harian<br><span style="font-weight:400;font-size:0.7rem;">(0-100)</span></th>
                        <th>UTS<br><span style="font-weight:400;font-size:0.7rem;">(0-100)</span></th>
                        <th>UAS<br><span style="font-weight:400;font-size:0.7rem;">(0-100)</span></th>
                        <th>Akhir</th>
                        <th>Predikat</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($siswas as $i => $s):
                    $n = $nilaiMap[$s['id']] ?? null;
                ?>
                <tr>
                    <td><?= $s['no_absen'] ?: $i+1 ?></td>
                    <td style="font-weight:600;white-space:nowrap;"><?= e($s['nama']) ?></td>
                    <td style="font-size:0.8rem;color:var(--text-muted);"><?= e($s['nis_nip'] ?? '—') ?></td>
                    <td>
                        <input type="number" name="nilai[<?= $s['id'] ?>][harian]" class="form-control nilai-input"
                               style="max-width:70px;" min="0" max="100" step="0.5"
                               value="<?= $n ? $n['nilai_harian'] : '' ?>"
                               data-siswa="<?= $s['id'] ?>">
                    </td>
                    <td>
                        <input type="number" name="nilai[<?= $s['id'] ?>][uts]" class="form-control nilai-input"
                               style="max-width:70px;" min="0" max="100" step="0.5"
                               value="<?= $n ? $n['nilai_uts'] : '' ?>"
                               data-siswa="<?= $s['id'] ?>">
                    </td>
                    <td>
                        <input type="number" name="nilai[<?= $s['id'] ?>][uas]" class="form-control nilai-input"
                               style="max-width:70px;" min="0" max="100" step="0.5"
                               value="<?= $n ? $n['nilai_uas'] : '' ?>"
                               data-siswa="<?= $s['id'] ?>">
                    </td>
                    <td id="na-<?= $s['id'] ?>" style="font-weight:700;text-align:center;color:var(--primary);">
                        <?= $n && $n['nilai_akhir'] !== null ? number_format($n['nilai_akhir'],1) : '—' ?>
                    </td>
                    <td id="pred-<?= $s['id'] ?>" style="text-align:center;font-weight:700;">
                        <?= $n ? ($n['predikat'] ?? '—') : '—' ?>
                    </td>
                    <td>
                        <input type="text" name="nilai[<?= $s['id'] ?>][catatan]" class="form-control"
                               style="max-width:150px;" placeholder="Opsional..."
                               value="<?= e($n ? $n['catatan_guru'] : '') ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:16px;">
        <button type="submit" class="btn btn-primary btn-lg">💾 Simpan Semua Nilai</button>
    </div>
</form>

<script>
// Auto-hitung nilai akhir & predikat secara real-time
document.querySelectorAll('.nilai-input').forEach(inp => {
    inp.addEventListener('input', function() {
        const sid = this.dataset.siswa;
        const h = parseFloat(document.querySelector(`input[name="nilai[${sid}][harian]"]`)?.value) || 0;
        const u = parseFloat(document.querySelector(`input[name="nilai[${sid}][uts]"]`)?.value)    || 0;
        const a = parseFloat(document.querySelector(`input[name="nilai[${sid}][uas]"]`)?.value)    || 0;

        if (h === 0 && u === 0 && a === 0) {
            document.getElementById(`na-${sid}`).textContent   = '—';
            document.getElementById(`pred-${sid}`).textContent = '—';
            return;
        }

        const na = Math.round(((h * 0.30) + (u * 0.30) + (a * 0.40)) * 10) / 10;
        const pred = na >= 90 ? 'A' : na >= 75 ? 'B' : na >= 60 ? 'C' : 'D';
        const colors = { A: 'var(--success)', B: 'var(--info)', C: 'var(--warning)', D: 'var(--danger)' };

        document.getElementById(`na-${sid}`).textContent   = na.toFixed(1);
        document.getElementById(`pred-${sid}`).textContent = pred;
        document.getElementById(`pred-${sid}`).style.color = colors[pred];
    });
});
</script>

<?php elseif ($jm_id && $selJM && empty($siswas)): ?>
<div class="card text-center" style="padding:40px;">
    <p class="text-muted">Belum ada siswa yang terdaftar di kelas ini.</p>
</div>
<?php elseif (!$jm_id): ?>
<div class="card text-center" style="padding:50px;">
    <div style="font-size:40px;margin-bottom:12px;">👆</div>
    <p>Pilih kelas dan mata pelajaran di atas untuk mulai menginput nilai.</p>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
