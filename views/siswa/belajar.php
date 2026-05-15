<?php
/**
 * views/siswa/belajar.php
 */
Auth::requireRole('siswa');
$db = Database::getInstance();

$jm_id = $_GET['jm_id'] ?? 0;
$item_id = $_GET['item_id'] ?? 0;
$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

// === HANDLER PROGRESS & KUIS ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($action === 'tandai_selesai') {
        $id = $_POST['item_id'] ?? 0;
        $db->execute("INSERT IGNORE INTO progress_materi (item_id, siswa_id) VALUES (?, ?)", 'ii', [$id, $user_id]);
        
        // Cari item berikutnya
        $curr = $db->queryOne("SELECT m.urutan as m_urut, mi.urutan as i_urut FROM modul_item mi JOIN modul m ON m.id=mi.modul_id WHERE mi.id=?", 'i', [$id]);
        $next = $db->queryOne(
            "SELECT mi.id FROM modul_item mi JOIN modul m ON m.id=mi.modul_id 
             WHERE m.jadwal_mengajar_id=? AND (m.urutan > ? OR (m.urutan = ? AND mi.urutan > ?)) 
             ORDER BY m.urutan ASC, mi.urutan ASC LIMIT 1",
            'iiii', [$jm_id, $curr['m_urut'], $curr['m_urut'], $curr['i_urut']]
        );
        
        if ($next) {
            header("Location: " . BASE_URL . "/index.php?page=s_belajar&jm_id=$jm_id&item_id=" . $next['id']);
        } else {
            setFlash('success', 'Selamat! Anda telah menyelesaikan semua materi.');
            header("Location: " . BASE_URL . "/index.php?page=s_course");
        }
        exit;
    }
    elseif ($action === 'submit_kuis') {
        $id = $_POST['item_id'] ?? 0;
        $jawaban = $_POST['jawaban'] ?? []; // soal_id => opsi_id (untuk PG)
        $jawaban_essay = $_POST['jawaban_essay'] ?? []; // soal_id => text
        
        $soalList = $db->queryAll("SELECT * FROM kuis_soal WHERE item_id=?", 'i', [$id]);
        
        $total_pg_didapat = 0;
        
        foreach ($soalList as $s) {
            $soal_id = $s['id'];
            if ($s['tipe'] === 'pg') {
                $opsi_id = $jawaban[$soal_id] ?? 0;
                $is_benar = 0;
                $poin = 0;
                
                $cek = $db->queryOne("SELECT is_benar FROM kuis_opsi WHERE id=? AND soal_id=?", 'ii', [$opsi_id, $soal_id]);
                if ($cek && $cek['is_benar']) {
                    $is_benar = 1;
                    $poin = $s['poin_maksimal'];
                    $total_pg_didapat += $poin;
                }
                
                $db->execute("INSERT INTO kuis_jawaban (item_id, siswa_id, soal_id, opsi_id, poin_didapat, is_benar) VALUES (?, ?, ?, ?, ?, ?)", 'iiiiii', [$id, $user_id, $soal_id, $opsi_id, $poin, $is_benar]);
            } else {
                $teks = $jawaban_essay[$soal_id] ?? '';
                $db->execute("INSERT INTO kuis_jawaban (item_id, siswa_id, soal_id, jawaban_teks, poin_didapat, is_benar) VALUES (?, ?, ?, ?, 0, 0)", 'iiisi', [$id, $user_id, $soal_id, $teks]);
            }
        }
        
        // Simpan total sementara (hanya dari PG) ke kuis_hasil
        $db->execute("INSERT INTO kuis_hasil (item_id, siswa_id, skor) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE skor=VALUES(skor), diselesaikan_pada=NOW()", 'iid', [$id, $user_id, $total_pg_didapat]);
        
        setFlash('success', "Kuis selesai! Poin Pilihan Ganda Anda: " . round($total_pg_didapat) . ". (Poin Essay menunggu penilaian guru)");
        header("Location: " . BASE_URL . "/index.php?page=s_belajar&jm_id=$jm_id&item_id=$id");
        exit;
    }
}
// === END HANDLER ===

