<?php
/**
 * views/auth/login.php
 * Halaman login dengan desain minimalis dan elegan.
 */

// Jika sudah login, langsung redirect ke dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/index.php?page=dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <meta name="description" content="Portal E-Learning Madrasah Tsanawiyah — Login untuk mengakses materi, tugas, dan ujian online.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ── Reset & Base ───────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:       #1e293b;
            --primary-light: #334155;
            --accent:        #3b82f6;
            --success:       #10b981;
            --danger:        #ef4444;
            --text:          #0f172a;
            --text-muted:    #64748b;
            --border:        #e2e8f0;
            --bg:            #f8fafc;
            --card:          #ffffff;
            --radius:        20px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            position: relative;
            overflow: hidden;
        }

        /* ── Background Mesh ────────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(at 40% 20%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
            z-index: -1;
        }

        /* ── Login Card ──────────────────────────────────────── */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border-radius: var(--radius);
            padding: 48px 40px;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.02);
            position: relative;
            z-index: 1;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: scale(0.96);
        }

        @keyframes fadeInScale {
            to { opacity: 1; transform: scale(1); }
        }

        /* ── Header / Branding ───────────────────────────────── */
        .card-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(30, 41, 59, 0.15);
        }

        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* ── Form Elements ───────────────────────────────────── */
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.icon-left {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 20px;
            transition: color 0.2s;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-wrapper:focus-within .icon-left {
            color: var(--accent);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        /* ── Button ──────────────────────────────────────────── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(30, 41, 59, 0.15);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login .spinner {
            display: none;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .btn-login.loading .btn-text { display: none; }
        .btn-login.loading .spinner { display: block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Alert Notifikasi ────────────────────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            display: none;
            align-items: center;
            gap: 10px;
            animation: fadeInDown 0.3s ease;
        }

        .alert.show { display: flex; }
        .alert-error { background: #fef2f2; color: var(--danger); border: 1px solid #fee2e2; }
        .alert-success { background: #f0fdf4; color: var(--success); border: 1px solid #dcfce7; }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Admin Padlock ───────────────────────────────────── */
        .admin-lock {
            position: fixed;
            bottom: 24px;
            left: 24px;
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--text-muted);
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .admin-lock:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: scale(1.05);
        }

        /* ── Footer text ─────────────────────────────────────── */
        .card-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* ── Animasi Shake ───────────────────────────────────── */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60%  { transform: translateX(-5px); }
            40%, 80%  { transform: translateX(5px); }
        }
        .shake { animation: shake 0.4s ease; }

        @media (max-width: 480px) {
            .login-card {
                padding: 40px 24px;
                border-radius: 24px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.05);
                width: 90vw;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header">
        <div class="brand-icon">🕌</div>
        <h1><?= APP_NAME ?></h1>
        <p>Masuk untuk melanjutkan pembelajaran</p>
    </div>

    <!-- Alert Notifikasi -->
    <div id="alertBox" class="alert" role="alert">
        <i id="alertIcon" class='bx bx-error-circle'></i>
        <span id="alertMsg">Pesan error</span>
    </div>

    <!-- Form Login -->
    <form id="loginForm" novalidate>
        <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="form-label" for="username">NIS / NIP</label>
            <div class="input-wrapper">
                <i class='bx bx-id-card icon-left'></i>
                <input type="text" id="username" name="username" class="form-control"
                       placeholder="Masukkan NIS atau NIP"
                       autocomplete="username" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrapper">
                <i class='bx bx-lock-alt icon-left'></i>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="••••••••"
                       autocomplete="current-password" required>
                <i class='bx bx-hide toggle-password' id="togglePass"
                   onclick="togglePassword()" title="Tampilkan/Sembunyikan"></i>
            </div>
        </div>

        <button type="submit" class="btn-login" id="btnLogin">
            <span class="btn-text">Masuk Sekarang</span>
            <div class="spinner"></div>
        </button>
    </form>

    <div class="card-footer">
        © <?= date('Y') ?> Madrasah Tsanawiyah
    </div>
</div>

<!-- Admin Lock Icon -->
<div class="admin-lock" id="adminLockBtn" title="Login Administrator">
    <i class='bx bxs-lock-alt'></i>
</div>

<script>
const form     = document.getElementById('loginForm');
const btnLogin = document.getElementById('btnLogin');
const alertBox = document.getElementById('alertBox');
const alertMsg = document.getElementById('alertMsg');
const alertIcon= document.getElementById('alertIcon');
const adminLockBtn = document.getElementById('adminLockBtn');

// Toggle Password
function togglePassword() {
    const passInput = document.getElementById('password');
    const icon      = document.getElementById('togglePass');
    if (passInput.type === 'password') {
        passInput.type = 'text';
        icon.className = 'bx bx-show toggle-password';
    } else {
        passInput.type = 'password';
        icon.className = 'bx bx-hide toggle-password';
    }
}

// Alert System
function showAlert(type, msg) {
    alertBox.className = `alert alert-${type} show`;
    alertIcon.className = type === 'error' ? 'bx bx-error-circle' : 'bx bx-check-circle';
    alertMsg.textContent = msg;
    if (type === 'success') setTimeout(hideAlert, 5000);
}

function hideAlert() {
    alertBox.className = 'alert';
}

// Form Submit
form.addEventListener('submit', async function (e) {
    e.preventDefault();
    hideAlert();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    if (!username || !password) {
        showAlert('error', 'NIS/NIP dan password wajib diisi.');
        return;
    }

    btnLogin.classList.add('loading');
    const formData = new FormData(form);

    try {
        const res  = await fetch('<?= BASE_URL ?>/modules/auth/login_handler.php', {
            method: 'POST',
            body:   formData,
        });

        const data = await res.json();

        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => { window.location.href = data.redirect; }, 800);
        } else {
            showAlert('error', data.message);
            document.getElementById('password').value = '';
            
            // Apply shake animation
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(inp => {
                inp.classList.remove('shake');
                void inp.offsetWidth; // trigger reflow
                inp.classList.add('shake');
            });
        }
    } catch (err) {
        showAlert('error', 'Koneksi ke server gagal. Coba beberapa saat lagi.');
    } finally {
        btnLogin.classList.remove('loading');
    }
});

