<style>
    .btn {
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        font-weight: 500;
    }
    .btn-add { background-color: #28c76f; color: white; }
    .btn-info { background-color: #2196F3; color: white; }
    .btn-warning { background-color: #FF9800; color: white; }
    .btn-delete { background-color: #f44336; color: white; }
    .btn:hover { opacity: 0.8; transform: translateY(-1px); }
    
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    th { background-color: #f8f9fa; color: #333; font-weight: 600; }
    th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
    tr:hover { background-color: #fcfcfc; }

    .input-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .input-group input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        flex: 1;
    }
</style>

<div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 20px;">
    <h2 style="display: flex; align-items: center; gap: 10px;">🏫 Kelola Kelas</h2>
    <form action="proses/tambah_kelas.php" method="POST" class="input-group">
        <input name="nama_kelas" placeholder="Nama Kelas (Contoh: VII A)" required>
        <input name="fasilitas" placeholder="Fasilitas (Contoh: AC, Proyektor)">
        <button type="submit" class="btn btn-add">
            <i class='bx bx-plus'></i> Tambah Kelas
        </button>
    </form>
</div>

<div class="card" style="padding: 10px; border-radius: 12px; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th>
                <th>Nama Kelas</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data dari tabel kelas
            $data = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id ASC");
            
            // Inisialisasi nomor urut agar mulai dari 1
            $n = 1; 
            
            while($k = mysqli_fetch_assoc($data)){
            ?>
            <tr>
                <!-- Menampilkan nomor urut, bukan ID database -->
                <td style="text-align: center; font-weight: bold; color: #666;"><?= $n++ ?></td>
                
                <td>
                    <span style="font-size: 16px; color: #333; font-weight: 600;"><?= $k['nama_kelas'] ?></span>
                </td>
                
                <td style="text-align: center; display: flex; justify-content: center; gap: 8px;">
                    <!-- Tombol Lihat -->
                    <a href="dashboard.php?page=detail_kelas&id=<?= $k['id'] ?>" class="btn btn-info">
                        <i class='bx bx-show'></i> Lihat
                    </a>
                    
                    <!-- Tombol Edit -->
                    <a href="dashboard.php?page=edit_kelas&id=<?= $k['id'] ?>" class="btn btn-warning">
                        <i class='bx bx-edit-alt'></i> Edit
                    </a>
                    
                    <!-- Tombol Hapus -->
                    <a href="proses/hapus_kelas.php?id=<?= $k['id'] ?>" class="btn btn-delete" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus kelas <?= $k['nama_kelas'] ?>? Semua data terkait mungkin akan hilang.')">
                        <i class='bx bx-trash'></i> Hapus
                    </a>
                </td>
            </tr>
            <?php } ?>
            
            <?php if(mysqli_num_rows($data) == 0): ?>
            <tr>
                <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                    Belum ada data kelas. Silakan tambah kelas baru di atas.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>