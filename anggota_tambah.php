<?php
$level = $_SESSION['level'] ?? '';
if (!in_array($level, ['admin', 'petugas'], true)) {
    echo '<div class="alert alert-danger">Anda tidak memiliki akses ke halaman ini.</div>';
    return;
}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $userLevel = $_POST['level'] ?? 'peminjam';

    if (!in_array($userLevel, ['admin', 'petugas', 'peminjam'], true)) {
        $userLevel = 'peminjam';
    }
    if ($level === 'petugas') {
        $userLevel = 'peminjam';
    }

    if ($username === '' || $password === '' || $nama === '') {
        echo '<script>alert("Username, password, dan nama wajib diisi.");</script>';
    } else {
        $cek = $koneksi->prepare('SELECT COUNT(*) FROM user WHERE username = :username');
        $cek->execute(['username' => $username]);

        if ((int) $cek->fetchColumn() > 0) {
            echo '<script>alert("Username sudah dipakai.");</script>';
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
                'level' => $userLevel,
            ]);

            if ($ok) {
                echo '<script>alert("Anggota berhasil ditambahkan."); location.href="?page=anggota";</script>';
            } else {
                echo '<script>alert("Gagal menambah anggota.");</script>';
            }
        }
    }
}
?>

<h1 class="h3 mb-2 text-gray-800">Anggota</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Anggota</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" name="nama" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <?php if ($level === 'admin'): ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Level</label>
                <div class="col-sm-10">
                    <select name="level" class="form-control">
                        <option value="peminjam">Peminjam</option>
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=anggota" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
