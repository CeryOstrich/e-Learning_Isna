<?php
Auth::requireLogin();
$db = Database::getInstance();

$notifikasi = $db->queryAll("SELECT * FROM notifikasi WHERE user_id=? ORDER BY created_at DESC LIMIT 50", 'i', [$_SESSION['user_id']]);

// Tandai semua sebagai dibaca jika halaman ini dibuka
$db->execute("UPDATE notifikasi SET dibaca=1 WHERE user_id=?", 'i', [$_SESSION['user_id']]);

$pageTitle = 'Semua Notifikasi';
ob_start();
?>

<div class="card" style="max-width:800px; margin:0 auto;">
    <div class="card-header"><span class="card-title">🔔 Riwayat Notifikasi</span></div>
    
    <div style="display:flex; flex-direction:column; gap:10px;">
        <?php if(empty($notifikasi)): ?>
            <div class="text-center text-muted" style="padding:20px;">Anda belum memiliki notifikasi.</div>
        <?php else: ?>
            <?php foreach($notifikasi as $n): ?>
            <a href="<?= e($n['link'] ?: '#') ?>" style="display:block; padding:15px; background:var(--bg); border-radius:8px; text-decoration:none; color:var(--text); border-left:4px solid <?= $n['dibaca'] ? 'var(--border)' : 'var(--primary)' ?>; transition:0.3s;">
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <strong><?= e($n['pesan']) ?></strong>
                    <span style="font-size:0.8rem; color:var(--text-muted);"><?= date('d M H:i', strtotime($n['created_at'])) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
