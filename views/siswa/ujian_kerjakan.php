<?php
/**
 * views/siswa/ujian_kerjakan.php — Antarmuka CBT untuk Siswa
 * Fitur: timer countdown, pilgan + esai, auto-submit saat waktu habis
 */
Auth::requireRole('siswa');
$db    = Database::getInstance();
$uid   = $_SESSION['user_id'];
$ujian_id = (int)($_GET['ujian_id'] ?? 0);

// Validasi ujian aktif
$ujian = $db->queryOne(
    "SELECT u.* FROM ujian u
     JOIN jadwal_mengajar jm ON jm.id=u.jadwal_mengajar_id
     JOIN kelas_siswa ks ON ks.kelas_id=jm.kelas_id
     WHERE u.id=? AND ks.user_id=? AND u.status='aktif' LIMIT 1",
    'ii', [$ujian_id, $uid]
);
if (!$ujian) {
    setFlash('error','Ujian tidak ditemukan, tidak aktif, atau Anda tidak terdaftar di kelas ini.');
    redirectTo('index.php?page=s_ujian');
}

// Cek sesi yang sedang berlangsung atau sudah selesai
$sesi = $db->queryOne("SELECT * FROM sesi_ujian WHERE ujian_id=? AND siswa_id=?", 'ii', [$ujian_id, $uid]);

if ($sesi && $sesi['status'] !== 'berlangsung') {
    setFlash('info','Anda sudah mengerjakan ujian ini.');
    redirectTo('index.php?page=s_ujian');
}

// Buat sesi baru jika belum ada
if (!$sesi) {
    $sesi_id = $db->execute(
        "INSERT INTO sesi_ujian (ujian_id, siswa_id) VALUES (?,?)",
        'ii', [$ujian_id, $uid]
    );
    $sesi = ['id' => $sesi_id, 'mulai_at' => date('Y-m-d H:i:s')];
}

$sesi_id = $sesi['id'];

// Ambil soal (acak jika setting aktif)
$orderBy = $ujian['acak_soal'] ? 'RAND()' : 'nomor, id';
$soals   = $db->queryAll("SELECT * FROM soal WHERE ujian_id=? ORDER BY $orderBy", 'i', [$ujian_id]);

// Ambil jawaban yang sudah diisi sebelumnya (jika ada)
$jawabanLama = [];
$jwbs = $db->queryAll("SELECT soal_id, jawaban FROM jawaban_siswa WHERE sesi_ujian_id=?", 'i', [$sesi_id]);
foreach ($jwbs as $j) $jawabanLama[$j['soal_id']] = $j['jawaban'];

// Hitung sisa waktu
$elapsed = time() - strtotime($sesi['mulai_at']);
$sisaDetik = max(0, ($ujian['durasi_menit'] * 60) - $elapsed);

