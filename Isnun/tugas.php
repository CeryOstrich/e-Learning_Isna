<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<link rel="stylesheet" href="style.css">
<a href="dashboard.php" style="display:inline-block; margin-bottom: 20px; color: #6a11cb; text-decoration: none;">← Kembali ke Dashboard</a>
<h2>📝 Tugas</h2>
    <h3>Tugas Matematika</h3>
    <p>Kumpulkan sebelum: 20 April</p>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
</div>