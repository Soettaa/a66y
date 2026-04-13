<?php 
session_start();
include "koneksi.php";

function fetch($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

$buku = fetch("SELECT * FROM buku");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar buku — PERPUSPUSTAKAAN</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #e2e8f0;
        }
        .container { max-width: 1100px; margin: 0 auto; }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .navigation {
            display: flex;
            gap: 12px;
            margin-bottom: 1.5rem;
            justify-content: flex-end;
        }   

        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-name { font-size: 20px; font-weight: 800; color: #60a5fa; }
        .logo-sub { font-size: 12px; color: #94a3b8; }
        .btn-logout {
            font-size: 13px;
            color: #fca5a5;
            text-decoration: none;
            font-weight: 600;
            border: 1.5px solid #7f1d1d;
            padding: 8px 16px;
            border-radius: 6px;
            background: rgba(127, 29, 29, 0.1);
            transition: all 0.2s ease;
        }
        .btn-logout:hover { background: rgba(220, 38, 38, 0.2); }
        .btn-tambah {
            font-size: 13px;
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
            border: 1.5px solid #1e40af;
            padding: 8px 16px;
            border-radius: 6px;
            background: rgba(30, 64, 175, 0.1);
            transition: all 0.2s ease;
        }
        .btn-tambah:hover { background: rgba(96, 165, 250, 0.15); }
        .btn-nav {
            background: transparent;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border: 1.5px solid #334155;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .btn-nav:hover { color: #60a5fa; border-color: #60a5fa; }
        .btn-nav.active {
            background: #1e40af;
            color: #fff;
            border: 1.5px solid #1e40af;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #e2e8f0;
        }
        .table-wrap {
            background: #1e293b;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            overflow: hidden;
            border: 1px solid #334155;
        }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead tr { background: #0f172a; border-bottom: 2px solid #334155; }
        thead th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        tbody tr { border-bottom: 1px solid #334155; transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #334155; }
        td { padding: 13px 16px; vertical-align: middle; color: #cbd5e1; }
        td img {
            width: 72px; height: 54px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #334155;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-out { background: rgba(220, 38, 38, 0.2); color: #fca5a5; }
        .badge-ada   { background: rgba(96, 165, 250, 0.2); color: #60a5fa; }
        select {
            padding: 8px 12px;
            border: 1.5px solid #334155;
            border-radius: 6px;
            font-size: 13px;
            color: #e2e8f0;
            background: #0f172a;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
        }
        select:focus { border-color: #60a5fa; }
        .btn-hapus {
            font-size: 13px;
            color: #fca5a5;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 14px;
            border: 1.5px solid #7f1d1d;
            border-radius: 6px;
            background: rgba(127, 29, 29, 0.1);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-hapus:hover { background: rgba(220, 38, 38, 0.2); }
        .empty {
            text-align: center;
            padding: 3rem;
            color: #64748b;
            font-size: 15px;
        }
        @media (max-width: 768px) {
            table { font-size: 13px; }
            td, th { padding: 10px 10px; }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="logo">
            <div>
                <div class="logo-name">PERPUSPUSTAKAAN</div>
                <div class="logo-sub">List buku</div>
            </div>
        </div>
        <div class="1n">
            <a href="logout.php" class="btn-logout">Logout</a>
            <a href="simpan_buku.php" class="btn-tambah">Tambah buku</a>
        </div>
        
    </header>
    <div class="navigation">
        <a  href="anggota.php" class="btn-nav">Anggota</a>
        <a href="buku.php" class="btn-nav active">buku</a>
        <a href="peminjaman.php" class="btn-nav">Peminjaman</a>
    </div>

    <div class="page-title">Daftar buku</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if(empty($buku)): ?>
                <tr><td colspan="7" class="empty">Belum ada buku yang masuk.</td></tr>
            <?php endif; ?>
            <?php foreach($buku as $b): ?>
            <tr>
                <td style="color:#9ca3af;font-size:13px;"><?php echo $b['id_buku']; ?></td>
                <td style="font-weight:600;"><?php echo htmlspecialchars($b['judul']); ?></td>
                <td><?php echo htmlspecialchars($b['pengarang']); ?></td>
                <td style="max-width:180px;color:#6b7280;"><?php echo htmlspecialchars($b['penerbit']); ?></td>
                <td><?php echo htmlspecialchars($b['kategori']); ?></td>
                 <td>
                    <?php if($b['qty'] == 0): ?>
                        <span class="badge badge-out">Kosong</span>
                    <?php else: ?>
                        <span class="badge badge-ada">Tersedia (<?php echo $b['qty']; ?>)</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="hapus_buku.php?id=<?php echo $b['id_buku']; ?>" class="btn-hapus"
                       onclick="return confirm('Hapus buku ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>