<?php
// GURU: Kelola Profil
// Perbaikan: Gunakan pemeriksaan session yang aman sesuai source 2
if(!isset($_SESSION['id'])) {
    echo "<div class='alert' style='background:#fff3cd; color:#856404; padding:15px; border-radius:10px; margin-bottom:15px; border-left: 5px solid #ffeeba;'>
            ⚠️ <strong>Sesi tidak lengkap.</strong> Silakan <a href='logout.php' style='color:#856404; font-weight:bold;'>Logout</a> dan Login kembali untuk memperbarui data profil Anda.
          </div>";
    // Fallback jika session id kosong
    $me = ['nama' => $_SESSION['email'] ?? 'User', 'email' => $_SESSION['email'] ?? '', 'foto_profil' => '', 'bio' => ''];
} else {
    // Perbaikan: Nama tabel adalah 'users', bukan 'user'
    $user_id = $_SESSION['id'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $me = mysqli_fetch_assoc($query);
}

// Jika data tidak ditemukan di database[cite: 2]
if (!$me) {
    $me = ['nama' => $_SESSION['email'] ?? 'User', 'email' => $_SESSION['email'] ?? '', 'foto_profil' => '', 'bio' => ''];
}
?>

<div style="display: flex; flex-direction: column; gap: 25px;">
    
    <!-- TAMPILAN PROFIL ATAS -->
    <div class="card" style="padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none;">
        <h2 style="margin-top:0; margin-bottom:20px;"><i class='bx bxs-user-circle' style="color: var(--main);"></i> Profil Saya</h2>
        
        <div style="display:flex; gap:40px; align-items:center; flex-wrap:wrap;">
            <div style="text-align:center;">
                <?php if(!empty($me['foto_profil'])): ?>
                    <img src="uploads/profil/<?= $me['foto_profil'] ?>" style="width:140px; height:140px; border-radius:50%; object-fit:cover; border:5px solid #f0eeff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <?php else: ?>
                    <div style="width:140px; height:140px; border-radius:50%; background:linear-gradient(45deg, var(--main), var(--second)); display:flex; align-items:center; justify-content:center; font-size:60px; color:white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">👤</div>
                <?php endif; ?>
            </div>
            
            <div style="flex:1; min-width: 250px;">
                <h2 style="margin: 0; color: var(--text);"><?= $me['nama'] ?></h2>
                <div style="margin-top: 10px;">
                    <p style="margin: 5px 0;"><i class='bx bx-envelope'></i> <strong>Email:</strong> <?= $me['email'] ?></p>
                    <p style="margin: 5px 0;"><i class='bx bx-shield-quarter'></i> <strong>Role:</strong> <span class="badge guru" style="padding: 4px 12px; border-radius: 20px;">Guru</span></p>
                    <p style="margin: 5px 0;"><i class='bx bx-info-circle'></i> <strong>Bio:</strong> <?= $me['bio'] ?? '<em>Belum ada bio singkat</em>' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM EDIT PROFIL -->
    <div class="card" style="padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none;">
        <h3 style="margin-top:0; margin-bottom:25px;"><i class='bx bx-edit' style="color: var(--orange);"></i> Edit Profil</h3>
        
        <form action="proses/update_profil.php" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                
                <!-- SISI KIRI -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight:600; margin-bottom:8px;">Nama Lengkap:</label>
                        <input type="text" name="nama" value="<?= $me['nama'] ?>" required style="width:100%; border-radius:10px; padding: 10px; border: 1px solid #ddd;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight:600; margin-bottom:8px;">Foto Profil (JPG/PNG):</label>
                        <input type="file" name="foto_profil" accept="image/*" style="width:100%; border:1px solid #ddd; padding:8px; border-radius:10px; background:#f9f9f9;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight:600; margin-bottom:8px;">Password Baru:</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" style="width:100%; border-radius:10px; padding: 10px; border: 1px solid #ddd;">
                    </div>
                </div>

                <!-- SISI KANAN -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight:600; margin-bottom:8px;">Bio / Deskripsi Singkat:</label>
                        <textarea name="bio" style="width:100%; height:195px; padding:15px; border-radius:10px; border:1px solid #ddd; resize:none;" placeholder="Tulis bio singkat Anda di sini..."><?= $me['bio'] ?></textarea>
                    </div>
                </div>

            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                <button type="submit" class="btn btn-main" style="padding: 12px 35px; border-radius: 12px; font-weight: 600;">
                    <i class='bx bx-save'></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>