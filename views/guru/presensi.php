<?php
Auth::requireRole('guru');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$detail_id = $_GET['id'] ?? 0;

if ($detail_id) {
    // TAMPILAN DETAIL PRESENSI
    $presensi = $db->queryOne(
        "SELECT p.*, k.nama_kelas, m.nama_mapel, jm.kelas_id
         FROM presensi p
         JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id
         JOIN kelas k ON k.id = jm.kelas_id
         JOIN mapel m ON m.id = jm.mapel_id
         WHERE p.id = ? AND jm.guru_id = ?",
        'ii', [$detail_id, $_SESSION['user_id']]
    );
    
    if (!$presensi) {
        setFlash('error', 'Sesi presensi tidak ditemukan.');
        header('Location: ' . BASE_URL . '/index.php?page=g_presensi');
        exit;
    }
    
    // Ambil daftar siswa dan status kehadirannya
    $siswaList = $db->queryAll(
        "SELECT u.id, u.nama, u.nis_nip, ks.no_absen, ps.status_hadir, ps.waktu_absen
         FROM kelas_siswa ks
         JOIN users u ON u.id = ks.user_id
         LEFT JOIN presensi_siswa ps ON ps.siswa_id = u.id AND ps.presensi_id = ?
         WHERE ks.kelas_id = ?
         ORDER BY ks.no_absen",
        'ii', [$detail_id, $presensi['kelas_id']]
    );
    
    $pageTitle = 'Detail Presensi';
    ob_start();
    ?>
    <div class="mb-4">
        <a href="?page=g_presensi" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali</a>
    </div>

    <div class="card mb-6" style="border-left:4px solid <?= $presensi['status'] === 'buka' ? 'var(--success)' : 'var(--danger)' ?>;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3>Pertemuan Ke-<?= $presensi['pertemuan_ke'] ?>: <?= e($presensi['topik']) ?></h3>
                <p class="text-muted mt-2">
                    Kelas: <strong><?= e($presensi['nama_kelas']) ?></strong> | Mapel: <strong><?= e($presensi['nama_mapel']) ?></strong><br>
                    Tanggal: <?= date('d M Y', strtotime($presensi['tanggal'])) ?>
                </p>
            </div>
            <div style="text-align:right;">
                <?php if($presensi['status'] === 'buka'): ?>
                    <span class="badge badge-success mb-2" style="font-size:1rem;">Buka (Berlangsung)</span><br>
                    <a href="<?= BASE_URL ?>/modules/guru/presensi_handler.php?action=tutup&id=<?= $presensi['id'] ?>" class="btn btn-danger btn-sm">Tutup Presensi</a>
                <?php else: ?>
                    <span class="badge badge-danger mb-2" style="font-size:1rem;">Sesi Ditutup</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Daftar Hadir Siswa</span></div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Waktu Absen</th>
                        <th>Status</th>
                        <th>Aksi Guru (Ubah)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($siswaList as $s): ?>
                    <tr>
                        <td><?= $s['no_absen'] ?></td>
                        <td><strong><?= e($s['nama']) ?></strong><br><small class="text-muted"><?= e($s['nis_nip'] ?: '-') ?></small></td>
                        <td><?= $s['waktu_absen'] ? date('H:i:s', strtotime($s['waktu_absen'])) : '-' ?></td>
                        <td>
                            <?php 
                            $st = $s['status_hadir'] ?? 'alpa';
                            $warna = ['hadir'=>'success', 'izin'=>'info', 'sakit'=>'warning', 'alpa'=>'danger'];
                            ?>
                            <span class="badge badge-<?= $warna[$st] ?>"><?= ucfirst($st) ?></span>
                        </td>
                        <td>
                            <form action="<?= BASE_URL ?>/modules/guru/presensi_handler.php?action=update_siswa" method="POST" style="display:flex; gap:5px;">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="presensi_id" value="<?= $presensi['id'] ?>">
                                <input type="hidden" name="siswa_id" value="<?= $s['id'] ?>">
                                <select name="status_hadir" class="form-control" style="width:100px; padding:4px;" onchange="this.form.submit()">
                                    <option value="hadir" <?= $st=='hadir'?'selected':'' ?>>Hadir</option>
                                    <option value="izin" <?= $st=='izin'?'selected':'' ?>>Izin</option>
                                    <option value="sakit" <?= $st=='sakit'?'selected':'' ?>>Sakit</option>
                                    <option value="alpa" <?= $st=='alpa'?'selected':'' ?>>Alpa</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} else {
    // TAMPILAN DAFTAR PRESENSI
    $jadwalList = $db->queryAll(
        "SELECT jm.*, k.nama_kelas, m.nama_mapel 
         FROM jadwal_mengajar jm JOIN kelas k ON k.id = jm.kelas_id JOIN mapel m ON m.id = jm.mapel_id
         WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?", 'ii', [$_SESSION['user_id'], $ta_id]
    );

    $presensiList = $db->queryAll(
        "SELECT p.*, k.nama_kelas, m.nama_mapel,
                (SELECT COUNT(*) FROM presensi_siswa ps WHERE ps.presensi_id = p.id AND ps.status_hadir='hadir') as jml_hadir
         FROM presensi p
         JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id
         JOIN kelas k ON k.id = jm.kelas_id
         JOIN mapel m ON m.id = jm.mapel_id
         WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?
         ORDER BY p.tanggal DESC, p.pertemuan_ke DESC",
        'ii', [$_SESSION['user_id'], $ta_id]
    );

    $pageTitle = 'Kelola Presensi';
    ob_start();
    ?>
    <div class="card mb-6">
        <div class="card-header">
            <span class="card-title">📅 Presensi Kelas</span>
            <button class="btn btn-primary btn-sm" onclick="showModal('addPresensiModal')">+ Buka Sesi Presensi</button>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelas & Mapel</th>
                        <th>Pertemuan / Topik</th>
                        <th>Hadir</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($presensiList)): ?>
                    <tr><td colspan="6" class="text-center text-muted">Belum ada sesi presensi yang dibuat.</td></tr>
                    <?php else: ?>
                        <?php foreach($presensiList as $p): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                            <td><span class="badge badge-primary"><?= e($p['nama_kelas']) ?></span><br><small><?= e($p['nama_mapel']) ?></small></td>
                            <td>Ke-<?= $p['pertemuan_ke'] ?>: <?= e($p['topik']) ?></td>
                            <td><span class="badge badge-info"><?= $p['jml_hadir'] ?> Siswa</span></td>
                            <td>
                                <?php if($p['status'] === 'buka'): ?>
                                    <span class="badge badge-success">Buka</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tutup</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?page=g_presensi&id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Lihat</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/guru/presensi_handler.php?action=delete&id=<?= $p['id'] ?>')"><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="addPresensiModal" class="modal">
        <div class="modal-content" style="max-width:500px;">
            <h3 class="mb-4">Buka Sesi Presensi Baru</h3>
            <form action="<?= BASE_URL ?>/modules/guru/presensi_handler.php?action=buka" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                
                <div class="form-group">
                    <label>Pilih Kelas & Mapel</label>
                    <select name="jadwal_mengajar_id" class="form-control" required>
                        <?php foreach($jadwalList as $j): ?>
                        <option value="<?= $j['id'] ?>"><?= e($j['nama_kelas']) ?> - <?= e($j['nama_mapel']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div style="display:flex; gap:15px;">
                    <div class="form-group" style="flex:1;">
                        <label>Pertemuan Ke</label>
                        <input type="number" name="pertemuan_ke" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group" style="flex:3;">
                        <label>Topik Bahasan</label>
                        <input type="text" name="topik" class="form-control" placeholder="Contoh: Pengantar Aljabar" required>
                    </div>
                </div>
                
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" class="btn btn-outline" onclick="hideModal('addPresensiModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Buka Presensi</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
