<?php
/**
 * modules/siswa/forum_handler.php
 * Handler POST untuk balasan forum oleh siswa.
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=s_forum');
        exit;
    }

    if ($action === 'reply') {
        $thread_id = $_POST['thread_id'] ?? 0;
        $pesan     = trim($_POST['pesan'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        
        // Cek apakah thread ada
        $cek_thread = $db->queryOne("SELECT id FROM forum_thread WHERE id=?", 'i', [$thread_id]);
        
        if ($cek_thread && $pesan) {
            $db->execute(
                "INSERT INTO forum_reply (thread_id, user_id, pesan, parent_id) VALUES (?, ?, ?, ?)",
                'iisi', [$thread_id, $_SESSION['user_id'], $pesan, $parent_id]
            );

            // ── Gamifikasi: XP untuk partisipasi forum ────────────────────────
            $hasilForum = Gamifikasi::tambahXP($_SESSION['user_id'], Gamifikasi::XP_FORUM_POST, 'Posting di forum diskusi');
            if (!empty($hasilForum['badge_baru'])) {
                $_SESSION['badge_baru'] = $hasilForum['badge_baru'];
            }
            // ───────────────────────────────────────────────────────────

            setFlash('success', 'Balasan berhasil dikirim. +' . Gamifikasi::XP_FORUM_POST . ' XP 💬');
        } else {
            setFlash('error', 'Gagal mengirim balasan. Thread tidak ditemukan atau pesan kosong.');
        }
        
        header("Location: " . BASE_URL . "/index.php?page=s_forum&id=$thread_id");
        exit;
    }
    elseif ($action === 'edit_reply') {
        $reply_id = $_POST['reply_id'] ?? 0;
        $pesan = trim($_POST['pesan'] ?? '');
        $thread_id = $_POST['thread_id'] ?? 0;

        if ($reply_id && $pesan) {
            $db->execute("UPDATE forum_reply SET pesan=? WHERE id=? AND user_id=?", 'sii', [$pesan, $reply_id, $_SESSION['user_id']]);
            setFlash('success', 'Komentar berhasil diedit.');
        }
        header("Location: " . BASE_URL . "/index.php?page=s_forum&id=$thread_id");
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
        header("Location: " . BASE_URL . "/index.php?page=s_forum&id=$thread_id");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete_reply') {
        $id = $_GET['id'] ?? 0;
        $thread_id = $_GET['thread_id'] ?? 0;
        
        // Siswa hanya bisa menghapus komentarnya sendiri
        $cek = $db->queryOne("SELECT id FROM forum_reply WHERE id=? AND user_id=?", 'ii', [$id, $_SESSION['user_id']]);
        if ($cek) {
            $db->execute("DELETE FROM forum_reply WHERE id=?", 'i', [$id]);
            setFlash('success', 'Komentar berhasil dihapus.');
        } else {
            setFlash('error', 'Anda tidak berhak menghapus komentar ini.');
        }
        header("Location: " . BASE_URL . "/index.php?page=s_forum&id=$thread_id");
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php?page=s_forum');
exit;
