<?php
// Ambil ID dari URL, kalau tidak ada set ke 0
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Cari datanya di database
$q = mysqli_query($conn, "SELECT * FROM pengumuman WHERE id='$id'");
$d = mysqli_fetch_assoc($q);

// Jika data ditemukan, tampilkan. Jika tidak, beri pesan sopan.
if ($d) {
    $judul = $d['judul'];
    $tgl   = isset($d['tanggal']) ? date('d M Y H:i', strtotime($d['tanggal'])) : (isset($d['created_at']) ? date('d M Y H:i', strtotime($d['created_at'])) : '-');
    $isi   = $d['isi'];
} else {
    $judul = "Pengumuman Tidak Ditemukan";
    $tgl   = "-";
    $isi   = "Maaf, isi pengumuman ini sudah tidak tersedia atau telah dihapus.";
}
?>

<div class="card" style="padding: 30px; border-radius: 15px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
    <div style="text-align: center; border-bottom: 2px solid #f4f4f4; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="color: #6a11cb;">✉️ Surat Pengumuman</h2>
    </div>
    
    <h3 style="margin-bottom: 5px; color: #333;"><?= $judul; ?></h3>
    <small style="color: #aaa; display: block; margin-bottom: 20px;">
        📅 Diposting pada: <?= $tgl; ?>
    </small>
    
    <div style="line-height: 1.8; color: #555; font-size: 15px; text-align: justify;">
        <?= nl2br($isi); ?>
    </div>
    
    <div style="margin-top: 30px; text-align: right;">
        <a href="dashboard.php" style="padding: 10px 20px; background: #6a11cb; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px;">Kembali ke Dashboard</a>
    </div>
</div>