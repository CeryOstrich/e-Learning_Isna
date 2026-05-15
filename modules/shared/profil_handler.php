<?php
/**
 * modules/shared/profil_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireLogin();
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=profil');
        exit;
    }

    $nama = trim($_POST['nama'] ?? '');
    $password_baru = $_POST['password_baru'] ?? '';
    $user_id = $_SESSION['user_id'];
    
    if (!$nama) {
        setFlash('error', 'Nama tidak boleh kosong.');
        header('Location: ' . BASE_URL . '/index.php?page=profil');
        exit;
    }

    $updateQuery = "UPDATE users SET nama=?";
    $params = [$nama];
    $types = 's';
    
    if ($password_baru) {
        $updateQuery .= ", password=?";
        $params[] = password_hash($password_baru, PASSWORD_BCRYPT, ['cost' => 12]);
        $types .= 's';
    }

    // Handle upload foto profil
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] != UPLOAD_ERR_NO_FILE) {
        $uploadDir = ROOT_PATH . '/uploads/profil/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES["foto_profil"]["name"]));
        $targetFile = $uploadDir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validExt = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($imageFileType, $validExt) && $_FILES["foto_profil"]["size"] <= 2000000) {
            // Hapus foto lama
            $lama = $db->queryOne("SELECT foto_profil FROM users WHERE id=?", 'i', [$user_id]);
            if ($lama && $lama['foto_profil'] && file_exists($uploadDir . $lama['foto_profil'])) {
                unlink($uploadDir . $lama['foto_profil']);
            }
            
            if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $targetFile)) {
                $updateQuery .= ", foto_profil=?";
                $params[] = $fileName;
                $types .= 's';
                $_SESSION['foto_profil'] = $fileName; // Update session
            }
        } else {
            setFlash('error', 'File foto tidak valid atau terlalu besar (Max 2MB).');
            header('Location: ' . BASE_URL . '/index.php?page=profil');
            exit;
        }
    }

    $updateQuery .= " WHERE id=?";
    $params[] = $user_id;
    $types .= 'i';

    $db->execute($updateQuery, $types, $params);
    $_SESSION['nama'] = $nama; // Update session
    
    setFlash('success', 'Profil berhasil diupdate.');
}

header('Location: ' . BASE_URL . '/index.php?page=profil');
exit;
