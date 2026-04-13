<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION["level"]) && $_SESSION["level"] === "admin") {
    header("Location: admin_dashboard.php");
    exit;
}

$ida      = $_SESSION["ida"] ?? null;
$username = $_SESSION["username"] ?? "Anggota";

function fetchQ($conn, $query) {
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

$semua_buku      = fetchQ($conn, "SELECT * FROM buku ORDER BY judul ASC");
$peminjaman_saya = $ida ? fetchQ($conn,
    "SELECT p.*, b.judul, b.pengarang FROM peminjaman p
     JOIN buku b ON p.id_buku = b.id_buku
     WHERE p.id_anggota = $ida ORDER BY p.waktu_pinjam DESC") : [];

$active_tab = $_GET['tab'] ?? 'buku';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSPUSTAKAAN</title>
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
        .navigation {
            display: flex; gap: 20px;
            margin-bottom: 1rem; justify-content: flex-end;
        }
        .btn-nav {
            background: transparent; font-size: 13px; color: #4f46e5;
            text-decoration: none; font-weight: 600; padding: 6px 14px;
            border: 1.5px solid #c7d2fe; border-radius: 7px; transition: background 0.15s;
        }
        .btn-nav:hover { background: #eef2ff; }
        .btn-nav.active { background: #4f46e5; color: #fff; border-color: #4f46e5; }
        .page-title { font-size: 22px; font-weight: 700; margin-bottom: 1.25rem; }
        .table-wrap {
            background: #fff; border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead tr { background: #f8f9ff; border-bottom: 2px solid #e5e7eb; }
        thead th {
            padding: 13px 16px; text-align: left; font-size: 12px;
            font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; color: #6b7280;
        }
        tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }
        td { padding: 12px 16px; vertical-align: middle; color: #374151; }
        .badge {
            display: inline-block; padding: 3px 10px;
            border-radius: 999px; font-size: 12px; font-weight: 600;
        }
        .badge-ada      { background: #dbeafe; color: #1e40af; }
        .badge-kosong   { background: #fef9c3; color: #850e0e; }
        .badge-dipinjam { background: #dbeafe; color: #1e40af; }
        .badge-kembali  { background: #dcfce7; color: #166534; }
        .badge-telat    { background: #fee2e2; color: #991b1b; }
        .empty { text-align: center; padding: 3rem; color: #9ca3af; font-size: 15px; }
        @media (max-width: 768px) { table { font-size: 13px; } td, th { padding: 10px; } }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div>
            <div class="logo-name">PERPUSPUSTAKAAN</div>
            <div class="logo-sub">Halo, <?php echo htmlspecialchars($username); ?></div>
        </div>
        <a href="logout.php" class="btn-logout">Logout</a>
    </header>

    <div class="navigation">
        <a href="?tab=buku"       class="btn-nav <?php echo $active_tab === 'buku'       ? 'active' : ''; ?>">Buku</a>
        <a href="?tab=peminjaman" class="btn-nav <?php echo $active_tab === 'peminjaman' ? 'active' : ''; ?>">Pinjaman Saya</a>
    </div>

    <?php if ($active_tab === 'buku'): ?>

    <div class="page-title">Daftar Buku</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($semua_buku)): ?>
                <tr><td colspan="6" class="empty">Belum ada buku.</td></tr>
            <?php endif; ?>
            <?php foreach ($semua_buku as $b): ?>
            <tr>
                <td style="color:#9ca3af;font-size:13px;"><?php echo $b['id_buku']; ?></td>
                <td style="font-weight:600;"><?php echo htmlspecialchars($b['judul']); ?></td>
                <td><?php echo htmlspecialchars($b['pengarang']); ?></td>
                <td style="color:#6b7280;"><?php echo htmlspecialchars($b['penerbit']); ?></td>
                <td><?php echo htmlspecialchars($b['kategori']); ?></td>
                <td>
                    <?php if ($b['qty'] > 0): ?>
                        <span class="badge badge-ada">Tersedia (<?php echo $b['qty']; ?>)</span>
                    <?php else: ?>
                        <span class="badge badge-kosong">Kosong</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>

    <div class="page-title">Pinjaman Saya</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Judul</th><th>Pengarang</th>
                    <th>Waktu Pinjam</th><th>Tenggat</th><th>Dikembalikan</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($peminjaman_saya)): ?>
                <tr><td colspan="7" class="empty">Belum ada riwayat peminjaman.</td></tr>
            <?php endif; ?>
            <?php foreach ($peminjaman_saya as $p): ?>
            <tr>
                <td style="color:#9ca3af;font-size:13px;"><?php echo $p['id_peminjaman']; ?></td>
                <td style="font-weight:600;"><?php echo htmlspecialchars($p['judul']); ?></td>
                <td><?php echo htmlspecialchars($p['pengarang']); ?></td>
                <td><?php echo htmlspecialchars($p['waktu_pinjam']); ?></td>
                <td><?php echo htmlspecialchars($p['tenggat_pinjam']); ?></td>
                <td><?php echo $p['waktu_kembali'] ?: '—'; ?></td>
                <td>
                    <?php if ($p['status'] === 'dipinjam'): ?>
                        <span class="badge badge-dipinjam">Dipinjam</span>
                    <?php elseif ($p['status'] === 'kembali'): ?>
                        <span class="badge badge-kembali">Kembali</span>
                    <?php else: ?>
                        <span class="badge badge-telat">Telat</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</div>
</body>
</html>