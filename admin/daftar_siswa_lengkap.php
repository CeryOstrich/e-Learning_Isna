<?php
// 1. Deteksi kolom nama secara otomatis
$check_column = mysqli_query($conn, "SELECT * FROM daftar_siswa LIMIT 1");
$row_check = mysqli_fetch_assoc($check_column);

$col_nama = 'nama'; 
if (isset($row_check['nama_siswa'])) { $col_nama = 'nama_siswa'; } 
elseif (isset($row_check['nama_lengkap'])) { $col_nama = 'nama_lengkap'; }

// 2. PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    $sql_hapus = "DELETE FROM daftar_siswa WHERE id = '$id_hapus'";
    if (mysqli_query($conn, $sql_hapus)) {
        echo "<script>window.location='dashboard.php?page=daftar_siswa_lengkap&pesan=terhapus';</script>";
        exit;
    }
}

// 3. PROSES SIMPAN DATA (INSERT)
if (isset($_POST['tambah_siswa'])) {
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama_input']);
    $kelas_baru = mysqli_real_escape_string($conn, $_POST['kelas_input']);

    if (!empty($nama_baru)) {
        $sql_insert = "INSERT INTO daftar_siswa ($col_nama, kelas) VALUES ('$nama_baru', '$kelas_baru')";
        mysqli_query($conn, $sql_insert);
        echo "<script>window.location='dashboard.php?page=daftar_siswa_lengkap&pesan=berhasil';</script>";
        exit;
    }
}

// 4. LOGIKA SEARCH & FILTER
$filter_kelas = isset($_GET['filter_kelas']) ? mysqli_real_escape_string($conn, $_GET['filter_kelas']) : '';
$search_nama = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT * FROM daftar_siswa WHERE $col_nama IS NOT NULL AND $col_nama != ''";
if ($filter_kelas != '') { $sql .= " AND kelas = '$filter_kelas'"; }
if ($search_nama != '') { $sql .= " AND ($col_nama LIKE '%$search_nama%')"; }
$sql .= " ORDER BY $col_nama ASC"; 
$query = mysqli_query($conn, $sql);
?>

