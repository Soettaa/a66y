<?php 
session_start();
include "koneksi.php";

if (isset($_POST['status']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE peminjaman SET status = '$status' WHERE id_peminjaman = $id";
    mysqli_query($conn, $sql);
    
    header("Location: peminjaman.php");
    exit;
}

function fetch($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

$isAdmin = isset($_SESSION["level"]) && $_SESSION["level"] === "admin";
$peminjaman = fetch("SELECT * FROM peminjaman");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peminjaman — PERPUSPUSTAKAAN</title>
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
        .badge-over { background: rgba(220, 38, 38, 0.2); color: #fca5a5; }
        .badge-masih   { background: rgba(96, 165, 250, 0.2); color: #60a5fa; }
        .badge-retur  { background: rgba(34, 197, 94, 0.2); color: #86efac; }
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
                <div class="logo-sub">List Peminjaman</div>
            </div>
        </div>
        <div class="1n">
            <a href="logout.php" class="btn-logout">Logout</a>
            <a href="simpan_peminjaman.php" class="btn-tambah">Tambah Peminjaman</a>
        </div>
        
    </header>
    <div class="navigation">
        <a  href="anggota.php" class="btn-nav">Anggota</a>
        <a href="buku.php" class="btn-nav">Buku</a>
        <a href="peminjaman.php" class="btn-nav active">Peminjaman</a>
    </div>

    <div class="page-title">Daftar Peminjaman</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Id Peminjaman</th>
                    <th>Id Anggota</th>
                    <th>Id Buku</th>
                    <th>Waktu Peminjaman</th>
                    <th>Tenggat Peminjaman</th>
                    <th>Waktu Pengembalian</th>
                    <th>Status</th>
                    <?php if($isAdmin): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if(empty($peminjaman)): ?>
                <tr><td colspan="<?php echo $isAdmin ? 8 : 7; ?>" class="empty">Belum ada peminjaman yang masuk.</td></tr>
            <?php endif; ?>
            <?php foreach($peminjaman as $p): ?>
            <tr>
                <td style="color:#9ca3af;font-size:13px;"><?php echo $p['id_peminjaman']; ?></td>
                <td style="font-weight:600;"><?php echo htmlspecialchars($p['id_anggota']); ?></td>
                <td><?php echo htmlspecialchars($p['id_buku']); ?></td>
                <td><?php echo htmlspecialchars($p['waktu_pinjam']); ?></td>
                <td><?php echo htmlspecialchars($p['tenggat_pinjam']); ?></td>
                <td><?php echo $p['waktu_kembali'] ? htmlspecialchars($p['waktu_kembali']) : '-'; ?></td>
                <?php if($isAdmin): ?>
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $p['id_peminjaman']; ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="dipinjam" <?php if($p['status']==='dipinjam') echo 'selected'; ?>>Dipinjam</option>
                            <option value="kembali"   <?php if($p['status']==='kembali') echo 'selected'; ?>>Kembali</option>
                            <option value="telat"  <?php if($p['status']==='telat')  echo 'selected'; ?>>Telat</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="hapus_peminjaman.php?id=<?php echo $p['id_peminjaman']; ?>" class="btn-hapus"
                       onclick="return confirm('Hapus peminjaman ini?');">Hapus</a>
                </td>
                <?php else: ?>
                <td>
                    <?php if($p['status'] == 'dipinjam'): ?>
                        <span class="badge badge-masih">Dipinjam</span>
                    <?php elseif($p['status'] == 'dikembalikan'): ?>
                        <span class="badge badge-retur">Dikembalikan</span>
                    <?php else: ?>
                        <span class="badge badge-over">Telat</span>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>