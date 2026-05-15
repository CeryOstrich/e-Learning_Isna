<?php
Auth::requireRole('guru');
$db = Database::getInstance();

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

$detail_id = $_GET['id'] ?? 0;

if ($detail_id) {
    // DETAIL FORUM
    $thread = $db->queryOne(
        "SELECT ft.*, k.nama_kelas, m.nama_mapel, u.nama as pembuat
         FROM forum_thread ft
         JOIN jadwal_mengajar jm ON jm.id = ft.jadwal_mengajar_id
         JOIN kelas k ON k.id = jm.kelas_id
         JOIN mapel m ON m.id = jm.mapel_id
         JOIN users u ON u.id = ft.dibuat_oleh
         WHERE ft.id = ? AND jm.guru_id = ?",
        'ii', [$detail_id, $_SESSION['user_id']]
    );
    
    if (!$thread) {
        setFlash('error', 'Forum tidak ditemukan.');
        header('Location: ' . BASE_URL . '/index.php?page=g_forum');
        exit;
    }
    
    $replies_raw = $db->queryAll(
        "SELECT fr.*, u.nama, u.role, u.foto_profil 
         FROM forum_reply fr 
         JOIN users u ON u.id = fr.user_id 
         WHERE fr.thread_id = ? 
         ORDER BY fr.created_at ASC",
        'i', [$detail_id]
    );

    $reactions_raw = $db->queryAll(
        "SELECT r.*, u.nama FROM forum_reaction r JOIN users u ON u.id = r.user_id WHERE r.reply_id IN (SELECT id FROM forum_reply WHERE thread_id = ?)",
        'i', [$detail_id]
    );

    $reactions = [];
    foreach($reactions_raw as $r) {
        $reactions[$r['reply_id']][] = $r;
    }

    $replies_tree = [];
    $total_replies = 0;
    foreach($replies_raw as $r) {
        $pid = $r['parent_id'] ?: 0;
        $replies_tree[$pid][] = $r;
        $total_replies++;
    }

    $pageTitle = 'Forum: ' . $thread['judul'];
    ob_start();
    ?>
    <div class="mb-4">
        <a href="?page=g_forum" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali ke Daftar Forum</a>
    </div>

    <!-- Topik Utama -->
    <div class="card mb-6" style="border-top: 4px solid var(--primary);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px;">
            <div>
                <h3 class="mb-2"><?= e($thread['judul']) ?></h3>
                <span class="badge badge-info"><?= e($thread['nama_kelas']) ?> - <?= e($thread['nama_mapel']) ?></span>
            </div>
            <div style="text-align:right; color:var(--text-muted); font-size:0.85rem;">
                <i class='bx bx-time-five'></i> <?= date('d M Y H:i', strtotime($thread['created_at'])) ?><br>
                Oleh <strong><?= e($thread['pembuat']) ?></strong>
            </div>
        </div>
        <div style="padding:15px; background:var(--bg); border-radius:8px; line-height:1.6;">
            <?= nl2br(e($thread['deskripsi'])) ?>
        </div>
    </div>

    <h4 class="mb-4">Komentar & Diskusi (<?= $total_replies ?>)</h4>

    <!-- Balasan -->
    <div style="display:flex; flex-direction:column; gap:15px; margin-bottom:30px;">
        <?php 
        if(empty($replies_raw)): ?>
            <div class="text-center text-muted" style="padding:20px; background:var(--card); border-radius:8px;">Belum ada balasan. Jadilah yang pertama!</div>
        <?php else: 
            function render_replies($parent_id, $replies_tree, $reactions, $thread_id, $depth = 0) {
                if (!isset($replies_tree[$parent_id])) return;
                foreach ($replies_tree[$parent_id] as $r): 
                    $margin = $depth * 40;
                    $is_mine = $r['user_id'] == $_SESSION['user_id'];
                    $reply_reactions = $reactions[$r['id']] ?? [];
                    
                    // Group emotes
                    $emotes = [];
                    $my_reaction = null;
                    foreach($reply_reactions as $re) {
                        $emotes[$re['reaction_type']][] = $re['nama'];
                        if($re['user_id'] == $_SESSION['user_id']) $my_reaction = $re['reaction_type'];
                    }
        ?>
                <div class="card" style="display:flex; gap:15px; padding:15px; margin-left: <?= $margin ?>px; <?= $is_mine ? 'border-left: 3px solid var(--success);' : '' ?>">
                    <img src="<?= $r['foto_profil'] ? BASE_URL.'/uploads/profil/'.$r['foto_profil'] : 'https://ui-avatars.com/api/?name='.urlencode($r['nama']).'&background=random' ?>" style="width:45px; height:45px; border-radius:50%;">
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                            <strong><?= e($r['nama']) ?> <span class="badge badge-<?= $r['role']=='guru'?'success':'info' ?>" style="font-size:0.7rem;"><?= ucfirst($r['role']) ?></span></strong>
                            <div>
                                <span style="color:var(--text-muted); font-size:0.8rem; margin-right:10px;"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></span>
                                <!-- Aksi -->
                                <?php if($is_mine): ?>
                                    <button class="btn btn-sm btn-outline" style="padding:2px 5px; font-size:0.75rem;" onclick="toggleEdit(<?= $r['id'] ?>)"><i class='bx bx-edit'></i></button>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=delete_reply&id=<?= $r['id'] ?>&thread_id=<?= $thread_id ?>" class="btn btn-sm btn-danger" style="padding:2px 5px; font-size:0.75rem;" onclick="return confirm('Hapus komentar ini?')"><i class='bx bx-trash'></i></a>
                            </div>
                        </div>
                        
                        <div id="pesan-<?= $r['id'] ?>" style="line-height:1.5; font-size:0.95rem;"><?= nl2br(e($r['pesan'])) ?></div>
                        
                        <!-- Form Edit (Hidden) -->
                        <div id="form-edit-<?= $r['id'] ?>" style="display:none; margin-top:10px;">
                            <form action="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=edit_reply" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
                                <input type="hidden" name="reply_id" value="<?= $r['id'] ?>">
                                <textarea name="pesan" class="form-control mb-2" rows="2" required><?= e($r['pesan']) ?></textarea>
                                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="toggleEdit(<?= $r['id'] ?>)">Batal</button>
                            </form>
                        </div>

                        <!-- Reactions & Reply Btn -->
                        <div style="display:flex; gap:10px; margin-top:10px; align-items:center;">
                            <button class="btn btn-sm btn-outline" style="padding:2px 8px; font-size:0.8rem; border:none; background:var(--bg);" onclick="toggleReply(<?= $r['id'] ?>)"><i class='bx bx-reply'></i> Balas</button>
                            
                            <div style="display:flex; gap:5px;">
                                <?php 
                                $allowed_emotes = ['👍', '❤️', '😂', '😲', '😢'];
                                foreach($allowed_emotes as $em): 
                                    $count = isset($emotes[$em]) ? count($emotes[$em]) : 0;
                                    $active = ($my_reaction === $em) ? 'background:var(--primary-light); border-color:var(--primary);' : 'background:transparent; border-color:transparent;';
                                ?>
                                <form action="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=react" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
                                    <input type="hidden" name="reply_id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="reaction_type" value="<?= $em ?>">
                                    <button type="submit" title="<?= $count>0 ? implode(', ', $emotes[$em]) : 'React' ?>" style="cursor:pointer; padding:2px 5px; border-radius:10px; border:1px solid #ddd; <?= $active ?> font-size:0.9rem;">
                                        <?= $em ?> <?= $count>0 ? $count : '' ?>
                                    </button>
                                </form>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Form Reply (Hidden) -->
                        <div id="form-reply-<?= $r['id'] ?>" style="display:none; margin-top:10px; margin-left:20px;">
                            <form action="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=reply" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
                                <input type="hidden" name="parent_id" value="<?= $r['id'] ?>">
                                <textarea name="pesan" class="form-control mb-2" rows="2" placeholder="Tulis balasan..." required></textarea>
                                <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="toggleReply(<?= $r['id'] ?>)">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
        <?php 
                render_replies($r['id'], $replies_tree, $reactions, $thread_id, $depth + 1);
                endforeach;
            }
            render_replies(0, $replies_tree, $reactions, $detail_id);
        endif; 
        ?>
    </div>
    
    <script>
    function toggleEdit(id) {
        const msg = document.getElementById('pesan-' + id);
        const form = document.getElementById('form-edit-' + id);
        if(form.style.display === 'none') {
            form.style.display = 'block';
            msg.style.display = 'none';
        } else {
            form.style.display = 'none';
            msg.style.display = 'block';
        }
    }
    function toggleReply(id) {
        const form = document.getElementById('form-reply-' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
    </script>

    <!-- Form Balas -->
    <div class="card">
        <h5 class="mb-3">Tulis Balasan</h5>
        <form action="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=reply" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="thread_id" value="<?= $thread['id'] ?>">
            <textarea name="pesan" class="form-control mb-3" rows="3" placeholder="Ketik pesan Anda di sini..." required></textarea>
            <button type="submit" class="btn btn-primary"><i class='bx bx-send'></i> Kirim Balasan</button>
        </form>
    </div>
    <?php
} else {
    // DAFTAR FORUM
    $jadwalList = $db->queryAll(
        "SELECT jm.*, k.nama_kelas, m.nama_mapel 
         FROM jadwal_mengajar jm JOIN kelas k ON k.id = jm.kelas_id JOIN mapel m ON m.id = jm.mapel_id
         WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?", 'ii', [$_SESSION['user_id'], $ta_id]
    );

    $forumList = $db->queryAll(
        "SELECT ft.*, k.nama_kelas, m.nama_mapel,
                (SELECT COUNT(*) FROM forum_reply fr WHERE fr.thread_id = ft.id) as jml_balasan
         FROM forum_thread ft
         JOIN jadwal_mengajar jm ON jm.id = ft.jadwal_mengajar_id
         JOIN kelas k ON k.id = jm.kelas_id
         JOIN mapel m ON m.id = jm.mapel_id
         WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?
         ORDER BY ft.created_at DESC",
        'ii', [$_SESSION['user_id'], $ta_id]
    );

    $pageTitle = 'Forum Diskusi';
    ob_start();
    ?>
    <div class="card mb-6">
        <div class="card-header">
            <span class="card-title">💬 Forum Diskusi Kelas</span>
            <button class="btn btn-primary btn-sm" onclick="showModal('addForumModal')">+ Buat Diskusi Baru</button>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th width="150">Dibuat</th>
                        <th>Topik Diskusi</th>
                        <th>Kelas & Mapel</th>
                        <th>Balasan</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($forumList)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada forum diskusi.</td></tr>
                    <?php else: ?>
                        <?php foreach($forumList as $f): ?>
                        <tr>
                            <td style="font-size:0.85rem; color:var(--text-muted);"><?= date('d M Y H:i', strtotime($f['created_at'])) ?></td>
                            <td>
                                <strong><?= e($f['judul']) ?></strong>
                            </td>
                            <td><span class="badge badge-primary"><?= e($f['nama_kelas']) ?></span><br><small><?= e($f['nama_mapel']) ?></small></td>
                            <td><span class="badge badge-info"><?= $f['jml_balasan'] ?> Balasan</span></td>
                            <td>
                                <a href="?page=g_forum&id=<?= $f['id'] ?>" class="btn btn-success btn-sm"><i class='bx bx-message-dots'></i> Buka</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/modules/guru/forum_handler.php?action=delete&id=<?= $f['id'] ?>')"><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Buat Forum -->
    <div id="addForumModal" class="modal">
        <div class="modal-content" style="max-width:600px;">
            <h3 class="mb-4">Buat Topik Diskusi Baru</h3>
            <form action="<?= BASE_URL ?>/modules/guru/forum_handler.php?action=add" method="POST">
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
                    <label>Judul / Topik Diskusi</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Diskusi Materi Aljabar" required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi / Pertanyaan Pemantik</label>
                    <textarea name="deskripsi" class="form-control" rows="5" required></textarea>
                </div>
                
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" class="btn btn-outline" onclick="hideModal('addForumModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Forum</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
