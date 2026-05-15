<?php
/**
 * views/guru/builder_materi.php
 */
Auth::requireRole('guru');
$db = Database::getInstance();

$jm_id = $_GET['jm_id'] ?? 0;
$modul_id = $_GET['modul_id'] ?? 0;
$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
        exit;
    }
    
    $judul = trim($_POST['judul'] ?? '');
    $isi_teks = trim($_POST['isi_teks'] ?? '');
    
    // File upload logic
    $filePath = null;
    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] != UPLOAD_ERR_NO_FILE) {
        $filePath = uploadFile($_FILES['file_materi'], UPLOAD_MATERI, ALLOWED_FILE_EXT);
        if ($filePath === false) {
            setFlash('error', 'Gagal upload file materi.');
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    
    if ($id) {
        // Update
        $item = $db->queryOne("SELECT file_path FROM modul_item WHERE id=?", 'i', [$id]);
        $newFile = $filePath ? $filePath : $item['file_path'];
        if ($filePath && $item['file_path'] && file_exists(UPLOAD_MATERI . $item['file_path'])) {
            unlink(UPLOAD_MATERI . $item['file_path']);
        }
        
        $db->execute(
            "UPDATE modul_item SET judul=?, isi_teks=?, file_path=? WHERE id=?",
            'sssi', [$judul, $isi_teks, $newFile, $id]
        );
        setFlash('success', 'Materi berhasil diupdate.');
    } else {
        // Insert
        $urutan = $db->queryOne("SELECT MAX(urutan) as m FROM modul_item WHERE modul_id=?", 'i', [$modul_id])['m'] ?? 0;
        $db->execute(
            "INSERT INTO modul_item (modul_id, tipe, judul, isi_teks, file_path, urutan) VALUES (?, 'materi', ?, ?, ?, ?)",
            'isssi', [$modul_id, $judul, $isi_teks, $filePath, $urutan+1]
        );
        setFlash('success', 'Materi berhasil ditambahkan.');
    }
    header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
    exit;
}

$item = null;
if ($id) {
    $item = $db->queryOne("SELECT * FROM modul_item WHERE id=?", 'i', [$id]);
}

$pageTitle = $id ? 'Edit Materi' : 'Buat Materi Baru';
ob_start();
?>

<div class="mb-4">
    <a href="?page=g_course&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali ke Silabus</a>
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">📝 <?= $id ? 'Edit' : 'Buat' ?> Materi Bacaan</span></div>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        
        <div class="form-group">
            <label>Judul Materi</label>
            <input type="text" name="judul" class="form-control" value="<?= e($item['judul'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Isi Materi (Teks/Artikel)</label>
            <!-- Di sistem produksi nyata, kita bisa pasang CKEditor/TinyMCE di sini -->
            <textarea name="isi_teks" class="form-control" rows="15" placeholder="Tuliskan materi pelajaran di sini... (Mendukung tag HTML sederhana jika diperlukan)"><?= e($item['isi_teks'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Lampiran File (Opsional, PDF/Word/Video)</label>
            <?php if($item && $item['file_path']): ?>
                <div class="mb-2">
                    <a href="<?= BASE_URL ?>/modules/file_server.php?type=materi&file=<?= urlencode($item['file_path']) ?>&inline=1" target="_blank" class="badge badge-info">
                        <i class='bx bx-link-external'></i> Lihat File Saat Ini (<?= strtoupper(pathinfo($item['file_path'], PATHINFO_EXTENSION)) ?>)
                    </a>
                </div>
            <?php endif; ?>
            <input type="file" name="file_materi" class="form-control">
            <small class="text-muted">Max 10MB. Format: PDF, Word, PPT, Excel, JPG, PNG. Jika upload baru, file lama akan tertimpa.</small>
        </div>
        
        <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Simpan Materi</button>
    </form>
</div>

<!-- Tambahkan Summernote Lite (Tanpa Bootstrap) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('textarea[name="isi_teks"]').summernote({
            height: 350,
            placeholder: 'Tuliskan materi pelajaran di sini... Anda juga bisa memasukkan gambar/foto langsung ke dalam teks ini.',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                // Konfigurasi ini opsional, secara bawaan Summernote akan mengkonversi 
                // gambar menjadi Base64 string yang akan disimpan di database.
                // Jika ingin upload ke server secara asinkron, bisa handle di 'onImageUpload'
            }
        });
        
        // Memastikan background Summernote sesuai dengan tema yang ada
        $('.note-editor').css({'background-color': '#fff', 'border-color': 'var(--border)'});
        $('.note-toolbar').css({'background-color': '#f8f9fa'});
    });
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
