/* ============================================================
   main.js — JavaScript global untuk E-Learning MTs
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // ── SIDEBAR TOGGLE ──────────────────────────────────────
    const sidebar        = document.getElementById('sidebar');
    const mainWrapper    = document.getElementById('mainWrapper');
    const menuToggle     = document.getElementById('menuToggle');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const overlay        = document.getElementById('sidebarOverlay');

    const isMobile = () => window.innerWidth <= 768;

    // Simpan & muat preferensi sidebar dari localStorage
    const sidebarKey = 'mts_sidebar_collapsed';
    if (!isMobile() && localStorage.getItem(sidebarKey) === '1') {
        document.body.classList.add('sidebar-collapsed');
    }

    function toggleSidebar() {
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem(sidebarKey,
                document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        }
    }

    if (menuToggle)     menuToggle.addEventListener('click', toggleSidebar);
    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);
    if (overlay)        overlay.addEventListener('click', () => {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    });

    // ── DARK MODE ────────────────────────────────────────────
    const darkToggle = document.getElementById('darkToggle');
    const darkIcon   = document.getElementById('darkIcon');
    const darkKey    = 'mts_dark_mode';

    function applyDark(isDark) {
        document.body.classList.toggle('dark-mode', isDark);
        if (darkIcon) {
            darkIcon.className = isDark ? 'bx bx-sun' : 'bx bx-moon';
        }
    }

    // Muat preferensi dark mode
    applyDark(localStorage.getItem(darkKey) === '1');

    if (darkToggle) {
        darkToggle.addEventListener('click', () => {
            const isDark = !document.body.classList.contains('dark-mode');
            applyDark(isDark);
            localStorage.setItem(darkKey, isDark ? '1' : '0');
        });
    }

    // ── NOTIFIKASI DROPDOWN ──────────────────────────────────
    const notifBtn      = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifList     = document.getElementById('notifList');
    let   notifLoaded   = false;

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = notifDropdown.classList.toggle('show');

            // Muat notifikasi via AJAX sekali saja
            if (isOpen && !notifLoaded) {
                loadNotifications();
                notifLoaded = true;
            }
        });

        // Klik di luar dropdown → tutup
        document.addEventListener('click', function (e) {
            if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
                notifDropdown.classList.remove('show');
            }
        });
    }

    /**
     * Muat notifikasi terbaru via Fetch API.
     */
    async function loadNotifications() {
        try {
            const res  = await fetch(BASE_URL + '/modules/shared/get_notifikasi.php');
            const data = await res.json();

            if (!data.items || data.items.length === 0) {
                notifList.innerHTML = '<div class="notif-loading">Tidak ada notifikasi baru.</div>';
                return;
            }

            notifList.innerHTML = data.items.map(n => `
                <a href="${escHtml(n.link || '#')}"
                   class="notif-item ${n.dibaca == 0 ? 'unread' : ''}"
                   onclick="markNotifRead(${n.id})">
                    ${escHtml(n.pesan)}
                    <small>${escHtml(n.created_at)}</small>
                </a>
            `).join('');
        } catch (err) {
            notifList.innerHTML = '<div class="notif-loading">Gagal memuat notifikasi.</div>';
        }
    }

    // ── Tandai notifikasi sudah dibaca ──────────────────────
    window.markNotifRead = async function (id) {
        await fetch(`${BASE_URL}/modules/shared/mark_notif_read.php?id=${id}`);
        // Update badge (kurangi 1)
        const badge = document.querySelector('.notif-badge');
        if (badge) {
            const count = parseInt(badge.textContent) - 1;
            if (count <= 0) badge.remove();
            else badge.textContent = count;
        }
    };

    // Tandai semua dibaca
    const markAllBtn = document.getElementById('markAllRead');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            await fetch(`${BASE_URL}/modules/shared/mark_notif_read.php?all=1`);
            // Reset UI
            document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
            document.querySelector('.notif-badge')?.remove();
            markAllBtn.remove();
        });
    }

    // ── KONFIRMASI HAPUS ──────────────────────────────────────
    /**
     * Pasang event listener pada semua tombol [data-confirm].
     * Contoh HTML: <a href="..." data-confirm="Yakin ingin menghapus?">Hapus</a>
     */
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            const msg = this.dataset.confirm || 'Yakin ingin melanjutkan?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // ── FLASH MESSAGE AUTO HIDE ───────────────────────────────
    const flashMsg = document.getElementById('flashMsg');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.4s ease';
            flashMsg.style.opacity = '0';
            setTimeout(() => flashMsg.remove(), 400);
        }, 4500);
    }

    // ── LOGOUT KONFIRMASI ─────────────────────────────────────
    const btnLogout = document.getElementById('btnLogout');
    if (btnLogout) {
        btnLogout.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Yakin ingin keluar dari sistem?')) {
                window.location.href = this.href;
            }
        });
    }

    // ── HELPER: Escape HTML untuk mencegah XSS dari data JSON ─
    window.escHtml = function (str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };
});

// BASE_URL tersedia karena di-print oleh PHP di layout
// Jika belum ada, fallback ke window.location.origin
if (typeof BASE_URL === 'undefined') {
    window.BASE_URL = window.location.origin;
}

/* ============================================================
   MODAL HELPERS — showModal / hideModal
   Semua halaman yang menggunakan class="modal" dan memanggil
   showModal('idModal') / hideModal('idModal') dari onclick
   ============================================================ */

/**
 * Buka modal dengan id tertentu.
 * Cukup tambahkan class "open" yang sudah di-style di main.css.
 */
window.showModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
};

/**
 * Tutup modal dengan id tertentu.
 */
window.hideModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('open');
        document.body.style.overflow = '';
    }
};

/**
 * Klik di luar area .modal-content → tutup modal.
 * Event delegation: satu listener di document level.
 */
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal') && e.target.classList.contains('open')) {
        e.target.classList.remove('open');
        document.body.style.overflow = '';
    }
});

/**
 * Tutup modal dengan tombol Escape.
 */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.open').forEach(function (m) {
            m.classList.remove('open');
            document.body.style.overflow = '';
        });
    }
});

/* ============================================================
   CONFIRM DELETE — confirmDelete(url, msg?)
   Tombol hapus di semua halaman memanggil fungsi ini
   untuk menampilkan konfirmasi sebelum navigasi ke URL hapus.
   ============================================================ */

/**
 * Tampilkan dialog konfirmasi sebelum redirect ke URL hapus.
 * @param {string} url  - URL handler hapus (GET)
 * @param {string} msg  - Pesan konfirmasi (opsional)
 */
window.confirmDelete = function (url, msg) {
    const message = msg || 'Yakin ingin menghapus data ini? Tindakan tidak dapat dibatalkan.';
    if (window.confirm(message)) {
        window.location.href = url;
    }
};