// Ambil struktur silabus
$modulList = $db->queryAll("SELECT * FROM modul WHERE jadwal_mengajar_id=? ORDER BY urutan ASC", 'i', [$jm_id]);
$silabus = [];
$first_item_id = 0;

foreach ($modulList as $m) {
    $items = $db->queryAll("SELECT * FROM modul_item WHERE modul_id=? ORDER BY urutan ASC", 'i', [$m['id']]);
    
    foreach ($items as &$it) {
        if ($it['tipe'] === 'materi') {
            $prog = $db->queryOne("SELECT id FROM progress_materi WHERE item_id=? AND siswa_id=?", 'ii', [$it['id'], $user_id]);
            $it['is_selesai'] = $prog ? true : false;
        } else {
            $prog = $db->queryOne("SELECT skor FROM kuis_hasil WHERE item_id=? AND siswa_id=?", 'ii', [$it['id'], $user_id]);
            $it['is_selesai'] = $prog ? true : false;
            $it['skor'] = $prog['skor'] ?? null;
        }
        if (!$first_item_id) $first_item_id = $it['id'];
    }
    unset($it); // FIX: destroy reference to avoid modifying the last element in subsequent loops
    
    $m['items'] = $items;
    $silabus[] = $m;
}

if (!$item_id && $first_item_id) {
    $item_id = $first_item_id;
}

$activeItem = null;
if ($item_id) {
    $activeItem = $db->queryOne("SELECT * FROM modul_item WHERE id=?", 'i', [$item_id]);
}

