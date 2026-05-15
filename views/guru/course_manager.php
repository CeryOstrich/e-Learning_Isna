<?php
Auth::requireRole('guru');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$jm_id = $_GET['jm_id'] ?? 0;

$jadwalList = $db->queryAll(
    "SELECT jm.*, k.nama_kelas, k.tingkat, m.nama_mapel 
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?",
    'ii', [$_SESSION['user_id'], $ta_id]
);

$pageTitle = 'Manajemen Silabus';
ob_start();
?>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">📚 Pilih Kelas & Mata Pelajaran</span></div>
    
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <?php foreach($jadwalList as $j): ?>
            <a href="?page=g_course&jm_id=<?= $j['id'] ?>" class="btn <?= $jm_id == $j['id'] ? 'btn-primary' : 'btn-outline' ?>" style="text-align:left;">
                <div style="font-weight:bold;"><?= e($j['nama_mapel']) ?></div>
                <div style="font-size:0.8rem;">Kelas <?= e($j['nama_kelas']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if($jm_id): ?>
    <?php
    $jm_aktif = array_filter($jadwalList, fn($j) => $j['id'] == $jm_id);
    $jm_aktif = reset($jm_aktif);
    
    $modulList = $db->queryAll("SELECT * FROM modul WHERE jadwal_mengajar_id = ? ORDER BY urutan ASC", 'i', [$jm_id]);
    ?>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h3>Silabus: <?= e($jm_aktif['nama_mapel']) ?> (Kelas <?= e($jm_aktif['nama_kelas']) ?>)</h3>
        <button class="btn btn-primary" onclick="showModal('addModulModal')">+ Tambah Bab/Modul Baru</button>
    </div>

    <?php if(empty($modulList)): ?>
        <div class="alert alert-info show">Belum ada modul untuk mata pelajaran ini. Silakan buat Modul/Bab pertama Anda.</div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:20px;">
            <?php foreach($modulList as $m): ?>
                <?php
                // Ambil items untuk modul ini
                $items = $db->queryAll("SELECT * FROM modul_item WHERE modul_id=? ORDER BY urutan ASC", 'i', [$m['id']]);
                ?>
                <div class="card" style="border-left: 4px solid var(--primary);">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px;">
                        <div>
                            <h4>Bab <?= $m['urutan'] ?>: <?= e($m['judul']) ?></h4>
                            <p class="text-muted" style="font-size:0.9rem;"><?= nl2br(e($m['deskripsi'])) ?></p>
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/guru/course_handler.php?action=delete_modul&id=<?= $m['id'] ?>&jm_id=<?= $jm_id ?>')"><i class='bx bx-trash'></i> Hapus Bab</button>
                    </div>

                    <div style="background:var(--bg); border-radius:8px; padding:15px;">
                        <?php if(empty($items)): ?>
                            <p class="text-muted text-center" style="font-size:0.85rem; margin:0;">Modul ini masih kosong.</p>
                        <?php else: ?>
                            <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                                <?php foreach($items as $idx => $item): ?>
                                    <li style="display:flex; justify-content:space-between; align-items:center; padding:10px; background:var(--card); border-radius:6px; border:1px solid var(--border);">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <?php if($item['tipe'] === 'materi'): ?>
                                                <i class='bx bxs-file-blank text-primary' style="font-size:1.5rem;"></i>
                                            <?php elseif($item['tipe'] === 'live_class'): ?>
                                                <i class='bx bxs-video text-danger' style="font-size:1.5rem;"></i>
                                            <?php else: ?>
                                                <i class='bx bxs-check-square text-success' style="font-size:1.5rem;"></i>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <strong><?= $idx+1 ?>. <?= e($item['judul']) ?></strong>
                                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                                    <?php 
                                                    if($item['tipe'] === 'materi') echo 'Materi Bacaan/Video';
                                                    elseif($item['tipe'] === 'live_class') echo 'Live Class ('.date('d M Y, H:i', strtotime($item['isi_teks'])).')';
                                                    else echo 'Kuis Pilihan Ganda/Essay ('.$item['durasi_menit'].' Menit)';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <?php if($item['tipe'] === 'materi'): ?>
                                                <a href="?page=g_builder_materi&id=<?= $item['id'] ?>&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm"><i class='bx bx-edit'></i> Edit Materi</a>
                                            <?php elseif($item['tipe'] === 'live_class'): ?>
                                                <a href="<?= e($item['file_path']) ?>" target="_blank" class="btn btn-outline btn-sm"><i class='bx bx-link-external'></i> Buka Link</a>
                                            <?php else: ?>
                                                <a href="?page=g_koreksi_kuis&id=<?= $item['id'] ?>&jm_id=<?= $jm_id ?>" class="btn btn-success btn-sm"><i class='bx bx-check-double'></i> Koreksi</a>
                                                <a href="?page=g_builder_kuis&id=<?= $item['id'] ?>&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm"><i class='bx bx-edit'></i> Kelola Soal</a>
                                            <?php endif; ?>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/guru/course_handler.php?action=delete_item&id=<?= $item['id'] ?>&jm_id=<?= $jm_id ?>')"><i class='bx bx-trash'></i></button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                        <div style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">
                            <a href="?page=g_builder_materi&modul_id=<?= $m['id'] ?>&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm" style="flex:1; text-align:center;">+ Teks Materi / Video</a>
                            <a href="?page=g_builder_kuis&modul_id=<?= $m['id'] ?>&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm" style="flex:1; text-align:center; color:var(--success); border-color:var(--success);">+ Kuis Interaktif</a>
                            <button onclick="showModal('addLiveModal_<?= $m['id'] ?>')" class="btn btn-outline btn-sm" style="flex:1; text-align:center; color:var(--danger); border-color:var(--danger);">+ Live Class</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah Live Class -->
                <div id="addLiveModal_<?= $m['id'] ?>" class="modal">
                    <div class="modal-content" style="max-width:500px;">
                        <h3 class="mb-4">Tambah Sesi Live Class</h3>
                        <form action="<?= BASE_URL ?>/modules/guru/course_handler.php?action=add_live" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="jadwal_mengajar_id" value="<?= $jm_id ?>">
                            <input type="hidden" name="modul_id" value="<?= $m['id'] ?>">
                            
                            <div class="form-group">
                                <label>Topik Bahasan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Bedah Soal Bab 1" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="datetime-local" name="waktu_mulai" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Link Vicon (Zoom/GMeet)</label>
                                <input type="url" name="link_vicon" class="form-control" placeholder="https://meet.google.com/..." required>
                            </div>
                            
                            <div style="display:flex; gap:10px; justify-content:flex-end;">
                                <button type="button" class="btn btn-outline" onclick="hideModal('addLiveModal_<?= $m['id'] ?>')">Batal</button>
                                <button type="submit" class="btn btn-danger">Buat Live Class</button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Modal Tambah Modul -->
    <div id="addModulModal" class="modal">
        <div class="modal-content" style="max-width:500px;">
            <h3 class="mb-4">Tambah Bab/Modul Baru</h3>
            <form action="<?= BASE_URL ?>/modules/guru/course_handler.php?action=add_modul" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="jadwal_mengajar_id" value="<?= $jm_id ?>">
                
                <div class="form-group">
                    <label>Judul Bab/Modul</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Bab 1 - Pengenalan Aljabar" required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" class="btn btn-outline" onclick="hideModal('addModulModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
