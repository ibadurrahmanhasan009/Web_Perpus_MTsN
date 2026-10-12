<?php
$level = $_SESSION['level'] ?? '';
if (!in_array($level, ['admin', 'petugas'], true)) {
    echo '<div class="alert alert-danger">Anda tidak memiliki akses ke halaman ini.</div>';
    return;
}

$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM user WHERE id_user = :id');
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<div class="alert alert-danger">Data anggota tidak ditemukan.</div>';
    return;
}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $userLevel = $_POST['level'] ?? $data['level'];

    if (!in_array($userLevel, ['admin', 'petugas', 'peminjam'], true)) {
        $userLevel = $data['level'];
    }
    if ($level === 'petugas') {
        $userLevel = $data['level'];
    }

    if ($username === '' || $nama === '') {
        echo '<script>alert("Username dan nama wajib diisi.");</script>';
    } else {
        $cek = $koneksi->prepare('SELECT COUNT(*) FROM user WHERE username = :username AND id_user != :id');
        $cek->execute(['username' => $username, 'id' => $id]);

        if ((int) $cek->fetchColumn() > 0) {
            echo '<script>alert("Username sudah dipakai.");</script>';
        } else {
            if ($password !== '') {
                $update = $koneksi->prepare(
                    'UPDATE user SET username = :username, password = :password, nama = :nama,
                     email = :email, alamat = :alamat, level = :level WHERE id_user = :id'
                );
                $ok = $update->execute([
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'nama' => $nama,
                    'email' => $email,
                    'alamat' => $alamat,
                    'level' => $userLevel,
                    'id' => $id,
                ]);
            } else {
                $update = $koneksi->prepare(
                    'UPDATE user SET username = :username, nama = :nama,
                     email = :email, alamat = :alamat, level = :level WHERE id_user = :id'
                );
                $ok = $update->execute([
                    'username' => $username,
                    'nama' => $nama,
                    'email' => $email,
                    'alamat' => $alamat,
                    'level' => $userLevel,
                    'id' => $id,
                ]);
            }

            if ($ok) {
                echo '<script>alert("Data anggota diubah."); location.href="?page=anggota";</script>';
            } else {
                echo '<script>alert("Gagal mengubah data.");</script>';
            }
        }
    }

    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h1 class="h3 mb-2 text-gray-800">Anggota</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Ubah Anggota</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" class="form-control"
                           value="<?= htmlspecialchars($data['username']); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password Baru</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control"
                           placeholder="Kosongkan jika tidak diganti">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($data['nama']); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($data['email'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                </div>
            </div>
            <?php if ($level === 'admin'): ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Level</label>
                <div class="col-sm-10">
                    <select name="level" class="form-control">
                        <option value="peminjam" <?= $data['level'] === 'peminjam' ? 'selected' : ''; ?>>Peminjam</option>
                        <option value="petugas" <?= $data['level'] === 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                        <option value="admin" <?= $data['level'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
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