<div class="card" style="padding: 25px; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
    
    <!-- NOTIFIKASI -->
    <?php if(isset($_GET['pesan'])): ?>
        <div style="background: <?= $_GET['pesan'] == 'berhasil' ? '#d2f9e4' : '#feeaea' ?>; color: <?= $_GET['pesan'] == 'berhasil' ? '#18a45d' : '#ea5455' ?>; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; border-left: 5px solid;">
            <?= $_GET['pesan'] == 'berhasil' ? '✅ Siswa baru berhasil ditambahkan!' : '🗑️ Data siswa telah berhasil dihapus.' ?>
        </div>
    <?php endif; ?>

    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #333; margin: 0;"><i class='bx bxs-graduation' style="color: #7367f0;"></i> Daftar Siswa</h2>
        <div style="display: flex; gap: 10px;">
            <button onclick="document.getElementById('modalTambah').style.display='block'" class="btn" style="background: #28c76f; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                <i class='bx bx-plus-circle'></i> Tambah Siswa
            </button>
            <a href="dashboard.php" class="btn" style="background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>
    </div>

    <!-- SEARCH & FILTER (Opsi Kelas Sudah Lengkap) -->
    <form action="dashboard.php" method="GET" style="display: flex; gap: 20px; margin-bottom: 30px; align-items: flex-end; background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #f1f1f1;">
        <input type="hidden" name="page" value="daftar_siswa_lengkap">
        
        <div style="flex: 1;">
            <label style="display: block; font-size: 13px; color: #777; margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">Cari Nama</label>
            <input type="text" name="search" placeholder="Ketik nama siswa..." value="<?= htmlspecialchars($search_nama) ?>" 
                   style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none; box-sizing: border-box;">
        </div>

        <div style="flex: 1;">
            <label style="display: block; font-size: 13px; color: #777; margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">Pilih Kelas</label>
            <select name="filter_kelas" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; background: white; outline: none; box-sizing: border-box; cursor: pointer;">
                <option value="">-- Semua Kelas --</option>
                <option value="VII (Tujuh) A" <?= $filter_kelas == 'VII (Tujuh) A' ? 'selected' : '' ?>>VII (Tujuh) A</option>
                <option value="VII (Tujuh) B" <?= $filter_kelas == 'VII (Tujuh) B' ? 'selected' : '' ?>>VII (Tujuh) B</option>
                <option value="VIII (Delapan) A" <?= $filter_kelas == 'VIII (Delapan) A' ? 'selected' : '' ?>>VIII (Delapan) A</option>
                <option value="VIII (Delapan) B" <?= $filter_kelas == 'VIII (Delapan) B' ? 'selected' : '' ?>>VIII (Delapan) B</option>
                <option value="IX (Sembilan) A" <?= $filter_kelas == 'IX (Sembilan) A' ? 'selected' : '' ?>>IX (Sembilan) A</option>
                <option value="IX (Sembilan) B" <?= $filter_kelas == 'IX (Sembilan) B' ? 'selected' : '' ?>>IX (Sembilan) B</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" style="background: #7367f0; color:white; border:none; padding: 0 25px; border-radius: 8px; cursor:pointer; font-weight: 600; height: 45px;">Cari</button>
            <?php if($filter_kelas != '' || $search_nama != ''): ?>
                <a href="dashboard.php?page=daftar_siswa_lengkap" style="background: #ea5455; color:white; text-decoration:none; padding: 0 15px; border-radius: 8px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 500;">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- TABEL -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: linear-gradient(45deg, #7367f0, #9e95f5); color: white;">
                    <th style="padding: 18px; width: 60px; text-align: center;">No</th>
                    <th style="padding: 18px; text-align: left;">Nama Lengkap</th>
                    <th style="padding: 18px; width: 220px; text-align: left;">Kelas</th>
                    <th style="padding: 18px; width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($s = mysqli_fetch_assoc($query)) { 
                ?>
                <tr style="border-bottom: 1px solid #f4f4f4;">
                    <td style="padding: 15px; text-align: center; color: #aaa;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 700; color: #444; text-transform: uppercase;"><?= $s[$col_nama]; ?></td>
                    <td style="padding: 15px;">
                        <span style="background: #e1fcef; color: #28c76f; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800;"><?= $s['kelas']; ?></span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="dashboard.php?page=daftar_siswa_lengkap&hapus=<?= $s['id']; ?>" onclick="return confirm('Hapus data <?= $s[$col_nama]; ?>?')" style="color: #ea5455; font-size: 20px;"><i class='bx bxs-trash-alt'></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH (Opsi Kelas Sudah Lengkap) -->
<div id="modalTambah" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
    <div style="background-color: #fff; margin: 8% auto; padding: 0; border-radius: 15px; width: 450px; box-shadow: 0 10px 40px rgba(0,0,0,0.4); overflow: hidden;">
        <div style="background: #7367f0; color: white; padding: 20px; font-weight: bold; font-size: 18px;"><i class='bx bx-user-plus'></i> Tambah Siswa Baru</div>
        <form action="" method="POST" style="padding: 25px;">
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Nama Lengkap</label>
                <input type="text" name="nama_input" required placeholder="Nama lengkap..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 30px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Kelas</label>
                <select name="kelas_input" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white; box-sizing: border-box;">
                    <option value="VII (Tujuh) A">VII (Tujuh) A</option>
                    <option value="VII (Tujuh) B">VII (Tujuh) B</option>
                    <option value="VIII (Delapan) A">VIII (Delapan) A</option>
                    <option value="VIII (Delapan) B">VIII (Delapan) B</option>
                    <option value="IX (Sembilan) A">IX (Sembilan) A</option>
                    <option value="IX (Sembilan) B">IX (Sembilan) B</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end; background: #fcfcfc; padding: 15px 25px; border-top: 1px solid #eee;">
                <button type="button" onclick="document.getElementById('modalTambah').style.display='none'" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #ccc; background: white; cursor:pointer;">Batal</button>
                <button type="submit" name="tambah_siswa" style="padding: 10px 25px; border-radius: 8px; border: none; background: #28c76f; color: white; cursor:pointer; font-weight: 700;">Simpan</button>
            </div>
        </form>
    </div>
</div>