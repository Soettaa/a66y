<?php 
session_start();
include 'koneksi.php';

$message = '';
if(isset($_POST['login'])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM anggota WHERE nama_anggota = '$username' OR id_anggota = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password === $row["password"]) {
           if ($row["level"] == "admin") {
                $_SESSION["login"] = true;
                $_SESSION["level"] = "admin";
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $_SESSION["login"] = true;
                $_SESSION["level"] = "anggota";
                $_SESSION["username"] = $row["nama_anggota"];
                $_SESSION["ida"] = $row["id_anggota"];
                header("Location: index.php");
                exit;
            }
        } else {
            $message = "Password salah!";
        }
    } else {
        $message = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PERPUSPUSTAKAAN</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #1e293b;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            border: 1px solid #334155;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.75rem;
        }
        .logo-text { font-size: 20px; font-weight: 800; color: #60a5fa; }
        .logo-sub { font-size: 13px; color: #94a3b8; }
        h1 { font-size: 22px; font-weight: 700; color: #e2e8f0; margin-bottom: 0.25rem; }
        p.sub { font-size: 14px; color: #94a3b8; margin-bottom: 1.5rem; }
        label { display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px; }
        .field { margin-bottom: 1rem; }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-size: 14px;
            color: #e2e8f0;
            background: #0f172a;
            transition: border-color 0.2s;
            outline: none;
        }
        input[type=text]:focus, input[type=password]:focus { border-color: #60a5fa; }
        button[type=submit] {
            width: 100%;
            padding: 11px;
            background: #1e40af;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }
        button[type=submit]:hover { background: #1e3a8a; }
        .error {
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid #7f1d1d;
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div>
                <div class="logo-text">PERPUSPUSTAKAAN</div>
                <div class="logo-sub">Sistem Perpustakaan</div>
            </div>
        </div>
        <h1>Selamat datang</h1>
        <p class="sub">Masuk</p>
        <form action="" method="post">
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password">
            </div>
            <button type="submit" name="login">Masuk</button>
        </form>
        <?php if($message): ?>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>