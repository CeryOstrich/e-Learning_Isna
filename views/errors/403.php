<?php
/**
 * Error 403 — Akses Ditolak.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f4ff; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { text-align: center; background: white; padding: 50px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h1 { color: #1a3a6b; margin-bottom: 10px; }
        p { color: #6b7280; margin-bottom: 30px; }
        a { display: inline-block; padding: 10px 24px; background: #1a3a6b; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <div style="font-size: 80px; margin-bottom: 20px;">🚫</div>
        <h1>403 — Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman atau fitur ini.</p>
        <a href="javascript:history.back()">← Kembali</a>
    </div>
</body>
</html>
