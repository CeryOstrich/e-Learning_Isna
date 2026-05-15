<?php
/**
 * modules/guru/forum_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=g_forum');
        exit;
    }

    $jm_id     = $_POST['jadwal_mengajar_id'] ?? 0;
    $judul     = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($action === 'add') {
        // Verifikasi
        $cek = $db->queryOne("SELECT id FROM jadwal_mengajar WHERE id=? AND guru_id=?", 'ii', [$jm_id, $_SESSION['user_id']]);
        if ($cek && $judul) {
            $db->execute(
                "INSERT INTO forum_thread (jadwal_mengajar_id, judul, deskripsi, dibuat_oleh) VALUES (?, ?, ?, ?)",
                'issi', [$jm_id, $judul, $deskripsi, $_SESSION['user_id']]
            );
            
            // Notify students
            $siswa = $db->queryAll("SELECT user_id FROM kelas_siswa ks JOIN jadwal_mengajar jm ON jm.kelas_id = ks.kelas_id WHERE jm.id=?", 'i', [$jm_id]);
            foreach ($siswa as $s) {
                $db->execute("INSERT INTO notifikasi (user_id, pesan, link) VALUES (?, ?, ?)", 'iss', [
                    $s['user_id'],
                    "Forum Baru: " . $judul,
                    "?page=s_forum"
                ]);
            }
            
            setFlash('success', 'Thread diskusi berhasil dibuat.');
        } else {
            setFlash('error', 'Gagal membuat forum diskusi.');
        }
    } 
    elseif ($action === 'reply') {
        $thread_id = $_POST['thread_id'] ?? 0;
        $pesan     = trim($_POST['pesan'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        
        $cek_thread = $db->queryOne(
            "SELECT ft.id FROM forum_thread ft JOIN jadwal_mengajar jm ON jm.id=ft.jadwal_mengajar_id WHERE ft.id=? AND jm.guru_id=?",
            'ii', [$thread_id, $_SESSION['user_id']]
        );
        
        if ($cek_thread && $pesan) {
            $db->execute(
                "INSERT INTO forum_reply (thread_id, user_id, pesan, parent_id) VALUES (?, ?, ?, ?)",
                'iisi', [$thread_id, $_SESSION['user_id'], $pesan, $parent_id]
            );
            setFlash('success', 'Balasan berhasil dikirim.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_forum&id=$thread_id");
        exit;
    }
    elseif ($action === 'edit_reply') {
        $reply_id = $_POST['reply_id'] ?? 0;
        $pesan = trim($_POST['pesan'] ?? '');
        $thread_id = $_POST['thread_id'] ?? 0;

        if ($reply_id && $pesan) {
            $db->execute("UPDATE forum_reply SET pesan=? WHERE id=? AND user_id=?", 'sii', [$pesan, $reply_id, $_SESSION['user_id']]);
            setFlash('success', 'Balasan berhasil diubah.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_forum&id=$thread_id");
        exit;
    }
    elseif ($action === 'react') {
        $reply_id = $_POST['reply_id'] ?? 0;
        $reaction = $_POST['reaction_type'] ?? '';
        $thread_id = $_POST['thread_id'] ?? 0;

        if ($reply_id && $reaction) {
            $cek = $db->queryOne("SELECT id FROM forum_reaction WHERE reply_id=? AND user_id=?", 'ii', [$reply_id, $_SESSION['user_id']]);
            if ($cek) {
                $db->execute("DELETE FROM forum_reaction WHERE id=?", 'i', [$cek['id']]);
            } else {
                $db->execute("INSERT INTO forum_reaction (reply_id, user_id, reaction_type) VALUES (?, ?, ?)", 'iis', [$reply_id, $_SESSION['user_id'], $reaction]);
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=g_forum&id=$thread_id");
        exit;
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute(
            "DELETE ft FROM forum_thread ft JOIN jadwal_mengajar jm ON jm.id = ft.jadwal_mengajar_id 
             WHERE ft.id=? AND jm.guru_id=?",
            'ii', [$id, $_SESSION['user_id']]
        );
        setFlash('success', 'Forum diskusi berhasil dihapus.');
    } elseif ($action === 'delete_reply') {
        $id = $_GET['id'] ?? 0;
        $thread_id = $_GET['thread_id'] ?? 0;
        
        // Guru can delete any reply in their thread
        $cek = $db->queryOne("SELECT fr.id FROM forum_reply fr JOIN forum_thread ft ON ft.id=fr.thread_id JOIN jadwal_mengajar jm ON jm.id=ft.jadwal_mengajar_id WHERE fr.id=? AND jm.guru_id=?", 'ii', [$id, $_SESSION['user_id']]);
        if ($cek) {
            $db->execute("DELETE FROM forum_reply WHERE id=?", 'i', [$id]);
            setFlash('success', 'Balasan berhasil dihapus.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_forum&id=$thread_id");
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php?page=g_forum');
exit;
