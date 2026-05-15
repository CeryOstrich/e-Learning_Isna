<?php
session_start();
include "database.php"; 

$error_login = "";
$error_register = "";
$form_status = ""; 

// --- 1. LOGIKA LOGIN RAHASIA ---
if (isset($_POST['admin_secret_login'])) {
    if ($_POST['kode_admin'] === "isnun") {
        $_SESSION['login'] = true;
        $_SESSION['role']  = "admin";
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Kode Admin Salah!');</script>";
    }
}

// --- 2. LOGIKA LOGIN NORMAL ---
if (isset($_POST['login'])) {
    $form_status = ""; 
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        if (password_verify($password, $data['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id']    = $data['id'];
            $_SESSION['nama']  = $data['nama'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['role']  = $data['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error_login = "Sandi salah!"; 
        }
    } else {
        $error_login = "Email tidak ditemukan!"; 
    }
}

// --- 3. LOGIKA REGISTER (Perbaikan Error Screenshot 2026-04-28 20:00:06.png) ---
if (isset($_POST['register'])) {
    $form_status = "active"; 
    
    // Ambil data dari FORM terlebih dahulu agar variabel tidak 'Undefined'
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = mysqli_real_escape_string($conn, $_POST['role']); 

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error_register = "Email sudah terdaftar!"; 
    } else {
        // Gunakan variabel yang sudah diisi di atas
        $sql = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";
        $insert = mysqli_query($conn, $sql);
        
        if($insert) {
            echo "<script>alert('Berhasil Daftar! Silakan Login.'); window.location='Login_Page.php';</script>";
            exit;
        } else {
            $error_register = "Gagal simpan: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learn - Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: url('mts.jpg') no-repeat center center/cover; overflow: hidden; }

        /* Gembok Menyala */
        .admin-lock { position: fixed; left: 20px; bottom: 20px; width: 45px; height: 45px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 100; border: 1px solid rgba(255,255,255,0.3); transition: 0.4s; }
        .admin-lock:hover { background: rgba(255,255,255,0.4); box-shadow: 0 0 20px rgba(255,255,255,0.6); transform: scale(1.1); }

        .container { position: relative; width: 900px; height: 550px; background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border-radius: 30px; border: 1px solid rgba(255,255,255,0.2); display: flex; overflow: hidden; }
        
        .toggle-box { position: absolute; left: 0; top: 0; width: 50%; height: 100%; background: linear-gradient(135deg, #f02fc2, #6094ea); border-radius: 0 150px 150px 0; z-index: 10; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; transition: 0.7s ease-in-out; }
        .container.active .toggle-box { left: 50%; border-radius: 150px 0 0 150px; }

        .form-box { width: 50%; height: 100%; display: flex; flex-direction: column; justify-content: center; padding: 40px; transition: 0.6s ease; }
        .login-box { position: absolute; right: 0; opacity: 1; }
        .register-box { position: absolute; left: 0; opacity: 0; pointer-events: none; }
        .container.active .login-box { opacity: 0; pointer-events: none; }
        .container.active .register-box { opacity: 1; pointer-events: auto; }

        h2 { color: white; font-size: 32px; margin-bottom: 20px; text-align: center; }
        .error-msg { color: #ff4d4d; font-size: 13px; text-align: center; margin-bottom: 10px; font-weight: 600; }

        .input-group { position: relative; margin-bottom: 15px; }
        .input-group input, .input-group select { width: 100%; padding: 12px 45px 12px 18px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); border-radius: 12px; outline: none; color: white; appearance: none; -webkit-appearance: none; }
        .input-group input::placeholder { color: rgba(255,255,255,0.9); }
        .input-group i { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); color: white; }

        .btn { width: 100%; padding: 12px; background: linear-gradient(to right, #f02fc2, #6094ea); border: none; border-radius: 12px; color: white; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-outline { background: transparent; border: 2px solid white; width: 150px; margin-top: 15px; }

        .secret-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); z-index: 1000; justify-content: center; align-items: center; }
        .secret-box { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 30px; border-radius: 20px; color: white; text-align: center; }
    </style>
</head>
<body>

    <div class="admin-lock" onclick="toggleAdmin()"><i class='bx bxs-lock-alt'></i></div>

    <div class="secret-modal" id="adminModal">
        <div class="secret-box">
            <h3>Akses Admin</h3>
            <form action="" method="POST">
                <input type="password" name="kode_admin" placeholder="Kode" style="width:100%; padding:10px; margin:15px 0; border-radius:8px; border:none; text-align:center;">
                <button type="submit" name="admin_secret_login" class="btn">Masuk</button>
                <p onclick="toggleAdmin()" style="cursor:pointer; margin-top:10px; font-size:12px;">Batal</p>
            </form>
        </div>
    </div>

    <div class="container <?= $form_status ?>" id="container">
        <div class="toggle-box">
            <h1 id="toggle-title"><?= ($form_status == 'active') ? 'Halo, Teman!' : 'Halo!' ?></h1>
            <p id="toggle-desc"><?= ($form_status == 'active') ? 'Sudah punya akun?' : 'Belum punya akun?' ?></p>
            <button class="btn btn-outline" id="toggle-btn" onclick="switchForm()"><?= ($form_status == 'active') ? 'Masuk' : 'Daftar' ?></button>
        </div>

        <div class="form-box login-box">
            <h2>Masuk</h2>
            <?php if($error_login): ?> <div class="error-msg"><?= $error_login ?></div> <?php endif; ?>
            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Sandi" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>

        <div class="form-box register-box">
            <h2>Daftar</h2>
            <?php if($error_register): ?> <div class="error-msg"><?= $error_register ?></div> <?php endif; ?>
            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Sandi" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-group">
                    <select name="role" required style="background: rgba(255,255,255,0.2); color:white;">
                        <option value="" disabled selected style="color:black;">Pilih Role</option>
                        <option value="siswa" style="color:black;">Siswa</option>
                        <option value="guru" style="color:black;">Guru</option>
                    </select>
                    <i class='bx bxs-chevron-down'></i>
                </div>
                <button type="submit" name="register" class="btn">Buat Akun</button>
            </form>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const toggleBtn = document.getElementById('toggle-btn');
        const toggleTitle = document.getElementById('toggle-title');
        const toggleDesc = document.getElementById('toggle-desc');

        function switchForm() {
            container.classList.toggle('active');
            if (container.classList.contains('active')) {
                toggleTitle.innerText = "Halo, Teman!";
                toggleDesc.innerText = "Sudah punya akun?";
                toggleBtn.innerText = "Masuk";
            } else {
                toggleTitle.innerText = "Halo!";
                toggleDesc.innerText = "Belum punya akun?";
                toggleBtn.innerText = "Daftar";
            }
        }
        function toggleAdmin() {
            const modal = document.getElementById('adminModal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
</body>
</html>