// Clear alert on input
document.getElementById('username').addEventListener('input', hideAlert);
document.getElementById('password').addEventListener('input', hideAlert);

// Admin Login
adminLockBtn.addEventListener('click', () => {
    Swal.fire({
        title: 'Login Administrator',
        text: 'Masukkan kode keamanan admin',
        input: 'password',
        inputPlaceholder: 'Kode Unik',
        inputAttributes: { autocapitalize: 'off', autocorrect: 'off' },
        showCancelButton: true,
        confirmButtonText: 'Verifikasi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#1e293b',
        showLoaderOnConfirm: true,
        preConfirm: async (code) => {
            if (!code) { Swal.showValidationMessage('Kode wajib diisi'); return false; }
            try {
                const fd = new FormData();
                fd.append('admin_code', code);
                const csrf = document.getElementById('csrf_token');
                if (csrf) fd.append('csrf_token', csrf.value);

                const response = await fetch('<?= BASE_URL ?>/modules/auth/login_handler.php', {
                    method: 'POST',
                    body: fd
                });
                
                const resData = await response.json();
                if (!resData.success) {
                    Swal.showValidationMessage(resData.message || 'Kode unik salah');
                    return false;
                }
                return resData;
            } catch (error) {
                Swal.showValidationMessage('Terjadi kesalahan jaringan');
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Akses Diberikan',
                showConfirmButton: false,
                timer: 1000
            }).then(() => {
                window.location.href = result.value.redirect || '<?= BASE_URL ?>/index.php?page=dashboard';
            });
        }
    });
});
</script>
</body>
</html>
