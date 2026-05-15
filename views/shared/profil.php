<?php
Auth::requireLogin();
$db = Database::getInstance();

$user = $db->queryOne("SELECT * FROM users WHERE id=?", 'i', [$_SESSION['user_id']]);

$pageTitle = 'Profil Saya';
ob_start();
?>

<div class="card mb-6" style="max-width:600px; margin: 0 auto;">
    <div class="card-header"><span class="card-title">👤 Pengaturan Profil</span></div>
    
    <form action="<?= BASE_URL ?>/modules/shared/profil_handler.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        
        <div style="text-align:center; margin-bottom:30px;">
            <?php
            $fotoUrl = $user['foto_profil'] 
                ? BASE_URL . '/uploads/profil/' . $user['foto_profil'] 
                : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama']) . '&background=1a3a6b&color=fff&size=120';
            ?>
            <img src="<?= $fotoUrl ?>" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:15px; border:4px solid var(--border);">
            <br>
            <label for="foto_profil" class="btn btn-outline btn-sm" style="cursor:pointer;"><i class='bx bx-camera'></i> Ganti Foto</label>
            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display:none;">
            <p class="text-muted mt-2" style="font-size:0.8rem;">Format: JPG/PNG, Max: 2MB</p>
        </div>
        
        <div class="form-group">
            <label>NIS / NIP <small class="text-muted">(digunakan untuk login)</small></label>
            <input type="text" class="form-control" value="<?= e($user['nis_nip'] ?: '-') ?>" readonly style="background:var(--bg); cursor:not-allowed;">
        </div>
        
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="<?= e($user['nama']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
            <input type="password" name="password_baru" class="form-control" placeholder="******">
        </div>
        
        <div style="text-align:center; margin-top:30px;">
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class='bx bx-save'></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
document.getElementById('foto_profil').addEventListener('change', function() {
    if(this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('img').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
