<?php
/**
 * core/Gamifikasi.php — Engine utama sistem gamifikasi.
 *
 * Cara pakai:
 *   Gamifikasi::tambahXP($user_id, 20, 'Mengumpulkan tugas tepat waktu');
 *   $stats = Gamifikasi::getStats($user_id);
 *   $board = Gamifikasi::getLeaderboard($kelas_id);
 */

class Gamifikasi
{
    // ── Konstanta XP per aktivitas ────────────────────────────────────────────
    const XP_MATERI_SELESAI     = 10;
    const XP_TUGAS_TEPAT_WAKTU  = 20;
    const XP_TUGAS_TERLAMBAT    = 5;
    const XP_KUIS_SELESAI       = 15;
    const XP_KUIS_SEMPURNA      = 25;   // bonus jika skor = 100
    const XP_FORUM_POST         = 5;

    // ── XP yang dibutuhkan per level (level = floor(sqrt(XP/50)) + 1) ────────
    // Formula: Level = floor(sqrt(total_xp / 50)) + 1
    // Level 1 = 0 XP, Level 2 = 50 XP, Level 3 = 200 XP, Level 4 = 450 XP, dst.

    // ── Daftar semua badge yang tersedia ─────────────────────────────────────
    const BADGES = [
        'pemula_bersemangat' => [
            'nama'  => 'Pemula Bersemangat',
            'ikon'  => '🔥',
            'desc'  => 'Menyelesaikan 1 materi pertama',
        ],
        'rajin_belajar' => [
            'nama'  => 'Rajin Belajar',
            'ikon'  => '📚',
            'desc'  => 'Menyelesaikan 10 materi',
        ],
        'kilat_tepat_waktu' => [
            'nama'  => 'Kilat Tepat Waktu',
            'ikon'  => '⚡',
            'desc'  => 'Mengumpulkan tugas tepat waktu sebanyak 5x',
        ],
        'juara_kuis' => [
            'nama'  => 'Juara Kuis',
            'ikon'  => '🏆',
            'desc'  => 'Mendapatkan skor sempurna di kuis',
        ],
        'aktif_diskusi' => [
            'nama'  => 'Aktif Diskusi',
            'ikon'  => '💬',
            'desc'  => 'Memposting 10x di forum diskusi',
        ],
        'level_5' => [
            'nama'  => 'Level Master',
            'ikon'  => '🌟',
            'desc'  => 'Mencapai Level 5',
        ],
        'marathon_belajar' => [
            'nama'  => 'Marathon Belajar',
            'ikon'  => '🎯',
            'desc'  => 'Mengumpulkan lebih dari 200 XP',
        ],
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // METODE UTAMA
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tambah XP untuk user tertentu.
     * Otomatis: update level, cek & berikan badge baru.
     *
     * @return array ['xp_baru' => int, 'badge_baru' => array]
     */
    public static function tambahXP(int $user_id, int $jumlah, string $keterangan): array
    {
        $db = Database::getInstance();

        // 1. Catat log transaksi XP
        $db->execute(
            "INSERT INTO xp_log (user_id, jumlah, keterangan) VALUES (?, ?, ?)",
            'iis', [$user_id, $jumlah, $keterangan]
        );

        // 2. Upsert total XP di user_xp
        $db->execute(
            "INSERT INTO user_xp (user_id, total_xp) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE total_xp = total_xp + VALUES(total_xp)",
            'ii', [$user_id, $jumlah]
        );

        // 3. Hitung level baru
        $row = $db->queryOne("SELECT total_xp FROM user_xp WHERE user_id=?", 'i', [$user_id]);
        $totalXP  = (int)($row['total_xp'] ?? 0);
        $levelBaru = self::hitungLevel($totalXP);

        $db->execute(
            "UPDATE user_xp SET level=? WHERE user_id=?",
            'ii', [$levelBaru, $user_id]
        );

        // 4. Cek & berikan badge baru
        $badgeBaru = self::cekBadge($user_id);

        return [
            'xp_total'  => $totalXP,
            'level'     => $levelBaru,
            'badge_baru'=> $badgeBaru,
        ];
    }

    /**
     * Hitung level dari total XP.
     * Formula: Level = floor(sqrt(total_xp / 50)) + 1
     */
    public static function hitungLevel(int $totalXP): int
    {
        return (int)floor(sqrt($totalXP / 50)) + 1;
    }

    /**
     * Hitung berapa XP yang dibutuhkan untuk naik ke level berikutnya.
     */
    public static function xpUntukLevelBerikutnya(int $level): int
    {
        // XP minimum level N = (N-1)^2 * 50
        return ($level * $level) * 50;
    }

    /**
     * Evaluasi semua badge dan berikan yang belum diraih.
     *
     * @return array Badge-badge baru yang baru saja diraih (untuk notifikasi).
     */
    public static function cekBadge(int $user_id): array
    {
        $db = Database::getInstance();
        $badgeBaru = [];

        // Ambil badge yang sudah diraih
        $sudahPunya = $db->queryAll(
            "SELECT badge_slug FROM user_badge WHERE user_id=?", 'i', [$user_id]
        );
        $daftarPunya = array_column($sudahPunya, 'badge_slug');

        // ── Ambil data aktivitas user untuk dievaluasi ────────────────────────

        // Jumlah materi yang sudah diselesaikan
        $jumlahMateri = (int)($db->queryOne(
            "SELECT COUNT(*) c FROM xp_log WHERE user_id=? AND keterangan LIKE '%materi%'",
            'i', [$user_id]
        )['c'] ?? 0);

        // Jumlah tugas tepat waktu
        $tugasTepat = (int)($db->queryOne(
            "SELECT COUNT(*) c FROM xp_log WHERE user_id=? AND keterangan LIKE '%tepat waktu%'",
            'i', [$user_id]
        )['c'] ?? 0);

        // Apakah pernah dapat kuis sempurna
        $kuisSempurna = (int)($db->queryOne(
            "SELECT COUNT(*) c FROM xp_log WHERE user_id=? AND keterangan LIKE '%sempurna%'",
            'i', [$user_id]
        )['c'] ?? 0);

        // Jumlah posting forum
        $forumPost = (int)($db->queryOne(
            "SELECT COUNT(*) c FROM xp_log WHERE user_id=? AND keterangan LIKE '%forum%'",
            'i', [$user_id]
        )['c'] ?? 0);

        // Total XP & Level
        $row    = $db->queryOne("SELECT total_xp, level FROM user_xp WHERE user_id=?", 'i', [$user_id]);
        $xp     = (int)($row['total_xp'] ?? 0);
        $level  = (int)($row['level']    ?? 1);

        // ── Evaluasi setiap badge ─────────────────────────────────────────────
        $evaluasi = [
            'pemula_bersemangat' => $jumlahMateri >= 1,
            'rajin_belajar'      => $jumlahMateri >= 10,
            'kilat_tepat_waktu'  => $tugasTepat   >= 5,
            'juara_kuis'         => $kuisSempurna >= 1,
            'aktif_diskusi'      => $forumPost    >= 10,
            'level_5'            => $level        >= 5,
            'marathon_belajar'   => $xp           >= 200,
        ];

        foreach ($evaluasi as $slug => $terpenuhi) {
            if ($terpenuhi && !in_array($slug, $daftarPunya, true)) {
                $db->execute(
                    "INSERT IGNORE INTO user_badge (user_id, badge_slug) VALUES (?, ?)",
                    'is', [$user_id, $slug]
                );
                $badgeBaru[] = self::BADGES[$slug];
            }
        }

        return $badgeBaru;
    }

    /**
     * Ambil statistik lengkap gamifikasi seorang user.
     *
     * @return array ['total_xp', 'level', 'xp_next_level', 'xp_progress_persen', 'badges', 'log_terbaru']
     */
    public static function getStats(int $user_id): array
    {
        $db = Database::getInstance();

        $row     = $db->queryOne("SELECT total_xp, level FROM user_xp WHERE user_id=?", 'i', [$user_id]);
        $totalXP = (int)($row['total_xp'] ?? 0);
        $level   = (int)($row['level']    ?? 1);

        $xpLevelSekarang  = (($level - 1) * ($level - 1)) * 50; // XP minimal level ini
        $xpLevelBerikutnya = ($level * $level) * 50;             // XP minimal level berikutnya
        $xpDiLevel         = $totalXP - $xpLevelSekarang;
        $xpBtnLevel        = $xpLevelBerikutnya - $xpLevelSekarang;
        $persen            = $xpBtnLevel > 0 ? min(100, round(($xpDiLevel / $xpBtnLevel) * 100)) : 100;

        $badges = $db->queryAll(
            "SELECT badge_slug, earned_at FROM user_badge WHERE user_id=? ORDER BY earned_at DESC",
            'i', [$user_id]
        );

        $logTerbaru = $db->queryAll(
            "SELECT jumlah, keterangan, created_at FROM xp_log WHERE user_id=? ORDER BY created_at DESC LIMIT 5",
            'i', [$user_id]
        );

        return [
            'total_xp'          => $totalXP,
            'level'             => $level,
            'xp_next_level'     => $xpLevelBerikutnya,
            'xp_progress_persen'=> $persen,
            'xp_di_level'       => $xpDiLevel,
            'xp_btn_level'      => $xpBtnLevel,
            'badges'            => $badges,
            'log_terbaru'       => $logTerbaru,
        ];
    }

    /**
     * Ambil leaderboard siswa dalam satu kelas.
     *
     * @param int $kelas_id 0 = leaderboard global semua siswa
     * @return array
     */
    public static function getLeaderboard(int $kelas_id = 0): array
    {
        $db = Database::getInstance();

        if ($kelas_id > 0) {
            // Leaderboard per kelas
            $rows = $db->queryAll(
                "SELECT u.id, u.nama, ux.total_xp, ux.level,
                        (SELECT COUNT(*) FROM user_badge WHERE user_id=u.id) AS jumlah_badge,
                        u.foto_profil
                 FROM users u
                 JOIN kelas_siswa ks ON ks.user_id = u.id AND ks.kelas_id = ?
                 LEFT JOIN user_xp ux ON ux.user_id = u.id
                 WHERE u.role = 'siswa'
                 ORDER BY ux.total_xp DESC, u.nama ASC
                 LIMIT 20",
                'i', [$kelas_id]
            );
        } else {
            // Leaderboard global
            $rows = $db->queryAll(
                "SELECT u.id, u.nama, ux.total_xp, ux.level,
                        (SELECT COUNT(*) FROM user_badge WHERE user_id=u.id) AS jumlah_badge,
                        u.foto_profil
                 FROM users u
                 LEFT JOIN user_xp ux ON ux.user_id = u.id
                 WHERE u.role = 'siswa'
                 ORDER BY ux.total_xp DESC, u.nama ASC
                 LIMIT 20",
                '', []
            );
        }

        // Inject avatar URL & posisi ranking
        foreach ($rows as $i => &$r) {
            $r['rank'] = $i + 1;
            $r['total_xp'] = (int)($r['total_xp'] ?? 0);
            $r['level']    = (int)($r['level']    ?? 1);
            $r['foto_url'] = $r['foto_profil']
                ? BASE_URL . '/uploads/profil/' . $r['foto_profil']
                : 'https://ui-avatars.com/api/?name=' . urlencode($r['nama']) . '&background=1a3a6b&color=fff&bold=true&size=80';
        }
        unset($r);

        return $rows;
    }

    /**
     * Ambil nama lengkap level berdasarkan nomor level.
     */
    public static function getNamaLevel(int $level): string
    {
        $levels = [
            1 => 'Penjelajah',
            2 => 'Pelajar',
            3 => 'Cendekia',
            4 => 'Ilmuwan',
            5 => 'Sarjana',
            6 => 'Magister',
            7 => 'Doktor',
        ];
        return $levels[$level] ?? 'Master Lv.' . $level;
    }
}
