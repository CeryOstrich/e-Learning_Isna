<?php
session_start();
// Proteksi halaman admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - E-Learning</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --main: #6a11cb;
            --second: #2575fc;
            --bg: #f4f6f9;
            --text: #333;
            --card-bg: #ffffff;
        }

        /* Mode Gelap Otomatis jika Dashboard menggunakan Dark Mode */
        body.dark {
            --bg: #1e1e2f;
            --text: #fff;
            --card-bg: #2c2c3e;
        }

        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Poppins', sans-serif; }
        body { display:flex; background: var(--bg); color: var(--text); transition: 0.3s; }

        /* SIDEBAR (Disamakan dengan Dashboard) */
        .sidebar {
            width: 250px; height: 100vh;
            background: linear-gradient(180deg, var(--main), var(--second));
            color: white; padding: 20px; position: fixed; transition: 0.3s;
            z-index: 100;
        }
        .sidebar.hide { width: 70px; }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size: 20px; }
        .sidebar a {
            display:flex; align-items:center; padding:12px; margin:8px 0;
            border-radius:10px; color:white; text-decoration:none; transition:0.3s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.2); transform: translateX(5px); }
        .sidebar i { margin-right:10px; font-size: 20px; }
        .sidebar.hide i { margin-right: 0; }
        .sidebar.hide span, .sidebar.hide h2 { display: none; }

        /* CONTENT */
        .content { margin-left:250px; padding:30px; width:100%; transition:0.3s; }
        .sidebar.hide ~ .content { margin-left:70px; }

        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .toggle-btn { font-size:22px; cursor:pointer; }

        /* CARD STYLE (Disamakan dengan Dashboard) */
        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: var(--text);
        }

        /* FORM & TABLE STYLING */
        input, select {
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 300px;
        }
        
        table { width:100%; border-collapse:collapse; margin-top: 15px; }
        table th, table td { padding:12px; text-align: left; border-bottom:1px solid rgba(0,0,0,0.1); }
        body.dark table td { border-bottom: 1px solid rgba(255,255,255,0.1); }

        /* BUTTONS */
        .btn {
            padding:8px 16px; border:none; border-radius:8px;
            cursor:pointer; text-decoration: none; display: inline-block;
            font-size: 14px; transition: 0.2s;
        }
        .btn-add { background:#22c55e; color:white; }
        .btn-edit { background:#3b82f6; color:white; }
        .btn-delete { background:#ef4444; color:white; }
        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h2>E-Learn Admin</h2>
    <a href="user.php"><i class='bx bxs-user'></i> <span>User</span></a>
    <a href="kelas.php"><i class='bx bxs-school'></i> <span>Kelas</span></a>
    <a href="mapel.php"><i class='bx bxs-book-open'></i> <span>Mapel</span></a>
    <a href="jadwal.php"><i class='bx bxs-calendar'></i> <span>Jadwal</span></a>
    <a href="laporan.php"><i class='bx bxs-bar-chart'></i> <span>Laporan</span></a>
    <hr style="opacity: 0.3; margin: 15px 0;">
    <a href="../dashboard.php"><i class='bx bx-left-arrow-alt'></i> <span>Kembali</span></a>
</div>

<div class="content">
    <div class="header">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class='bx bx-menu'></i></span>
        <h2>Panel Administrasi</h2>
    </div>
    
    <!-- Bagian ini akan diisi oleh konten dari file user.php, kelas.php, dll -->

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("hide");
}
</script>