$pageTitle = 'Mengerjakan: ' . $ujian['judul'];
ob_start();
?>
<style>
#cbt-container{display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;}
#timer-box{position:sticky;top:calc(var(--topbar-h) + 20px);background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;}
#timer-display{font-size:2.5rem;font-weight:800;color:var(--primary);font-variant-numeric:tabular-nums;}
#timer-display.urgent{color:var(--danger)!important;animation:pulse 1s infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.5;}}
.soal-item{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:22px;margin-bottom:14px;}
.opsi-label{display:flex;align-items:center;gap:12px;padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--radius);cursor:pointer;margin-bottom:8px;transition:all 0.15s;font-size:0.9rem;}
.opsi-label:hover{border-color:var(--primary);background:var(--bg);}
.opsi-label input[type=radio]{accent-color:var(--primary);width:16px;height:16px;}
.opsi-label:has(input:checked){border-color:var(--primary);background:rgba(26,58,107,0.06);}
textarea.form-control{min-height:100px;}
.nomor-nav{display:grid;grid-template-columns:repeat(5,1fr);gap:6px;margin-top:14px;}
.nomor-btn{aspect-ratio:1;border-radius:8px;border:1.5px solid var(--border);background:var(--surface-2);font-size:0.8rem;font-weight:700;cursor:pointer;transition:all 0.15s;}
.nomor-btn.answered{background:var(--primary);color:white;border-color:var(--primary);}
@media(max-width:768px){#cbt-container{grid-template-columns:1fr;}}
</style>

<form id="cbt-form" method="POST" action="<?= BASE_URL ?>/modules/siswa/ujian_submit.php">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="sesi_ujian_id" value="<?= $sesi_id ?>">
    <input type="hidden" name="ujian_id" value="<?= $ujian_id ?>">

    <div id="cbt-container">
        <!-- ── Soal-soal ───────────────────────────── -->
        <div>
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:16px;">
                📋 <?= e($ujian['judul']) ?>
            </h2>

            <?php foreach ($soals as $i => $s): ?>
            <div class="soal-item" id="soal-<?= $s['id'] ?>">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <span style="background:var(--primary);color:white;min-width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;"><?= $i+1 ?></span>
                    <span class="badge <?= $s['tipe_soal']==='pilgan' ? 'badge-info' : 'badge-warning' ?>"><?= $s['tipe_soal']==='pilgan' ? 'Pilihan Ganda' : 'Esai' ?></span>
                    <span style="font-size:0.78rem;color:var(--text-muted);">+<?= $s['bobot'] ?> poin</span>
                </div>
                <p style="margin-bottom:14px;font-size:0.95rem;line-height:1.7;"><?= e($s['pertanyaan']) ?></p>

                <?php if ($s['tipe_soal'] === 'pilgan'): ?>
                <?php foreach (['a','b','c','d'] as $opt): ?>
                <?php if ($s["opsi_$opt"]): ?>
                <label class="opsi-label">
                    <input type="radio" name="jawaban[<?= $s['id'] ?>]" value="<?= $opt ?>"
                           <?= ($jawabanLama[$s['id']] ?? '') === $opt ? 'checked' : '' ?>
                           onchange="markAnswered(<?= $s['id'] ?>)">
                    <span><strong><?= strtoupper($opt) ?>.</strong> <?= e($s["opsi_$opt"]) ?></span>
                </label>
                <?php endif; ?>
                <?php endforeach; ?>

                <?php else: // Esai ?>
                <textarea name="jawaban[<?= $s['id'] ?>]" class="form-control"
                          placeholder="Tulis jawaban Anda di sini..."
                          onchange="markAnswered(<?= $s['id'] ?>)"><?= e($jawabanLama[$s['id']] ?? '') ?></textarea>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:8px;"
                    onclick="return confirmSubmit()">
                ✅ Kumpulkan Jawaban
            </button>
        </div>

        <!-- ── Panel Kanan: Timer & Navigasi ──────── -->
        <div id="timer-box">
            <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;margin-bottom:6px;">SISA WAKTU</div>
            <div id="timer-display">00:00</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;">
                ⏱ Durasi: <?= $ujian['durasi_menit'] ?> menit
            </div>

            <div style="margin-top:18px;font-size:0.78rem;font-weight:600;color:var(--text-muted);">NAVIGASI SOAL</div>
            <div class="nomor-nav" style="margin-top:8px;">
                <?php foreach ($soals as $i => $s): ?>
                <button type="button" class="nomor-btn <?= isset($jawabanLama[$s['id']]) ? 'answered' : '' ?>"
                        id="nav-<?= $s['id'] ?>"
                        onclick="document.getElementById('soal-<?= $s['id'] ?>').scrollIntoView({behavior:'smooth',block:'center'})">
                    <?= $i+1 ?>
                </button>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:16px;font-size:0.8rem;">
                <span id="answered-count" style="font-weight:700;color:var(--primary);">
                    <?= count($jawabanLama) ?>
                </span> / <?= count($soals) ?> terjawab
            </div>
        </div>
    </div>
</form>

<script>
const SISA_DETIK = <?= $sisaDetik ?>;
const TOTAL_SOAL = <?= count($soals) ?>;
let answered = new Set(<?= json_encode(array_map('intval', array_keys($jawabanLama))) ?>);

// ── Timer ────────────────────────────────────────────
let remaining = SISA_DETIK;
const timerEl = document.getElementById('timer-display');
const countEl = document.getElementById('answered-count');

function updateTimer() {
    if (remaining <= 0) {
        timerEl.textContent = '00:00';
        autoSubmit();
        return;
    }
    const m = String(Math.floor(remaining / 60)).padStart(2, '0');
    const s = String(remaining % 60).padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
    if (remaining <= 60) timerEl.classList.add('urgent');
    remaining--;
}

updateTimer();
const timerInterval = setInterval(updateTimer, 1000);

function autoSubmit() {
    clearInterval(timerInterval);
    alert('Waktu ujian telah habis. Jawaban Anda akan dikumpulkan otomatis.');
    document.getElementById('cbt-form').submit();
}

// ── Mark Answered ─────────────────────────────────
function markAnswered(soalId) {
    answered.add(soalId);
    document.getElementById('nav-' + soalId)?.classList.add('answered');
    countEl.textContent = answered.size;
}

function confirmSubmit() {
    const belum = TOTAL_SOAL - answered.size;
    if (belum > 0) {
        return confirm(`Masih ada ${belum} soal belum terjawab. Yakin ingin mengumpulkan?`);
    }
    return confirm('Kumpulkan semua jawaban? Anda tidak bisa mengubah jawaban setelah dikumpulkan.');
}

// Auto-save setiap 30 detik (via fetch, jaga data jika browser crash)
setInterval(async () => {
    const fd = new FormData(document.getElementById('cbt-form'));
    fd.append('auto_save', '1');
    await fetch('<?= BASE_URL ?>/modules/siswa/ujian_autosave.php', { method: 'POST', body: fd });
}, 30000);
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
