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

if(isset($_POST['submit'])) {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tenggat = $_POST['tenggat'];

    $sql = "INSERT INTO `peminjaman`(`id_peminjaman`, `id_anggota`, `id_buku`, `waktu_pinjam`, `tenggat_pinjam`, `waktu_kembali`, `status`) 
    VALUES (null,'$id_anggota','$id_buku',NOW(),'$tenggat',NULL,'dipinjam')";
    $result = $conn->query($sql);

    if($result){
        echo "<script>
            alert('peminjaman berhasil ditambahkan!');
            document.location.href = 'peminjaman.php';
            </script>";
    } else {
        echo "<script>
            alert('peminjaman gagal ditambahkan!');
            document.location.href = 'peminjaman.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSPUSTAKAAN — peminjaman</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #e2e8f0;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-name { font-size: 20px; font-weight: 800; color: #60a5fa; }
        header a {
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
        header a:hover { background: rgba(96, 165, 250, 0.15); }
        .card {
            background: #1e293b;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            border: 1px solid #334155;
        }
        .card h1 {
            font-size: 20px;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 0.25rem;
        }
        .card p.sub {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .field { display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 13px; font-weight: 600; color: #cbd5e1; }
        input[type=text], input[type=date] {
            padding: 10px 14px;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-size: 14px;
            color: #e2e8f0;
            background: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }
        input[type=text]:focus, input[type=date]:focus { border-color: #60a5fa; }
        
        select {
            padding: 10px 14px;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-size: 14px;
            color: #e2e8f0;
            background: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }
        select:focus { border-color: #60a5fa; }
        .divider { height: 1px; background: #334155; margin: 1.25rem 0; }
        .actions { display: flex; align-items: center; gap: 12px; }
        button[type=submit] {
            padding: 11px 28px;
            background: #1e40af;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type=submit]:hover { background: #1e3a8a; }
        button[type=reset] {
            padding: 11px 28px;
            background: #334155;
            color: #cbd5e1;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type=reset]:hover { background: #475569; }
        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="logo">
            <div class="logo-name">PERPUSPUSTAKAAN</div>
        </div>
        <a href="peminjaman.php">Kembali</a>
    </header>

    <div class="card">
        <h1>Form Peminjaman</h1>
        <p class="sub">Tambahkan peminjaman baru ke dalam sistem</p>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field">
                    <label for="Id_anggota">Id Anggota</label>
                    <select id="id_anggota" class="form-input" name="id_anggota" required>
                        <option disabled selected value="">--Pilih Id Anggota--</option>
                            <?php
                            $anggota = fetch("SELECT id_anggota, nama_anggota FROM anggota WHERE level = 'user'");
                            foreach ($anggota as $row) {
                            echo '<option value="' . htmlspecialchars($row['id_anggota']) . '">' . htmlspecialchars($row['id_anggota']) . ' - ' . htmlspecialchars($row['nama_anggota']) . '</option>';
                            }
                            ?>
                    </select>
                </div>
                <div class="field">
                    <label for="Id_buku">Id_buku Buku</label>
                    <select id="id_buku" class="form-input" name="id_buku" required>
                        <option disabled selected value="">--Pilih Id Buku--</option>
                            <?php
                            $buku = fetch("SELECT id_buku, judul FROM buku");
                            foreach ($buku as $row) {
                            echo '<option value="' . htmlspecialchars($row['id_buku']) . '">' . htmlspecialchars($row['id_buku']) . ' - ' . htmlspecialchars($row['judul']) . '</option>';
                            }
                            ?>
                    </select>

                </div>
                <div class="field">
                    <label for="tenggat">Tenggat Waktu</label>
                    <input type="date" id="tenggat" name="tenggat" placeholder="Waktu tenggat">
                </div>
            </div>
            <div class="divider"></div>
            <div class="actions">
                <button type="submit" name="submit">Tambah peminjaman</button>
                <button type="reset" class="clear-btn">Reset</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>