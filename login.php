<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status']) && $_SESSION['status'] === 'login') {
    header('Location: index.php');
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $koneksi->prepare('SELECT * FROM user WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $valid = false;
        if ($data) {
            $stored = (string) $data['password'];
            $info = password_get_info($stored);

            if (!empty($info['algo'])) {
                $valid = password_verify($password, $stored);
            } elseif (preg_match('/^[a-f0-9]{32}$/i', $stored)) {
                $valid = hash_equals(strtolower($stored), md5($password));
            } else {
                $valid = hash_equals($stored, $password);
            }
        }

        if ($valid) {
            $role = $data['level'] ?? ($data['role'] ?? '');

            $_SESSION['user_id'] = $data['id_user'];
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $role;
            $_SESSION['level'] = $role;
            $_SESSION['status'] = 'login';
            $_SESSION['user'] = [
                'id_user' => $data['id_user'],
                'username' => $data['username'],
                'nama' => $data['nama'] ?? '',
                'level' => $role,
            ];

            header('Location: index.php');
            exit;
        }

        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">

                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

                        <div class="col-lg-6">
                            <div class="p-5">

                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>

                                <?php if ($error !== ''): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <?php endif; ?>

                                <form method="POST" action="login.php" class="user">
                                    <div class="form-group">
                                        <input type="text" name="username"
                                               class="form-control form-control-user"
                                               placeholder="Username" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" name="password"
                                               class="form-control form-control-user"
                                               placeholder="Password" required>
                                    </div>

                                    <button type="submit" name="login"
                                            class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="register.php">Create an Account!</a>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">Demo: admin / admin123</small>
                                </div>

                            </div>
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
