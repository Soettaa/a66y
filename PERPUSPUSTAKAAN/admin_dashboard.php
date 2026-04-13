<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["level"]) || $_SESSION["level"] !== "admin") {
    header("Location: index.php");
    exit;
}

$total_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM anggota WHERE level='user'"))['c'];
$total_buku    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM buku"))['c'];
$total_pinjam  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM peminjaman WHERE status='dipinjam'"))['c'];
$total_telat   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM peminjaman WHERE status='telat'"))['c'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — PERPUSPUSTAKAAN</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #1a1a2e;
        }
        .container { max-width: 1100px; margin: 0 auto; }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .logo-name { font-size: 18px; font-weight: 700; }
        .logo-sub  { font-size: 12px; color: #6b7280; }
        .btn-logout {
            font-size: 13px; color: #dc2626; text-decoration: none;
            font-weight: 600; border: 1.5px solid #fecaca;
            padding: 6px 14px; border-radius: 7px; transition: background 0.15s;
        }
        .btn-logout:hover { background: #fef2f2; }

        /* nav */
        .navigation {
            display: flex; gap: 20px;
            margin-bottom: 1.75rem; justify-content: flex-end;
        }
        .btn-nav {
            background: transparent; font-size: 13px; color: #4f46e5;
            text-decoration: none; font-weight: 600; padding: 6px 14px;
            border: 1.5px solid #c7d2fe; border-radius: 7px; transition: background 0.15s;
        }
        .btn-nav:hover { background: #eef2ff; }
        .btn-nav.active { background: #4f46e5; color: #fff; border-color: #4f46e5; }

        .page-title { font-size: 22px; font-weight: 700; margin-bottom: 1.25rem; }

        /* stat cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 1.25rem 1.5rem;
        }
        .stat-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .stat-value.red { color: #dc2626; }

        /* shortcut cards */
        .shortcuts-title { font-size: 14px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }
        .shortcuts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .shortcut {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 1.25rem 1.5rem;
            text-decoration: none;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: box-shadow 0.15s, transform 0.15s;
        }
        .shortcut:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.11); transform: translateY(-1px); }
        .shortcut-left { display: flex; flex-direction: column; gap: 3px; }
        .shortcut-name { font-size: 15px; font-weight: 700; }
        .shortcut-desc { font-size: 12px; color: #6b7280; }
        .shortcut-arrow { font-size: 18px; color: #c7d2fe; }

        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .shortcuts { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 560px) {
            .shortcuts { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div>
            <div class="logo-name">PERPUSPUSTAKAAN</div>
            <div class="logo-sub">Admin Panel</div>
        </div>
        <a href="logout.php" class="btn-logout">Logout</a>
    </header>

    <div class="navigation">
        <a href="admin_dashboard.php" class="btn-nav active">Dashboard</a>
        <a href="anggota.php"         class="btn-nav">Anggota</a>
        <a href="buku.php"            class="btn-nav">Buku</a>
        <a href="peminjaman.php"      class="btn-nav">Peminjaman</a>
    </div>

    <div class="page-title">Dashboard</div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Anggota</div>
            <div class="stat-value"><?php echo $total_anggota; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Koleksi Buku</div>
            <div class="stat-value"><?php echo $total_buku; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-value"><?php echo $total_pinjam; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Terlambat</div>
            <div class="stat-value red"><?php echo $total_telat; ?></div>
        </div>
    </div>

    <div class="shortcuts-title">Aksi Cepat</div>
    <div class="shortcuts">
        <a href="anggota.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Kelola Anggota</div>
                <div class="shortcut-desc">Lihat & hapus anggota</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
        <a href="buku.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Kelola Buku</div>
                <div class="shortcut-desc">Lihat & hapus buku</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
        <a href="peminjaman.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Kelola Peminjaman</div>
                <div class="shortcut-desc">Update status & hapus</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
        <a href="simpan_anggota.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Tambah Anggota</div>
                <div class="shortcut-desc">Daftarkan anggota baru</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
        <a href="simpan_buku.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Tambah Buku</div>
                <div class="shortcut-desc">Input buku baru</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
        <a href="simpan_peminjaman.php" class="shortcut">
            <div class="shortcut-left">
                <div class="shortcut-name">Catat Peminjaman</div>
                <div class="shortcut-desc">Input peminjaman baru</div>
            </div>
            <span class="shortcut-arrow">→</span>
        </a>
    </div>

</div>
</body>
</html>