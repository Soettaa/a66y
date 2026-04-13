<?php 
session_start();
include "koneksi.php";

if(isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];
    $nohp = $_POST['nohp'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $lokasiFile = $_FILES['foto']['tmp_name'];
    $error = $_FILES['foto']['error'];

    if($error===4){
        echo "<script>
            alert('Pilih gambar dahulu!');
            </script>";
        return false;
    }

    $extensiGambarValid=['jpg','png','webp','jpeg'];
    $extensiGambar=explode('.',$namaFile);
    $extensiGambar=strtolower(end($extensiGambar));
    if(!in_array($extensiGambar,$extensiGambarValid)){
        echo "<script>
            alert('File bukan gambar!');
            </script>";
        return false;
    }

    if($ukuranFile>7000000){
        echo "<script>
            alert('Size terlalu besar!');
            </script>";
        return false;
    }

    move_uploaded_file($lokasiFile,'img/'.$namaFile);
    $foto = 'img/'.$namaFile;


    $sql = "INSERT INTO `anggota`(`id_anggota`, `nama_anggota`, `gender`, `alamat`, `nohp`, `foto`, `tgl_daftar`, `password`, `level`) 
    VALUES (null,'$nama','$gender','$alamat','$nohp','$foto',NOW(),'$password','$level')";
    $result = $conn->query($sql);

    if($result){
        echo "<script>
            alert('Anggota berhasil ditambahkan!');
            document.location.href = 'anggota.php';
            </script>";
    } else {
        echo "<script>
            alert('Anggota gagal ditambahkan!');
            document.location.href = 'anggota.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSPUSTAKAAN — Anggota</title>
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
        .field.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 600; color: #cbd5e1; }
        input[type=text], textarea {
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
        input[type=text]:focus, textarea:focus { border-color: #60a5fa; }
        textarea { resize: vertical; min-height: 80px; }
        
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
        .file-input {
            display: none;
        }
        .file-label {
            display: inline-block;
            padding: 10px 14px;
            background: rgba(96, 165, 250, 0.15);
            color: #60a5fa;
            border: 1.5px solid #1e40af;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .file-label:hover { background: rgba(96, 165, 250, 0.25); }
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
        <a href="anggota.php">Kembali</a>
    </header>

    <div class="card">
        <h1>Form Anggota</h1>
        <p class="sub">Tambahkan anggota baru ke dalam sistem</p>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field">
                    <label for="nama">Nama Anggota</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama lengkap">
                </div>
                <div class="field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="" disabled selected>Pilih gender</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="field">
                    <label for="nohp">No HP</label>
                    <input type="text" id="nohp" name="nohp" placeholder="Contoh: 081234567890">
                </div>
                <div class="field">
                    <label for="level">Level</label>
                    <select id="level" name="level">
                        <option value="" disabled selected>Pilih level</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                 <div class="field">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password">
                </div>
                <div class="field full">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" placeholder="Alamat lengkap"></textarea>
                </div>
                <div class="field full">
                    <label for="foto" class="file-label">Pilih Foto Profil</label>
                    <input type="file" id="foto" name="foto" class="file-input" accept="image/*" onchange="previewImage(event)">
                </div>
                <img id="preview" src="#" alt="Preview Foto" style="max-width: 200px; max-height: 200px; display: none;">
            </div>
            <div class="divider"></div>
            <div class="actions">
                <button type="submit" name="submit">Tambah Anggota</button>
                <button type="reset" class="clear-btn">Reset</button>
            </div>
        </form>
    </div>
</div>
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    }
    
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('preview').style.display = "none";
        document.getElementById('preview').src = "#";
    });
    </script>
</body>
</html>