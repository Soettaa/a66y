<?php 
session_start();
include "koneksi.php";

if(isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $kategori = $_POST['kategori'];
    $qty = $_POST['qty'];

    $sql = "INSERT INTO `buku`(`id_buku`, `judul`, `pengarang`, `penerbit`, `kategori`, `qty`) 
    VALUES (null,'$judul','$pengarang','$penerbit','$kategori','$qty')";
    $result = $conn->query($sql);

    if($result){
        echo "<script>
            alert('Buku berhasil ditambahkan!');
            document.location.href = 'buku.php';
            </script>";
    } else {
        echo "<script>
            alert('Buku gagal ditambahkan!');
            document.location.href = 'buku.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSPUSTAKAAN — Buku</title>
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
        input[type=text], input[type=number] {
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
        input[type=text]:focus, input[type=number]:focus { border-color: #60a5fa; }
        
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
        <a href="buku.php">Kembali</a>
    </header>

    <div class="card">
        <h1>Form Buku</h1>
        <p class="sub">Tambahkan buku baru ke dalam sistem</p>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field">
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" placeholder="Judul buku">
                </div>
                <div class="field">
                    <label for="pengarang">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" placeholder="Nama pengarang">
                </div>
                <div class="field">
                    <label for="penerbit">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" placeholder="Nama penerbit">
                </div>
                <div class="field">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori">
                        <option value="">Pilih kategori</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Sains">Sains</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Sejarah">Sejarah</option>
                        <option value="Biografi">Biografi</option>
                    </select>
                </div>
                 <div class="field">
                    <label for="qty">Jumlah</label>
                    <input type="number" id="qty" name="qty" min="1" placeholder="Jumlah buku">
                </div>
            </div>
            <div class="divider"></div>
            <div class="actions">
                <button type="submit" name="submit">Tambah Buku</button>
                <button type="reset" class="clear-btn">Reset</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>