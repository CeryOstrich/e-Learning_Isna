<?php
// 1. Ambil input search nama guru
$search_guru = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// 2. Deteksi kolom nama guru (biasanya 'nama' atau 'nama_guru')
$check_col = mysqli_query($conn, "SELECT * FROM daftar_guru LIMIT 1");
$row_check = mysqli_fetch_assoc($check_col);
$col_nama = isset($row_check['nama_guru']) ? 'nama_guru' : 'nama';

// 3. Query Data Guru
$sql = "SELECT * FROM daftar_guru WHERE 1=1";
if ($search_guru != '') {
    $sql .= " AND $col_nama LIKE '%$search_guru%'";
}
$sql .= " ORDER BY $col_nama ASC";

$query = mysqli_query($conn, $sql);
?>

<div class="card" style="padding: 25px; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #333;"><i class='bx bxs-briefcase' style="color: #7367f0;"></i> Daftar Seluruh Guru</h2>
        <a href="dashboard.php" class="btn" style="background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px;">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
    </div>

    <!-- SEARCH BOX -->
    <form action="dashboard.php" method="GET" style="display: flex; gap: 12px; margin-bottom: 25px; background: #f8f9fa; padding: 20px; border-radius: 12px;">
        <input type="hidden" name="page" value="daftar_guru_lengkap">
        <input type="text" name="search" placeholder="Cari nama guru..." value="<?= htmlspecialchars($search_guru) ?>" 
               style="flex: 1; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
        
        <button type="submit" style="background: #7367f0; color:white; border:none; padding: 12px 25px; border-radius: 8px; cursor:pointer; font-weight: 600;">
            Cari
        </button>
        
        <?php if($search_guru != ''): ?>
            <a href="dashboard.php?page=daftar_guru_lengkap" style="background: #ea5455; color:white; text-decoration:none; padding: 12px 20px; border-radius: 8px; display: flex; align-items: center;">Reset</a>
        <?php endif; ?>
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #7367f0; color: white; text-align: left;">
                    <th style="padding: 15px; border-top-left-radius: 10px; width: 60px; text-align: center;">No</th>
                    <th style="padding: 15px;">Nama Lengkap Guru</th>
                    <th style="padding: 15px; border-top-right-radius: 10px;">Mata Pelajaran / Jabatan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($query && mysqli_num_rows($query) > 0) {
                    while($g = mysqli_fetch_assoc($query)) { 
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; text-align: center; color: #888;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 600; color: #333;">
                        <?= strtoupper($g[$col_nama]); ?>
                    </td>
                    <td style="padding: 15px;">
                        <span style="background: #f0efff; color: #7367f0; padding: 6px 15px; border-radius: 8px; font-size: 12px; font-weight: bold;">
                            <?= htmlspecialchars($g['mapel'] ?? $g['jabatan'] ?? 'Guru'); ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; padding: 50px; color: #999;'>Data guru tidak ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>