<?php
session_start();
include "database.php";
require_once "db_migrate_live_class_kuis.php";

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
$role = $_SESSION['role'];
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// --- LOGIC OTOMATIS: MENGHITUNG DATA DARI TABEL ADMINISTRASI ---
$query_siswa = mysqli_query($conn, "SELECT * FROM daftar_siswa");
$jml_siswa   = mysqli_num_rows($query_siswa);

$query_guru  = mysqli_query($conn, "SELECT * FROM daftar_guru");
$jml_guru    = mysqli_num_rows($query_guru);

$query_mapel = mysqli_query($conn, "SELECT * FROM mapel");
$jml_mapel   = mysqli_num_rows($query_mapel);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard E-Learning</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            /* Base Akademik Pro Colors (Light Mode) */
            --main: #000080; /* Deep blue */
            --second: #4338CA;
            --bg: #F8F9FA;
            --text: #111827;
            --text-muted: #6B7280;
            --card-bg: #FFFFFF;
            --sidebar-bg: #FFFFFF;
            --border: #E5E7EB;
            
            /* Status/Badges */
            --success-bg: #D1FAE5;
            --success-text: #065F46;
            --danger-bg: #FEE2E2;
            --danger-text: #991B1B;
            --warning-bg: #FEF3C7;
            --warning-text: #92400E;
            --neutral-bg: #F3F4F6;
            --neutral-text: #374151;
            
            /* Button Colors */
            --btn-main: var(--main);
            --btn-add: #10B981;
            --btn-edit: #3B82F6;
            --btn-delete: #EF4444;
        }
        body.dark {
            --bg: #111827;
            --sidebar-bg: #1F2937;
            --card-bg: #1F2937;
            --text: #F9FAFB;
            --text-muted: #9CA3AF;
            --border: #374151;
            
            --success-bg: rgba(16, 185, 129, 0.2);
            --success-text: #34D399;
            --danger-bg: rgba(239, 68, 68, 0.2);
            --danger-text: #F87171;
            --warning-bg: rgba(245, 158, 11, 0.2);
            --warning-text: #FBBF24;
            --neutral-bg: rgba(156, 163, 175, 0.2);
            --neutral-text: #D1D5DB;
        }
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Poppins', sans-serif; }
        body { display:flex; background: var(--bg); color: var(--text); transition: 0.3s; }

        /* SIDEBAR STYLE */
        .sidebar {
            width: 250px; height: 100vh; background: var(--sidebar-bg);
            color: var(--text); padding: 20px; position: fixed; transition: 0.3s; z-index: 1000;
            overflow-y: auto; border-right: 1px solid var(--border);
        }
        .sidebar.hide { width: 70px; }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size: 24px; white-space: nowrap; color: var(--main); font-weight: 700; }
        .sidebar.hide h2 { display: none; }
        .sidebar a { display:flex; align-items:center; padding:12px; margin:8px 0; border-radius:8px; color: var(--text-muted); text-decoration:none; transition:0.3s; font-weight: 500; font-size: 14px; }
        .sidebar a:hover { background: var(--neutral-bg); color: var(--text); }
        .sidebar a.active { background: var(--neutral-bg); color: var(--main); border-left: 4px solid var(--main); border-radius: 4px 8px 8px 4px; padding-left: 8px; font-weight: 600; }
        .sidebar i { margin-right:10px; font-size: 20px; }
        .sidebar.hide i { margin-right: 0; }
        .sidebar.hide span { display: none; }

        /* CONTENT AREA */
        .content { margin-left:250px; padding:30px; width:100%; transition:0.3s; }
        .sidebar.hide ~ .content { margin-left:70px; }

        /* HEADER & BADGE */
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
        .badge { padding:4px 12px; border-radius:9999px; font-size:12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; }
        .admin { background: var(--danger-bg); color: var(--danger-text); }
        .guru { background: var(--success-bg); color: var(--success-text); }
        .siswa { background: var(--neutral-bg); color: var(--neutral-text); }

        /* STATISTIC CARDS */
        .cards { display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px; }
        .card-link { text-decoration: none; color: inherit; display: block; }
        .card-stat {
            background: var(--card-bg); padding: 25px; border-radius: 12px; color: var(--text);
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid var(--border);
        }
        .card-stat:hover { transform: translateY(-3px); cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-stat h3 { font-size: 32px; margin-bottom: 5px; color: var(--text); }
        .card-stat p { font-size: 14px; color: var(--text-muted); font-weight: 500; margin:0; }
        .card-stat i { font-size: 40px; opacity: 1; }

        .bg-orange { background: var(--card-bg); color: var(--text); }
        .bg-orange i { color: #F59E0B; }
        .bg-pink { background: var(--card-bg); color: var(--text); }
        .bg-pink i { color: #EC4899; }
        .bg-purple { background: var(--card-bg); color: var(--text); }
        .bg-purple i { color: #8B5CF6; }

        .card { background: var(--card-bg); padding: 25px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); color: var(--text); border: 1px solid var(--border); }

        .alert-pilih-kelas {
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.alert-pilih-kelas p {
  color: #4a5568;
  font-size: 15px;
  line-height: 1.8;
  margin: 0;
}

.highlight-merah {
  background-color: #f56565; /* Warna latar merah/oranye seperti di gambar */
  color: white; 
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 500;
}

.btn-pilih {
  background-color: #c53030; /* Warna tombol merah kecoklatan */
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
  margin-left: 8px;
  vertical-align: middle;
}

.btn-pilih:hover {
  background-color: #9b2c2c; /* Efek saat tombol disorot mouse */
}
        
        /* FORM & TABLE STYLING */
        input, select { padding: 10px 14px; margin: 5px 0; border-radius: 8px; border: 1px solid var(--border); width: 100%; max-width: 300px; background: var(--card-bg); color: var(--text); }
        input:focus, select:focus { outline: none; border-color: var(--main); }
        table { width:100%; border-collapse:collapse; margin-top: 15px; font-size: 14px; }
        table th { padding:14px 12px; text-align: left; border-bottom: 2px solid var(--border); color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; }
        table td { padding:14px 12px; text-align: left; border-bottom: 1px solid var(--border); color: var(--text); }
        body.dark table td, body.dark table th { border-color: var(--border); }

        .btn { display: inline-block; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; text-decoration: none; font-size: 14px; transition: 0.2s; font-weight: 500; text-align: center; }
        .btn-main { background: var(--btn-main); color:white; }
        .btn-add { background: var(--btn-add); color:white; }
        .btn-edit { background: var(--btn-edit); color:white; }
        .btn-delete { background: var(--btn-delete); color:white; }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        
        .dark-toggle { cursor:pointer; font-size:24px; margin-left: 15px; }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h2>E-Learn</h2>
    <a href="dashboard.php" class="<?= $page == 'dashboard' ? 'active' : '' ?>"><i class='bx bxs-dashboard'></i> <span>Dashboard</span></a>
    <?php if ($role == 'admin') { ?>
        <hr style="opacity: 0.2; margin: 15px 0;">
        <a href="dashboard.php?page=user" class="<?= $page == 'user' ? 'active' : '' ?>"><i class='bx bxs-user'></i> <span>User Akun</span></a>
        <a href="dashboard.php?page=kelas" class="<?= $page == 'kelas' ? 'active' : '' ?>"><i class='bx bxs-school'></i> <span>Kelas</span></a>
        <a href="dashboard.php?page=mapel" class="<?= $page == 'mapel' ? 'active' : '' ?>"><i class='bx bxs-book-open'></i> <span>Mapel</span></a>
        <a href="dashboard.php?page=jadwal" class="<?= $page == 'jadwal' ? 'active' : '' ?>"><i class='bx bxs-calendar'></i> <span>Jadwal</span></a>
        <hr style="opacity: 0.2; margin: 15px 0;">
        <a href="dashboard.php?page=pengumuman" class="<?= $page == 'pengumuman' ? 'active' : '' ?>"><i class='bx bxs-megaphone'></i> <span>Pengumuman</span></a>
        <a href="dashboard.php?page=absensi" class="<?= $page == 'absensi' ? 'active' : '' ?>"><i class='bx bxs-check-square'></i> <span>Absensi Siswa</span></a>
        <a href="dashboard.php?page=kalender" class="<?= $page == 'kalender' ? 'active' : '' ?>"><i class='bx bxs-calendar-event'></i> <span>Kalender Kegiatan</span></a>
        <a href="dashboard.php?page=laporan" class="<?= $page == 'laporan' ? 'active' : '' ?>"><i class='bx bxs-report'></i> <span>Laporan</span></a>
  <?php } elseif ($role == 'guru') { 
        // Tangkap ID kelas jika guru sedang berada di dalam ruang kelas
        $id_kelas_url = isset($_GET['id_kelas']) ? "&id_kelas=" . $_GET['id_kelas'] : "";
    ?>
        <hr style="opacity: 0.2; margin: 15px 0;">
        <a href="dashboard.php?page=profil_guru" class="<?= $page == 'profil_guru' ? 'active' : '' ?>"><i class='bx bxs-user-circle'></i> <span>Profil Saya</span></a>
        
        <!-- Menu di bawah ini otomatis membawa ID Kelas -->
        <a href="dashboard.php?page=materi_guru<?= $id_kelas_url ?>" class="<?= $page == 'materi_guru' ? 'active' : '' ?>"><i class='bx bxs-file-blank'></i> <span>Kelola Materi</span></a>
        <a href="dashboard.php?page=tugas_guru<?= $id_kelas_url ?>" class="<?= $page == 'tugas_guru' ? 'active' : '' ?>"><i class='bx bx-task'></i> <span>Tugas & Penilaian</span></a>
        <a href="dashboard.php?page=quiz_guru<?= $id_kelas_url ?>" class="<?= $page == 'quiz_guru' ? 'active' : '' ?>"><i class='bx bxs-edit'></i> <span>Buat Kuis</span></a>
        <a href="dashboard.php?page=live_class_guru<?= $id_kelas_url ?>" class="<?= $page == 'live_class_guru' ? 'active' : '' ?>"><i class='bx bxs-video'></i> <span>Live Class</span></a>
        <a href="dashboard.php?page=forum_guru<?= $id_kelas_url ?>" class="<?= $page == 'forum_guru' ? 'active' : '' ?>"><i class='bx bxs-chat'></i> <span>Forum Diskusi</span></a>
       <!-- Rekap nilai per kelas -->
        <a href="dashboard.php?page=nilai_guru<?= $id_kelas_url ?>" class="<?= $page == 'nilai_guru' ? 'active' : '' ?>"><i class='bx bxs-bar-chart-alt-2'></i> <span>Rekap Nilai</span></a>
   <?php 
    } elseif ($role == 'siswa') { 
        // Tangkap ID kelas jika siswa sedang berada di dalam ruang kelas
        $id_kelas_url = isset($_GET['id_kelas']) ? "&id_kelas=" . $_GET['id_kelas'] : "";
    ?>
        <hr style="opacity: 0.2; margin: 15px 0;">
        
        <!-- Menu ini otomatis menyesuaikan: akan mengarah ke kelas yang sedang dibuka -->
        <a href="dashboard.php?page=jadwal_siswa<?= $id_kelas_url ?>" class="<?= $page == 'jadwal_siswa' ? 'active' : '' ?>"><i class='bx bxs-calendar'></i> <span>Jadwal Saya</span></a>
        <a href="dashboard.php?page=materi_siswa<?= $id_kelas_url ?>" class="<?= $page == 'materi_siswa' ? 'active' : '' ?>"><i class='bx bxs-file-blank'></i> <span>Materi Pelajaran</span></a>
        <a href="dashboard.php?page=tugas_siswa<?= $id_kelas_url ?>" class="<?= $page == 'tugas_siswa' ? 'active' : '' ?>"><i class='bx bx-task'></i> <span>Tugas Kelas</span></a>
        <a href="dashboard.php?page=quiz_siswa<?= $id_kelas_url ?>" class="<?= $page == 'quiz_siswa' ? 'active' : '' ?>"><i class='bx bxs-edit'></i> <span>Kuis Online</span></a>
        <a href="dashboard.php?page=live_class_siswa<?= $id_kelas_url ?>" class="<?= $page == 'live_class_siswa' ? 'active' : '' ?>"><i class='bx bxs-video'></i> <span>Live Class</span></a>
        <a href="dashboard.php?page=forum_siswa<?= $id_kelas_url ?>" class="<?= $page == 'forum_siswa' ? 'active' : '' ?>"><i class='bx bxs-chat'></i> <span>Forum Diskusi</span></a>
        
        <!-- Nilai dibiarkan tanpa ID kelas karena biasanya rekap nilai itu global -->
        <a href="dashboard.php?page=nilai_siswa" class="<?= $page == 'nilai_siswa' ? 'active' : '' ?>"><i class='bx bxs-star'></i> <span>Nilai Saya</span></a>
    <?php } ?>
    <a href="logout.php"><i class='bx bx-log-out'></i> <span>Logout</span></a>
</div>

<div class="content">
    <div class="header">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class='bx bx-menu' style="font-size: 24px; cursor:pointer;"></i></span>
        <div style="display: flex; align-items: center; gap:10px;">
            <?php
            // Notification bell (all roles)
            $notif_count = 0;
            if(isset($_SESSION['id'])) {
                $nr = mysqli_query($conn,"SELECT COUNT(*) as c FROM notifikasi WHERE user_id='{$_SESSION['id']}' AND dibaca=0");
                $notif_count = mysqli_fetch_assoc($nr)['c'] ?? 0;
            }
            ?>
            <div style="position:relative;cursor:pointer;" onclick="toggleNotif()">
                <i class='bx bxs-bell' style="font-size:22px;"></i>
                <?php if($notif_count > 0): ?>
                <span style="position:absolute;top:-5px;right:-6px;background:#e74c3c;color:white;border-radius:50%;width:16px;height:16px;font-size:10px;display:flex;align-items:center;justify-content:center;"><?=$notif_count?></span>
                <?php endif; ?>
            </div>
            <span class="badge <?= $role; ?>"><?= $role; ?></span>
            <span class="dark-toggle" onclick="toggleDark()"><i class='bx bx-moon'></i></span>
        </div>
    </div>

    <!-- Notification Dropdown -->
    <div id="notifDropdown" style="display:none;position:fixed;top:65px;right:20px;width:320px;background:var(--card-bg);border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,0.15);z-index:999;padding:15px;max-height:350px;overflow-y:auto;">
    <h4 style="margin-bottom:10px;border-bottom:1px solid #eee;padding-bottom:8px;">🔔 Notifikasi</h4>
    <?php
    if(isset($_SESSION['id'])) {
        $nq = mysqli_query($conn,"SELECT * FROM notifikasi WHERE user_id='{$_SESSION['id']}' ORDER BY created_at DESC LIMIT 10");
        if(mysqli_num_rows($nq) == 0) {
            echo "<p style='color:#aaa;text-align:center;'>Tidak ada notifikasi</p>";
        } else {
            while($n = mysqli_fetch_assoc($nq)) {
                $id_notif = $n['id'] ?? 0;
                $pesan = (string)($n['pesan'] ?? '');
                $bg = ($n['dibaca'] ?? 0) ? '#f8f8f8' : 'rgba(106,17,203,0.05)';
                $id_p = $n['id_pengumuman'] ?? null;
                $final_link = $n['link'] ?? 'dashboard.php';
            
                // Jika ini pengumuman, arahkan ke halaman lihat (bukan input admin)
                if (strpos($pesan, 'Pengumuman') !== false && $id_p !== null) {
                    $final_link = "dashboard.php?page=lihat_pengumuman&id=" . $id_p;
                }

               $id_p = isset($n['id_pengumuman']) ? $n['id_pengumuman'] : null;
if (strpos((string)$n['pesan'], 'Pengumuman') !== false) {
    $link_tujuan = "dashboard.php?page=lihat_pengumuman&id=" . ($n['id_pengumuman'] ?? 0);
} else {
    $link_tujuan = $n['link'];
}

echo "<a href='$link_tujuan' onclick='markRead(" . $n['id'] . ")' style='display:block;padding:10px;border-radius:8px;margin-bottom:6px;background:$bg;color:var(--text);text-decoration:none;font-size:14px;'>
                $pesan<br>
                <small style='color:#aaa;'>" . ($n['created_at'] ?? '') . "</small>
              </a>";

            } 
        } 
    }
?>
</div>
            
    <?php 
    if ($page == 'dashboard') { 
        // --- LOGIKA TAMPILAN DASHBOARD PER ROLE[cite: 1] ---
        if ($role == 'admin') { 
    ?>
        <div class="cards">
            <a href="dashboard.php?page=daftar_siswa_lengkap" class="card-link">
                <div class="card-stat bg-orange">
                    <div><h3><?= $jml_siswa; ?></h3><p>Total Siswa</p></div>
                    <i class='bx bxs-graduation'></i>
                </div>
            </a>
            <a href="dashboard.php?page=user" class="card-link">
                <div class="card-stat bg-pink">
                    <div><h3><?= $jml_guru; ?></h3><p>Total Guru</p></div>
                    <i class='bx bxs-briefcase'></i>
                </div>
            </a>
            <div class="card-stat bg-purple">
                <div><h3><?= $jml_mapel; ?></h3><p>Mata Pelajaran</p></div>
                <i class='bx bxs-book'></i>
            </div>
        </div>
  <?php 
        } elseif ($role == 'guru') { 
    ?>
        <div style="margin-bottom: 25px;">
            <h2 style="color: var(--text);">Halo, Pak/Bu <?= $_SESSION['nama'] ?? 'Guru'; ?>! 👋</h2>
            <p style="color: #777;">Silakan pilih ruang kelas yang ingin Anda kelola hari ini.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
            <?php 
            // Mengambil data kelas buatan admin dari database
            $query_kelas_guru = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
            
            $colors = [
                'linear-gradient(135deg, #E6D0FF, #F3E8FF)', // Pastel Purple
                'linear-gradient(135deg, #FFD1D1, #FFEBEB)', // Pastel Red/Pink
                'linear-gradient(135deg, #D4EDDA, #E8F5E9)', // Pastel Green
                'linear-gradient(135deg, #FFE5B4, #FFF3E0)', // Pastel Orange
                'linear-gradient(135deg, #D1ECF1, #E3F2FD)'  // Pastel Blue
            ];
            $color_index = 0;

            if(mysqli_num_rows($query_kelas_guru) > 0) {
                while($k = mysqli_fetch_assoc($query_kelas_guru)) { 
                    $bg_color = $colors[$color_index % count($colors)];
                    $color_index++;
            ?>
            <!-- Mengarah ke menu ruang kelas Guru -->
            <a href="dashboard.php?page=menu_kelas_guru&id_kelas=<?= $k['id']; ?>" style="text-decoration: none;">
                <div style="background: <?= $bg_color; ?>; padding: 30px; border-radius: 20px; color: var(--text); transition: 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02);">
                    <i class='bx bxs-door-open' style="float: right; font-size: 50px; opacity: 0.2;"></i>
                    <h3 style="font-size: 24px; margin:0;"><?= $k['nama_kelas']; ?></h3>
                </div>
            </a>
            <?php 
                }
            } else {
                echo "<div style='grid-column: 1 / -1; background: white; padding: 20px; border-radius: 10px; text-align: center; color: #888; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>Belum ada kelas yang dibuat oleh Admin.</div>";
            }
            ?>
        </div>
   <?php 
        } elseif ($role == 'siswa') { 
            // 1. CEK APAKAH SISWA SUDAH MEMILIH KELAS
            // Menangkap id_kelas dari URL (misal: dashboard.php?id_kelas=1)
            $id_kelas_terpilih = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;
    ?>
        <!-- CSS TAMPILAN BARU -->
        <style>
            .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
            .user-profile { display: flex; align-items: center; gap: 15px; }
            .user-profile img { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid #7367f0; }
            
            .class-selector form { display: flex; align-items: center; gap: 10px; background: white; padding: 10px 15px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
            .class-selector label { font-size: 14px; color: #f56565; font-weight: 600; margin: 0; }
            .class-selector select { padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e0; font-weight: 500; color: #4a5568; outline: none; cursor: pointer; }
            
            .quick-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
            .stat-card { background: var(--card-bg); padding: 20px; border-radius: 12px; color: var(--text); display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: 0.2s; border: 1px solid var(--border); }
            .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
            .stat-card h4 { margin: 0; font-size: 14px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
            .stat-card span { font-size: 28px; font-weight: 700; margin-top: 8px; color: var(--text); }
            
            .stat-merah { border-top: 4px solid var(--danger-text); }
            .stat-biru { border-top: 4px solid var(--btn-edit); }
            .stat-kuning { border-top: 4px solid var(--warning-text); }
            
            body.dark .stat-merah, body.dark .stat-biru, body.dark .stat-kuning { background: var(--card-bg); color: var(--text); }

            /* CSS Khusus Kartu Tugas */
            .task-card { background: var(--card-bg); padding: 20px; border-radius: 12px; border: 1px solid var(--border); border-left: 4px solid var(--danger-text); box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;}
            .task-card h4 { margin: 0 0 10px 0; color: var(--text); font-size: 16px; font-weight: 600;}
            .task-card p { margin: 0 0 15px 0; font-size: 13px; color: var(--text-muted); }
            .btn-kerjakan { display: inline-block; background: var(--main); color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; text-align: center; transition: 0.2s;}
            .btn-kerjakan:hover { opacity: 0.9; transform: translateY(-1px); color: white; }
        </style>

        <!-- HEADER & PROFIL -->
        <div class="dashboard-header">
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama'] ?? 'Siswa') ?>&background=b5a0dd&color=fff&bold=true" alt="Profil">
                <div>
                    <h2 style="margin: 0; color: var(--text); font-size: 24px;">Halo, <?= $_SESSION['nama'] ?? 'Siswa'; ?>! 👋</h2>
                    <p style="margin: 0; color: #718096; font-size: 14px;">Siap untuk belajar hal baru hari ini?</p>
                </div>
            </div>
            
            <div class="class-selector">
                <?php if(!$id_kelas_terpilih): ?>
                    <!-- Form Pilih Kelas (Hanya muncul jika belum pilih kelas) -->
                    <!-- action="" artinya form dikirim ke halaman dashboard ini sendiri -->
                    <form action="" method="GET">
                        <label>Anda Belum Memilih Kelas ➔</label>
                        <!-- onchange="this.form.submit()" membuat form otomatis terkirim saat opsi dipilih tanpa perlu tombol klik -->
                        <select name="id_kelas" required onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php 
                            $query_dropdown = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                            while($opt = mysqli_fetch_assoc($query_dropdown)) {
                                echo "<option value='".$opt['id']."'>".$opt['nama_kelas']."</option>";
                            }
                            ?>
                        </select>
                    </form>
                <?php else: ?>
                    <!-- Info Kelas Aktif (Muncul jika sudah pilih kelas) -->
                    <?php
                        // Ambil nama kelas yang dipilih untuk ditampilkan
                        $query_nama = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas_terpilih'");
                        $data_kelas = mysqli_fetch_assoc($query_nama);
                        $nama_kelas_aktif = $data_kelas ? $data_kelas['nama_kelas'] : 'Kelas';
                    ?>
                    <div style="background: white; padding: 10px 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px;">
                        <span style="font-weight: 600; color: #4a5568;">Kelas: <span style="color: #c53030;"><?= $nama_kelas_aktif ?></span></span>
                        <a href="dashboard.php" style="background: #e2e8f0; padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #4a5568; font-size: 12px; font-weight: bold;">Ganti Kelas</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if(!$id_kelas_terpilih): ?>
            <!-- TAMPILAN KETIKA KELAS BELUM DIPILIH -->
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; border: 1px dashed #cbd5e0;">
                <img src="https://cdn-icons-png.flaticon.com/512/3081/3081031.png" width="80" style="opacity: 0.5; margin-bottom: 15px;" alt="Pilih Kelas">
                <h3 style="color: #4a5568; margin: 0 0 10px 0;">Silakan Pilih Kelas Terlebih Dahulu</h3>
                <p style="color: #718096; margin: 0; font-size: 14px;">Pilih kelas Anda pada menu dropdown di pojok kanan atas untuk melihat tugas dan materi.</p>
            </div>

        <?php else: ?>
            <!-- TAMPILAN KETIKA KELAS SUDAH DIPILIH -->
            
            <!-- KARTU STATISTIK -->
            <div class="quick-stats">
                <div class="stat-card stat-merah">
                    <h4>Tugas Tertunda</h4>
                    <span>3 Tugas</span>
                </div>
                <div class="stat-card stat-biru">
                    <h4>Materi Baru</h4>
                    <span>2 Materi</span>
                </div>
                <div class="stat-card stat-kuning">
                    <h4>Kuis Mendatang</h4>
                    <span>1 Kuis</span>
                </div>
            </div>
            
        <!-- DAFTAR TUGAS BELUM DIKERJAKAN -->
            <h3 style="margin-bottom: 20px; color: var(--text);">📝 Tugas Belum Dikerjakan (<?= htmlspecialchars($nama_kelas_aktif) ?>)</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <?php
                // Query diperbaiki menggunakan nama kolom 'kelas_id'
                $query_tugas = mysqli_query($conn, "SELECT * FROM tugas WHERE kelas_id = '$id_kelas_terpilih' ORDER BY id DESC LIMIT 4");
                
                if($query_tugas && mysqli_num_rows($query_tugas) > 0) {
                    while($tugas = mysqli_fetch_assoc($query_tugas)) {
                ?>
                    <div class="task-card">
                        <div>
                            <!-- Menampilkan data menggunakan kolom 'judul' dan 'deadline' dari tabel aslimu -->
                            <h4><?= htmlspecialchars($tugas['judul'] ?? 'Tanpa Judul') ?></h4>
                            <p>Tenggat Waktu: <br><strong style="color: #c53030;"><?= htmlspecialchars($tugas['deadline'] ?? 'Tidak ada batas waktu') ?></strong></p>
                        </div>
                        <!-- Pastikan link ke halaman 'kerjakan_tugas.php' ini benar adanya di folder proyekmu -->
                        <a href="kerjakan_tugas.php?id=<?= $tugas['id']; ?>" class="btn-kerjakan">Kerjakan Sekarang</a>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div style='grid-column: 1 / -1; background: white; padding: 20px; border-radius: 10px; text-align: center; color: #888; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>Hore! Belum ada tugas untuk kelas ini. Sambil menunggu, jangan lupa pelajari materi yang baru ya!</div>";
                }
                ?>
            </div>

        <?php endif; ?>
    <?php } ?>

    <div style="margin-top:30px;" class="card">
        <h2>⚙️ Pusat Informasi[cite: 1]</h2>
        <p>Selamat datang di platform E-Learning ISNU. Pilih menu di sidebar untuk mulai beraktivitas sesuai peran Anda.[cite: 1]</p>
    </div>

    <?php 
    } else {
        $allowed_pages = [
            // Admin
            'daftar_siswa_lengkap' => 'admin/daftar_siswa_lengkap.php',
            'user' => 'admin/user.php',
            'edit_user' => 'admin/edit_user.php',
            'kelas' => 'admin/kelas.php',
            'mapel' => 'admin/mapel.php',
            'jadwal' => 'admin/jadwal.php',
            'laporan' => 'admin/laporan.php',
            'pengumuman' => 'admin/pengumuman.php',
            'absensi' => 'admin/absensi.php',
            'kalender' => 'admin/kalender.php',
            'detail_kelas' => 'admin/detail_kelas.php',
            'edit_kelas' => 'admin/edit_kelas.php',
            // Guru
            'profil_guru' => 'guru/profil.php',
            'materi_guru' => 'guru/materi.php',
            'tugas_guru' => 'guru/tugas.php',
            'cek_tugas' => 'guru/cek_tugas.php',
            'quiz_guru' => 'guru/quiz.php',
            'nilai_guru' => 'guru/nilai.php',
            'live_class_guru' => 'guru/live_class.php',
            'forum_guru' => 'guru/forum.php',
            'detail_forum_guru' => 'guru/detail_forum.php',
            'menu_kelas_guru' => 'guru/menu_kelas.php',
            'kelola_soal_guru' => 'guru/kelola_soal.php',
            // Siswa
            'jadwal_siswa' => 'siswa/jadwal.php',
            'materi_siswa' => 'siswa/materi.php',
            'tugas_siswa' => 'siswa/tugas.php',
            'kumpul_tugas' => 'siswa/kumpul_tugas.php',
            'quiz_siswa' => 'siswa/quiz.php',
            'nilai_siswa' => 'siswa/nilai.php',
            'live_class_siswa' => 'siswa/live_class.php',
            'forum_siswa' => 'siswa/forum.php',
            'detail_forum_siswa' => 'siswa/detail_forum.php',
            'menu_kelas_siswa' => 'siswa/menu_kelas.php', 
            'kerjakan_kuis' => 'siswa/kerjakan_kuis.php',
            'detail_nilai_kuis' => 'guru/detail_nilai_kuis.php',

            // SIMPAN DI SINI (BAGIAN UMUM)
            'lihat_pengumuman' => 'lihat_pengumuman.php', 
        ];
        if (array_key_exists($page, $allowed_pages)) {
            include $allowed_pages[$page];
        } else {
            echo "<h2>Halaman tidak ditemukan / Anda tidak memiliki akses.[cite: 1]</h2>";
        }
    }
    ?>
</div>

<script>
function toggleSidebar() { document.getElementById("sidebar").classList.toggle("hide"); }
function toggleDark() { document.body.classList.toggle("dark"); }
function toggleNotif() {
    const d = document.getElementById('notifDropdown');
    d.style.display = d.style.display === 'block' ? 'none' : 'block';
}
function markRead(id) {
    fetch('proses/mark_notif_read.php?id=' + id);
}
document.addEventListener('click', function(e) {
    const d = document.getElementById('notifDropdown');
    if(d && d.style.display === 'block' && !e.target.closest('#notifDropdown') && !e.target.closest('[onclick="toggleNotif()"]')) {
        d.style.display = 'none';
    }
    function tampilkanKelas() {
        // Memunculkan kotak kelas
        document.getElementById('pilihan-kelas-grid').style.display = 'grid';
        
        // Menyembunyikan banner setelah diklik
        document.querySelector('.alert-pilih-kelas').style.display = 'none';
    }
});

</script>
</body>
</html>