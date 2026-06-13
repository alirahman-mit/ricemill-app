<?php
/**
 * Standalone SQLite migration for Vercel serverless.
 * Creates all tables using raw PDO - no Laravel bootstrap needed.
 */

$dbPath = $_ENV['DB_DATABASE'] ?? '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    @touch($dbPath);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA journal_mode=WAL');
$pdo->exec('PRAGMA foreign_keys=ON');

// ── migrations table ──
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
)");

// ── users ──
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL DEFAULT 'petani',
    email_verified_at DATETIME NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NULL,
    address VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
)");

// ── password_reset_tokens ──
$pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NULL
)");

// ── sessions ──
$pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INTEGER NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON sessions(user_id)");

// ── cache & cache_locks ──
$pdo->exec("CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
)");

// ── jobs, job_batches, failed_jobs ──
$pdo->exec("CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts INTEGER NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS failed_jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

// ── profil_lahans ──
$pdo->exec("CREATE TABLE IF NOT EXISTS profil_lahans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nama_lahan VARCHAR(255) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    luas_lahan DECIMAL(10,2) NOT NULL,
    jenis_tanah VARCHAR(50) NOT NULL DEFAULT 'tanah_liat',
    deskripsi TEXT NULL,
    foto VARCHAR(255) NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── riwayat_panens ──
$pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_panens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    profil_lahan_id INTEGER NOT NULL,
    tanggal_panen DATE NOT NULL,
    jenis_tanaman VARCHAR(255) NOT NULL,
    jumlah_hasil DECIMAL(10,2) NOT NULL,
    satuan VARCHAR(255) NOT NULL DEFAULT 'kg',
    harga_per_kg DECIMAL(10,2) NULL,
    total_pendapatan DECIMAL(15,2) NULL,
    catatan TEXT NULL,
    bukti_foto VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profil_lahan_id) REFERENCES profil_lahans(id) ON DELETE CASCADE
)");

// ── setoran_penggilingan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS setoran_penggilingan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    tanggal_setoran DATE NOT NULL,
    jenis_hasil_panen VARCHAR(255) NOT NULL,
    jumlah_setoran DECIMAL(10,2) NOT NULL,
    biaya_penggilingan DECIMAL(10,2) NULL,
    hasil_bersih DECIMAL(10,2) NULL,
    total_pendapatan DECIMAL(15,2) NULL,
    bukti_nota VARCHAR(255) NULL,
    catatan TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── penerimaan_gabah ──
$pdo->exec("CREATE TABLE IF NOT EXISTS penerimaan_gabah (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nama_petani VARCHAR(255) NOT NULL,
    asal_lahan VARCHAR(255) NULL,
    tanggal DATE NOT NULL,
    jumlah_gabah DECIMAL(10,2) NOT NULL,
    kualitas_gabah VARCHAR(50) NOT NULL DEFAULT 'kering',
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_foto VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── operasional_penggilingan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS operasional_penggilingan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    penerimaan_gabah_id INTEGER NULL,
    batch_id VARCHAR(255) NOT NULL UNIQUE,
    tanggal_proses DATE NOT NULL,
    jumlah_gabah_masuk DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (penerimaan_gabah_id) REFERENCES penerimaan_gabah(id) ON DELETE SET NULL
)");

// ── riwayat_produksi (includes jenis_beras column) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_produksi (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    operasional_id INTEGER NULL,
    batch_id VARCHAR(255) NOT NULL,
    tanggal_proses DATE NOT NULL,
    jumlah_gabah DECIMAL(10,2) NOT NULL,
    jumlah_beras DECIMAL(10,2) NOT NULL,
    jenis_beras VARCHAR(255) NULL,
    notifikasi_rendemen_rendah INTEGER NOT NULL DEFAULT 0,
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (operasional_id) REFERENCES operasional_penggilingan(id) ON DELETE SET NULL
)");

// ── pengiriman_beras (with flexible jenis_beras & status) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS pengiriman_beras (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nama_packager VARCHAR(255) NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jumlah_karung INTEGER NOT NULL,
    berat_per_karung DECIMAL(8,2) NULL,
    tanggal_kirim DATE NOT NULL,
    biaya_logistik DECIMAL(15,2) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_kirim VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── keuangan_ricemill (with VARCHAR kategori) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS keuangan_ricemill (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    kategori VARCHAR(100) NOT NULL DEFAULT 'lainnya',
    tanggal DATE NOT NULL,
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── penerimaan_beras (with flexible jenis_beras) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS penerimaan_beras (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    pengiriman_beras_id INTEGER NULL,
    asal_penggilingan VARCHAR(255) NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jumlah_beras DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_foto VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pengiriman_beras_id) REFERENCES pengiriman_beras(id) ON DELETE SET NULL
)");

// ── hasil_pengemasan (with expanded enums) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS hasil_pengemasan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    penerimaan_beras_id INTEGER NULL,
    tanggal DATE NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jenis_kemasan VARCHAR(50) NOT NULL DEFAULT '5kg',
    jumlah_kemasan INTEGER NOT NULL,
    kualitas VARCHAR(50) NOT NULL DEFAULT 'layak_jual',
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (penerimaan_beras_id) REFERENCES penerimaan_beras(id) ON DELETE SET NULL
)");

// ── pesanan (with VARCHAR jenis_produk) ──
$pdo->exec("CREATE TABLE IF NOT EXISTS pesanan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nama_pelanggan VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    jenis_produk VARCHAR(100) NOT NULL,
    jumlah INTEGER NOT NULL,
    harga_satuan DECIMAL(15,2) NULL,
    total_harga DECIMAL(15,2) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    catatan TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// ── Record all migrations as done ──
$allMigrations = [
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
    '2026_05_05_085132_create_profil_lahans_table',
    '2026_05_05_085138_create_riwayat_panens_table',
    '2026_05_05_085146_create_setoran_penggilingan_table',
    '2026_05_06_085359_add_role_to_users_table',
    '2026_05_06_150001_create_penerimaan_gabah_table',
    '2026_05_06_150002_create_operasional_penggilingan_table',
    '2026_05_06_150003_create_riwayat_produksi_table',
    '2026_05_06_150004_create_pengiriman_beras_table',
    '2026_05_06_150005_create_keuangan_ricemill_table',
    '2026_05_06_150006_create_penerimaan_beras_table',
    '2026_05_06_150007_create_hasil_pengemasan_table',
    '2026_05_06_150008_create_pesanan_table',
    '2026_05_24_090000_add_jenis_beras_to_riwayat_produksi_table',
    '2026_05_25_070000_fix_all_enum_mismatches',
    '2026_05_26_015100_fix_keuangan_kategori_enum',
];

$stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
foreach ($allMigrations as $m) {
    $stmt->execute([$m]);
}
