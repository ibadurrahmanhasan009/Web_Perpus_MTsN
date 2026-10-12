<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status']) && $_SESSION['status'] === 'login') {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($username === '' || $password === '' || $nama === '') {
        $error = 'Username, password, dan nama wajib diisi.';
    } elseif ($password !== $password2) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $cek = $koneksi->prepare('SELECT COUNT(*) FROM user WHERE username = :username');
        $cek->execute(['username' => $username]);

        if ((int) $cek->fetchColumn() > 0) {
            $error = 'Username sudah dipakai.';
        } else {
            $stmt = $koneksi->prepare(
                'INSERT INTO user (username, password, nama, email, alamat, level)
                 VALUES (:username, :password, :nama, :email, :alamat, :level)'
            );
            $ok = $stmt->execute([
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'nama' => $nama,
                'email' => $email,
                'alamat' => $alamat,
                'level' => 'peminjam',
            ]);

            if ($ok) {
                $success = 'Registrasi berhasil. Silakan login.';
            } else {
                $error = 'Registrasi gagal.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register - Perpus MTsN Nagekeo</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Buat Akun Anggota</h1>
                        </div>

                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($success !== ''): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form class="user" method="post" action="register.php">
                            <div class="form-group">
                                <input type="text" name="nama" class="form-control form-control-user"
                                       placeholder="Nama Lengkap" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="username" class="form-control form-control-user"
                                       placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user"
                                       placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="text" name="alamat" class="form-control form-control-user"
                                       placeholder="Alamat">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user"
                                           placeholder="Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="password2" class="form-control form-control-user"
                                           placeholder="Ulangi Password" required>
                                </div>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="login.php">Sudah punya akun? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
