<?php
/**
 * views/admin/jadwal_mengajar.php
 * Pemetaan: Guru + Mapel + Kelas pada Tahun Ajaran aktif.
 * Ini adalah tabel pivot paling penting di sistem.
 */
Auth::requireRole('admin');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1 LIMIT 1");
$ta_id   = $taAktif['id'] ?? 0;

// Ambil semua jadwal mengajar dengan JOIN
$jadwals = $db->queryAll(
    "SELECT jm.*, u.nama AS nama_guru, m.nama_mapel, k.nama_kelas
     FROM jadwal_mengajar jm
     JOIN users u ON u.id = jm.guru_id
     JOIN mapel m ON m.id = jm.mapel_id
     JOIN kelas k ON k.id = jm.kelas_id
     WHERE jm.tahun_ajaran_id = ?
     ORDER BY k.nama_kelas, m.nama_mapel",
    'i', [$ta_id]
);

// Data untuk form dropdown
$guruList  = $db->queryAll("SELECT id, nama FROM users WHERE role='guru' AND is_active=1 ORDER BY nama");
$mapelList = $db->queryAll("SELECT id, nama_mapel, kode_mapel FROM mapel ORDER BY nama_mapel");
$kelasList = $db->queryAll("SELECT k.id, k.nama_kelas FROM kelas k WHERE k.tahun_ajaran_id=? ORDER BY k.nama_kelas", 'i', [$ta_id]);
$hariList  = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

$pageTitle = 'Jadwal Mengajar';
ob_start();
?>

<?php if (!$taAktif): ?>
<div class="card" style="border-left:4px solid var(--danger);padding:20px;">
    ⚠️ Belum ada Tahun Ajaran yang diset aktif. <a href="?page=a_tahun_ajaran">Atur sekarang</a>.
</div>
<?php else: ?>

<div class="card-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.1rem;font-weight:700;">👨‍🏫 Penugasan Guru</h2>
        <p style="font-size:0.82rem;color:var(--text-muted);">TA: <strong><?= e($taAktif['nama']) ?></strong></p>
    </div>
    <button class="btn btn-primary" onclick="showModal('modal-jadwal')">+ Tambah Penugasan</button>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Kelas</th><th>Mata Pelajaran</th><th>Guru Pengampu</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if (empty($jadwals)): ?>
            <tr><td colspan="5" class="text-center text-muted" style="padding:30px;">Belum ada penugasan guru. Silakan tambah.</td></tr>
            <?php else: ?>
            <?php foreach ($jadwals as $i => $j): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $i+1 ?></td>
                <td><strong><?= e($j['nama_kelas']) ?></strong></td>
                <td><?= e($j['nama_mapel']) ?></td>
                <td>👨‍🏫 <?= e($j['nama_guru']) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/modules/admin/jadwal_handler.php?action=hapus&id=<?= $j['id'] ?>"
                       class="btn btn-sm btn-danger"
                       data-confirm="Hapus jadwal ini? Data materi/tugas terkait tidak akan terhapus.">🗑 Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div id="modal-jadwal" class="modal">
    <div class="modal-content" style="max-width:520px;">
        <h3 class="mb-4">Tambah Penugasan Guru</h3>
        <form method="POST" action="<?= BASE_URL ?>/modules/admin/jadwal_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="tambah">
            <input type="hidden" name="tahun_ajaran_id" value="<?= $ta_id ?>">

            <div class="form-group">
                <label>Kelas *</label>
                <select name="kelas_id" class="form-control" required>
                    <option value="">— Pilih Kelas —</option>
                    <?php foreach ($kelasList as $k): ?>
                    <option value="<?= $k['id'] ?>"><?= e($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran *</label>
                <select name="mapel_id" class="form-control" required>
                    <option value="">— Pilih Mapel —</option>
                    <?php foreach ($mapelList as $m): ?>
                    <option value="<?= $m['id'] ?>">[<?= e($m['kode_mapel']) ?>] <?= e($m['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Guru Pengampu *</label>
                <select name="guru_id" class="form-control" required>
                    <option value="">— Pilih Guru —</option>
                    <?php foreach ($guruList as $g): ?>
                    <option value="<?= $g['id'] ?>"><?= e($g['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="hari" value="">
            <input type="hidden" name="jam_mulai" value="">
            <input type="hidden" name="jam_selesai" value="">

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px;">
                <button type="button" onclick="hideModal('modal-jadwal')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>



<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
