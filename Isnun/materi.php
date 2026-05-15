<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<link rel="stylesheet" href="style.css">
<a href="dashboard.php" style="display:inline-block; margin-bottom: 20px; color: #6a11cb; text-decoration: none;">← Kembali ke Dashboard</a>
<h2>📄 Materi Pembelajaran</h2>

<div class="card">
    <h3>Matematika</h3>
    <p>Materi Aljabar</p>
    <a href="#">Download</a>
</div>

<div class="card">
    <h3>Bahasa Indonesia</h3>
    <p>Materi Cerpen</p>
    <a href="#">Download</a>
</div>