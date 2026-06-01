<?php
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
?>

<div class="card">
    <h2>✏️ Edit User</h2>
    
    <form action="proses/update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required>
        
        <label>Role:</label>
        <select name="role">
            <option <?= $data['role']=='siswa'?'selected':'' ?>>siswa</option>
            <option <?= $data['role']=='guru'?'selected':'' ?>>guru</option>
            <option <?= $data['role']=='admin'?'selected':'' ?>>admin</option>
        </select>
        
        <label>Password (Kosongkan jika tidak ingin mengubah):</label>
        <input type="password" name="password" placeholder="Password Baru">
        
        <br><br>
        <button type="submit" class="btn btn-edit">Update Data</button>
        <a href="dashboard.php?page=user" class="btn" style="border: 1px solid #ddd; color: #333; margin-left: 10px;">Batal</a>
    </form>
</div>