$pageTitle = 'E-Learning Interaktif';
// Kita akan buat layout kustom sedikit karena ini butuh split screen
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - E-Learning</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * { box-sizing: border-box; }
        body { margin:0; padding:0; height:100vh; overflow:hidden; background:#f0f4ff; display:flex; flex-direction:column; font-family: 'Inter', sans-serif; }

        /* ── Topbar ── */
        .topbar { background:#1a3a6b; height:60px; display:flex; align-items:center; padding:0 24px; justify-content:space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .topbar-left { display:flex; align-items:center; gap:14px; }
        .topbar-title { font-size:1.1rem; font-weight:700; color:white; margin:0; }
        .topbar-exit { display:flex; align-items:center; gap:6px; color:rgba(255,255,255,0.75); text-decoration:none; font-size:0.85rem; padding:7px 14px; border:1px solid rgba(255,255,255,0.25); border-radius:8px; transition:all 0.2s; }
        .topbar-exit:hover { background:rgba(255,255,255,0.15); color:white; }
        .topbar-user { display:flex; align-items:center; gap:8px; color:white; font-size:0.9rem; font-weight:500; }
        .user-avatar { width:34px; height:34px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.1rem; border: 2px solid rgba(255,255,255,0.3); }

        .main-container { display:flex; flex:1; overflow:hidden; }

        /* ── Sidebar ── */
        .sidebar-el { width:290px; background:white; border-right:1px solid #e2e8f0; overflow-y:auto; flex-shrink:0; }
        .sidebar-header { padding:16px 18px; background:#f8faff; border-bottom:1px solid #e2e8f0; }
        .sidebar-header p { margin:0; font-size:0.7rem; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; font-weight:600; }

        .modul-group { margin-bottom: 4px; }
        .modul-title { padding:10px 18px; font-weight:700; background:#f0f4ff; font-size:0.78rem; color:#1a3a6b; text-transform:uppercase; letter-spacing:.05em; border-bottom:1px solid #e2e8f0; }
        .item-link { display:flex; padding:11px 18px; text-decoration:none; color:#334155; border-bottom:1px solid #f1f5f9; transition:all 0.15s; align-items:center; gap:12px; }
        .item-link:hover { background:#f8faff; }
        .item-link.active { background:#eff6ff; border-left:4px solid #1a3a6b; padding-left:14px; }
        .item-link.active .item-title { color:#1a3a6b; font-weight:600; }
        .item-link .icon { font-size:1.3rem; color:#94a3b8; flex-shrink:0; }
        .item-link.done .icon { color:#16a34a; }
        .item-link.active .icon { color:#1a3a6b; }
        .item-title { font-size:0.88rem; line-height:1.3; }
        .item-sub { font-size:0.72rem; color:#94a3b8; margin-top:2px; }
        .item-link.done .item-sub { color:#16a34a; }

        /* ── Content area ── */
        .content-el { flex:1; overflow-y:auto; background:#fff; }
        .content-inner { max-width:800px; margin:0 auto; padding:40px 48px 60px; }

        /* ── Materi typography ── */
        .materi-content { line-height:1.85; font-size:1.02rem; color:#1e293b; }
        .materi-title { font-size:1.6rem; font-weight:800; color:#0f172a; border-bottom:3px solid #1a3a6b; padding-bottom:14px; margin-bottom:28px; line-height:1.3; }
        .materi-body img { max-width:100%; height:auto; border-radius:10px; margin:10px 0; box-shadow:0 2px 12px rgba(0,0,0,0.1); }
        .materi-body p { margin-bottom:1em; }

        /* ── Lampiran card ── */
        .lampiran-card { background:#f8faff; border:1px solid #dbeafe; border-radius:14px; padding:24px; margin:32px 0; }
        .lampiran-card h4 { font-size:0.85rem; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin:0 0 18px 0; display:flex; align-items:center; gap:6px; }
        .file-preview-img { width:100%; border-radius:10px; border:1px solid #e2e8f0; }
        .file-preview-pdf { width:100%; height:580px; border-radius:10px; border:1px solid #e2e8f0; }
        .file-download-box { display:flex; align-items:center; gap:20px; background:white; border:1px solid #e2e8f0; border-radius:12px; padding:20px 24px; }
        .file-download-icon { font-size:2.8rem; flex-shrink:0; }
        .file-download-info h5 { margin:0 0 4px 0; font-size:1rem; font-weight:700; }
        .file-download-info p { margin:0; font-size:0.82rem; color:#64748b; }
        .btn-download { display:inline-flex; align-items:center; gap:6px; padding:9px 20px; background:#1a3a6b; color:white; border-radius:8px; text-decoration:none; font-size:0.88rem; font-weight:600; transition:all 0.2s; flex-shrink:0; }
        .btn-download:hover { background:#102a52; transform:translateY(-1px); }

        /* ── Selesai button bar ── */
        .done-bar { position:sticky; bottom:0; background:white; border-top:1px solid #e2e8f0; padding:16px 48px; display:flex; justify-content:flex-end; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }
        .btn-selesai { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg, #16a34a, #15803d); color:white; border:none; border-radius:10px; font-size:1rem; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .btn-selesai:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(22,163,74,0.3); }

        /* ── Flash message ── */
        .flash-msg { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; border-radius:10px; padding:14px 20px; margin-bottom:24px; display:flex; align-items:center; gap:10px; font-size:0.9rem; }

        /* ── Kuis styles ── */
        .kuis-card { background:#f8faff; padding:22px; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:18px; }
        .kuis-question { font-size:1.05rem; font-weight:600; color:#0f172a; margin-bottom:16px; line-height:1.5; }
        .opsi-label { display:flex; align-items:center; gap:12px; padding:13px 18px; border:1.5px solid #e2e8f0; border-radius:8px; margin-bottom:9px; cursor:pointer; background:white; transition:all 0.15s; font-size:0.95rem; }
        .opsi-label:hover { border-color:#1a3a6b; background:#f0f4ff; }
        .opsi-label input[type=radio] { accent-color:#1a3a6b; transform:scale(1.3); flex-shrink:0; }
        .kuis-result { text-align:center; background:#f0fdf4; border:2px solid #bbf7d0; border-radius:16px; padding:40px; margin-bottom:30px; }
        .kuis-score { font-size:4rem; font-weight:900; color:#16a34a; line-height:1; margin:10px 0; }

        @media (max-width:700px) {
            .sidebar-el { display:none; }
            .content-inner { padding:24px 20px 50px; }
            .done-bar { padding:14px 20px; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <a href="?page=s_course" class="topbar-exit"><i class='bx bx-x'></i> Keluar Kelas</a>
        <h3 class="topbar-title">LMS Interaktif</h3>
    </div>
    <div class="topbar-user">
        <div class="user-avatar"><i class='bx bxs-user'></i></div>
        <?= e($_SESSION['nama']) ?>
    </div>
</div>

<div class="main-container">
    <!-- SIDEBAR SILABUS -->
    <div class="sidebar-el">
        <div class="sidebar-header"><p>Daftar Materi</p></div>
        <?php foreach($silabus as $m): ?>
            <div class="modul-group">
                <div class="modul-title">Bab <?= $m['urutan'] ?>: <?= e($m['judul']) ?></div>
                <?php foreach($m['items'] as $idx => $item): ?>
                    <a href="?page=s_belajar&jm_id=<?= $jm_id ?>&item_id=<?= $item['id'] ?>" class="item-link <?= $item_id == $item['id'] ? 'active' : '' ?> <?= $item['is_selesai'] ? 'done' : '' ?>">
                        <?php if($item['is_selesai']): ?>
                            <i class='bx bxs-check-circle icon'></i>
                        <?php elseif($item['tipe'] == 'materi'): ?>
                            <i class='bx bx-file-blank icon'></i>
                        <?php elseif($item['tipe'] == 'live_class'): ?>
                            <i class='bx bx-video icon'></i>
                        <?php else: ?>
                            <i class='bx bx-task icon'></i>
                        <?php endif; ?>
                        <div style="flex:1; min-width:0;">
                            <div class="item-title"><?= $idx+1 ?>. <?= e($item['judul']) ?></div>
                            <div class="item-sub">
                                <?php
                                if($item['tipe'] == 'materi') echo 'Materi Bacaan';
                                elseif($item['tipe'] == 'live_class') echo 'Live Class';
                                else echo 'Kuis ('.$item['durasi_menit'].' Mnt)';
                                ?>
                                <?php if($item['tipe']=='kuis' && $item['skor'] !== null): ?>
                                    &nbsp;·&nbsp;Skor Sementara: <?= round($item['skor']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content-el">
        <div class="content-inner">
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="flash-msg">
                <i class='bx bx-check-circle' style="font-size:1.3rem;"></i>
                <?= e($_SESSION['flash']['msg'] ?? '') ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if(!$activeItem): ?>
            <div style="text-align:center; padding:80px 0; color:#94a3b8;">
                <i class='bx bx-book-open' style="font-size:4rem; display:block; margin-bottom:16px;"></i>
                <h3 style="font-size:1.2rem; color:#64748b;">Pilih materi di sidebar untuk mulai belajar.</h3>
            </div>
        <?php else: ?>
            <div class="materi-content">
                <h1 class="materi-title"><?= e($activeItem['judul']) ?></h1>
                
                <?php if($activeItem['tipe'] === 'materi'): ?>
                    <!-- MATERI MODE -->
                    <div class="materi-body" style="margin-bottom:32px;">
                        <?= $activeItem['isi_teks'] ?>
                    </div>
                    
                    <?php if($activeItem['file_path']): ?>
                        <?php 
                        $ext         = strtolower(pathinfo($activeItem['file_path'], PATHINFO_EXTENSION));
                        $serveBase   = BASE_URL . '/modules/file_server.php?type=materi&file=' . urlencode($activeItem['file_path']);
                        $serveInline   = $serveBase . '&inline=1';
                        $serveDownload = $serveBase . '&inline=0';
                        $isImage  = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                        $isPdf    = ($ext === 'pdf');
                        $isWord   = in_array($ext, ['doc','docx']);
                        $isExcel  = in_array($ext, ['xls','xlsx']);
                        $isPpt    = in_array($ext, ['ppt','pptx']);
                        $iconMap  = ['pdf'=>['bxs-file-pdf','#dc2626'],'doc'=>['bxs-file-doc','#2563eb'],'docx'=>['bxs-file-doc','#2563eb'],'ppt'=>['bxs-slideshow','#e97316'],'pptx'=>['bxs-slideshow','#e97316'],'xls'=>['bxs-spreadsheet','#16a34a'],'xlsx'=>['bxs-spreadsheet','#16a34a']];
                        [$iconClass, $iconColor] = $iconMap[$ext] ?? ['bxs-file','#64748b'];
                        ?>
                        <div class="lampiran-card">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                                <h4 style="margin:0;"><i class='bx bx-paperclip'></i> Lampiran Materi</h4>
                                <a href="<?= $serveDownload ?>" class="btn-download" style="font-size:0.8rem; padding:7px 14px;"><i class='bx bx-download'></i> Download</a>
                            </div>
                            
                            <?php if($isImage): ?>
                                <img src="<?= $serveInline ?>" alt="Lampiran gambar" class="file-preview-img">

                            <?php elseif($isPdf): ?>
                                <iframe src="<?= $serveInline ?>" class="file-preview-pdf"></iframe>

                            <?php elseif($isWord): ?>
                                <!-- Render DOCX langsung menggunakan mammoth.js -->
                                <div id="docx-loading" style="text-align:center; padding:40px; color:#64748b;">
                                    <i class='bx bx-loader-alt bx-spin' style="font-size:2.5rem; display:block; margin-bottom:12px;"></i>
                                    Memuat dokumen Word...
                                </div>
                                <div id="docx-content" style="background:white; border:1px solid #e2e8f0; border-radius:10px; padding:30px 40px; min-height:300px; line-height:1.8; display:none;"></div>
                                <div id="docx-error" style="display:none; text-align:center; padding:30px; background:#fff7ed; border:1px solid #fed7aa; border-radius:10px;">
                                    <i class='bx bxs-error' style="font-size:2rem; color:#ea580c; display:block; margin-bottom:8px;"></i>
                                    <p style="margin:0; color:#9a3412;">Gagal memuat pratinjau. Silakan download dokumennya.</p>
                                </div>
                                <script>
                                (function() {
                                    const fileUrl = '<?= $serveInline ?>';
                                    function loadMammoth() {
                                        fetch(fileUrl)
                                            .then(r => r.arrayBuffer())
                                            .then(buf => mammoth.convertToHtml({arrayBuffer: buf}))
                                            .then(result => {
                                                document.getElementById('docx-loading').style.display = 'none';
                                                const el = document.getElementById('docx-content');
                                                el.innerHTML = result.value;
                                                el.style.display = 'block';
                                            })
                                            .catch(() => {
                                                document.getElementById('docx-loading').style.display = 'none';
                                                document.getElementById('docx-error').style.display = 'block';
                                            });
                                    }
                                    if (window.mammoth) {
                                        loadMammoth();
                                    } else {
                                        const s = document.createElement('script');
                                        s.src = 'https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js';
                                        s.onload = loadMammoth;
                                        s.onerror = () => {
                                            document.getElementById('docx-loading').style.display = 'none';
                                            document.getElementById('docx-error').style.display = 'block';
                                        };
                                        document.head.appendChild(s);
                                    }
                                })();
                                </script>

                            <?php elseif($isExcel): ?>
                                <!-- Render Excel menggunakan SheetJS -->
                                <div id="xlsx-loading" style="text-align:center; padding:40px; color:#64748b;">
                                    <i class='bx bx-loader-alt bx-spin' style="font-size:2.5rem; display:block; margin-bottom:12px;"></i>
                                    Memuat spreadsheet...
                                </div>
                                <div id="xlsx-content" style="overflow-x:auto; display:none;"></div>
                                <div id="xlsx-error" style="display:none; text-align:center; padding:30px; background:#fff7ed; border:1px solid #fed7aa; border-radius:10px;">
                                    <i class='bx bxs-error' style="font-size:2rem; color:#ea580c; display:block; margin-bottom:8px;"></i>
                                    <p style="margin:0; color:#9a3412;">Gagal memuat pratinjau. Silakan download filenya.</p>
                                </div>
                                <style>
                                    #xlsx-content table { border-collapse:collapse; width:100%; font-size:0.88rem; }
                                    #xlsx-content th, #xlsx-content td { border:1px solid #e2e8f0; padding:8px 12px; white-space:nowrap; }
                                    #xlsx-content th { background:#f0f4ff; font-weight:700; color:#1a3a6b; }
                                    #xlsx-content tr:hover td { background:#f8faff; }
                                </style>
                                <script>
                                (function() {
                                    const fileUrl = '<?= $serveInline ?>';
                                    function loadXlsx() {
                                        fetch(fileUrl)
                                            .then(r => r.arrayBuffer())
                                            .then(buf => {
                                                const wb  = XLSX.read(buf, {type:'array'});
                                                const ws  = wb.Sheets[wb.SheetNames[0]];
                                                const html = XLSX.utils.sheet_to_html(ws, {editable:false});
                                                document.getElementById('xlsx-loading').style.display = 'none';
                                                const el = document.getElementById('xlsx-content');
                                                el.innerHTML = html;
                                                el.style.display = 'block';
                                            })
                                            .catch(() => {
                                                document.getElementById('xlsx-loading').style.display = 'none';
                                                document.getElementById('xlsx-error').style.display = 'block';
                                            });
                                    }
                                    if (window.XLSX) {
                                        loadXlsx();
                                    } else {
                                        const s = document.createElement('script');
                                        s.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
                                        s.onload = loadXlsx;
                                        s.onerror = () => {
                                            document.getElementById('xlsx-loading').style.display = 'none';
                                            document.getElementById('xlsx-error').style.display = 'block';
                                        };
                                        document.head.appendChild(s);
                                    }
                                })();
                                </script>

                            <?php else: ?>
                                <!-- PPT / file lain: tampilkan info dan tombol download -->
                                <div style="display:flex; align-items:center; gap:20px; background:white; border:1px solid #e2e8f0; border-radius:12px; padding:20px 24px;">
                                    <i class='bx <?= $iconClass ?>' style="font-size:3rem; color:<?= $iconColor ?>; flex-shrink:0;"></i>
                                    <div>
                                        <div style="font-weight:700; font-size:1rem;"><?= strtoupper($ext) ?> File</div>
                                        <div style="font-size:0.82rem; color:#64748b; margin-top:4px;">Tipe file ini tidak dapat ditampilkan langsung di browser. Silakan download untuk membukanya.</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                
        </div><!-- /.content-inner -->
                    <div class="done-bar">
                        <form action="?page=s_belajar&action=tandai_selesai&jm_id=<?= $jm_id ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="item_id" value="<?= $activeItem['id'] ?>">
                            <button type="submit" class="btn-selesai"><i class='bx bx-check-double'></i> Tandai Selesai & Lanjut</button>
                        </form>
                    </div>

                <?php elseif($activeItem['tipe'] === 'live_class'): ?>
                    <!-- LIVE CLASS MODE -->
                    <div style="margin-bottom:24px; padding:30px; background:#f8faff; border:2px dashed #bcdcff; border-radius:12px; text-align:center;">
                        <i class='bx bx-broadcast' style="font-size:4rem; color:#ef4444; margin-bottom:15px; display:block;"></i>
                        <h2 style="font-size:1.4rem; color:#0f172a; margin-bottom:8px;">Live Class (Virtual Meeting)</h2>
                        <p style="color:#64748b; font-size:1.05rem; margin-bottom:25px;">Jadwal: <strong><?= date('d M Y, H:i', strtotime($activeItem['isi_teks'])) ?> WIB</strong></p>
                        
                        <a href="<?= e($activeItem['file_path']) ?>" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:14px 30px; background:#ef4444; color:white; font-weight:700; font-size:1.1rem; text-decoration:none; border-radius:10px; box-shadow:0 4px 15px rgba(239, 68, 68, 0.3); transition:all 0.2s;">
                            <i class='bx bx-video'></i> Gabung ke Ruang Virtual
                        </a>
                    </div>
                    
                    <div class="done-bar">
                        <form action="?page=s_belajar&action=tandai_selesai&jm_id=<?= $jm_id ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="item_id" value="<?= $activeItem['id'] ?>">
                            <button type="submit" class="btn-selesai"><i class='bx bx-check-double'></i> Tandai Selesai & Lanjut</button>
                        </form>
                    </div>

                <?php else: ?>
                    <!-- KUIS MODE -->
                    <?php if($activeItem['isi_teks']): ?>
                    <div style="margin-bottom:24px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px;">
                        <p style="margin:0; color:#92400e;"><?= nl2br(e($activeItem['isi_teks'])) ?></p>
                    </div>
                    <?php endif; ?>
                    <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; background:#f1f5f9; border-radius:8px; font-size:0.88rem; color:#475569; margin-bottom:28px;">
                        <i class='bx bx-time-five'></i> Durasi: <strong><?= $activeItem['durasi_menit'] ?> Menit</strong>
                    </div>
                    
                    <?php
                    $hasil = $db->queryOne("SELECT * FROM kuis_hasil WHERE item_id=? AND siswa_id=?", 'ii', [$activeItem['id'], $user_id]);
                    ?>
                    
                    <?php if($hasil): ?>
                        <div class="kuis-result">
                            <div style="font-size:1rem; color:#166534; font-weight:600; margin-bottom:8px;">🎉 Kuis Telah Diselesaikan!</div>
                            <div class="kuis-score"><?= round($hasil['skor']) ?></div>
                            <div style="font-size:0.88rem; color:#64748b; margin-top:8px;">Dikerjakan pada <?= date('d M Y, H:i', strtotime($hasil['diselesaikan_pada'])) ?></div>
                            
                            <?php
                            $curr = $db->queryOne("SELECT m.urutan as m_urut, mi.urutan as i_urut FROM modul_item mi JOIN modul m ON m.id=mi.modul_id WHERE mi.id=?", 'i', [$activeItem['id']]);
                            $next = $db->queryOne(
                                "SELECT mi.id FROM modul_item mi JOIN modul m ON m.id=mi.modul_id 
                                 WHERE m.jadwal_mengajar_id=? AND (m.urutan > ? OR (m.urutan = ? AND mi.urutan > ?)) 
                                 ORDER BY m.urutan ASC, mi.urutan ASC LIMIT 1",
                                'iiii', [$jm_id, $curr['m_urut'], $curr['m_urut'], $curr['i_urut']]
                            );
                            ?>
                            <div style="margin-top:24px;">
                            <?php if($next): ?>
                                <a href="?page=s_belajar&jm_id=<?= $jm_id ?>&item_id=<?= $next['id'] ?>" class="btn-selesai" style="text-decoration:none;">Lanjut ke Modul Berikutnya <i class='bx bx-right-arrow-alt'></i></a>
                            <?php else: ?>
                                <a href="?page=s_course" style="display:inline-flex; align-items:center; gap:6px; padding:11px 24px; border:1.5px solid #1a3a6b; color:#1a3a6b; border-radius:10px; text-decoration:none; font-weight:600; font-size:0.95rem;">Kembali ke Daftar Mapel</a>
                            <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php
                        $soalList = $db->queryAll("SELECT * FROM kuis_soal WHERE item_id=? ORDER BY RAND()", 'i', [$activeItem['id']]);
                        ?>
                        <form action="?page=s_belajar&action=submit_kuis&jm_id=<?= $jm_id ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="item_id" value="<?= $activeItem['id'] ?>">
                            
                            <?php foreach($soalList as $idx => $s): ?>
                                <div class="kuis-card">
                                    <div class="kuis-question">
                                        <?= $idx+1 ?>. <?= nl2br(e($s['pertanyaan'])) ?>
                                        <div style="font-size:0.8rem; font-weight:normal; color:#e67e22; float:right;">Poin Maks: <?= $s['poin_maksimal'] ?></div>
                                    </div>
                                    
                                    <?php if($s['tipe'] === 'pg'): ?>
                                        <?php $opsi = $db->queryAll("SELECT * FROM kuis_opsi WHERE soal_id=? ORDER BY RAND()", 'i', [$s['id']]); ?>
                                        <?php foreach($opsi as $o): ?>
                                        <label class="opsi-label">
                                            <input type="radio" name="jawaban[<?= $s['id'] ?>]" value="<?= $o['id'] ?>" required>
                                            <?= e($o['teks']) ?>
                                        </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <textarea name="jawaban_essay[<?= $s['id'] ?>]" class="form-control" rows="4" placeholder="Ketik jawaban essay Anda di sini..." style="width:100%; padding:15px; border-radius:8px; border:1px solid #cbd5e1; font-family:inherit; resize:vertical;" required></textarea>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <div style="text-align:center; margin:30px 0 60px;">
                                <button type="submit" class="btn-selesai" style="font-size:1.05rem; padding:14px 36px;" onclick="return confirm('Yakin ingin mengumpulkan jawaban kuis ini?')">
                                    <i class='bx bx-send'></i> Kumpulkan & Lihat Skor
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                <?php endif; ?>
            </div><!-- /.materi-content -->
        <?php endif; ?>
    </div><!-- /.content-el -->
</div><!-- /.main-container -->

</body>
</html>

