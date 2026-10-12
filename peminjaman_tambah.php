<?php
$level = $_SESSION['level'] ?? '';
$userId = (int) ($_SESSION['id_user'] ?? 0);

if (isset($_POST['submit'])) {
    $id_user = ($level === 'peminjam') ? $userId : (int) ($_POST['id_user'] ?? 0);
    $id_buku = (int) ($_POST['id_buku'] ?? 0);
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'] ?? date('Y-m-d');

    if ($id_user <= 0 || $id_buku <= 0) {
        echo '<script>alert("Peminjam dan buku wajib dipilih.");</script>';
    } else {
        $cekStok = $koneksi->prepare('SELECT stok, judul FROM buku WHERE id_buku = :id');
        $cekStok->execute(['id' => $id_buku]);
        $buku = $cekStok->fetch(PDO::FETCH_ASSOC);

        if (!$buku) {
            echo '<script>alert("Buku tidak ditemukan.");</script>';
        } elseif ((int) $buku['stok'] <= 0) {
            echo '<script>alert("Stok buku habis.");</script>';
        } else {
            try {
                $koneksi->beginTransaction();

                $stmt = $koneksi->prepare(
                    'INSERT INTO peminjaman (id_user, id_buku, tanggal_peminjaman, status)
                     VALUES (:id_user, :id_buku, :tanggal_peminjaman, :status)'
                );
                $stmt->execute([
                    'id_user' => $id_user,
                    'id_buku' => $id_buku,
                    'tanggal_peminjaman' => $tanggal_peminjaman,
                    'status' => 'dipinjam',
                ]);

                $updateStok = $koneksi->prepare('UPDATE buku SET stok = stok - 1 WHERE id_buku = :id AND stok > 0');
                $updateStok->execute(['id' => $id_buku]);

                $koneksi->commit();
                echo '<script>alert("Peminjaman berhasil."); location.href="?page=peminjaman";</script>';
            } catch (PDOException $e) {
                $koneksi->rollBack();
                echo '<script>alert("Peminjaman gagal.");</script>';
            }
        }
    }
}

$anggota = $koneksi->query("SELECT id_user, nama, username FROM user WHERE level = 'peminjam' ORDER BY nama ASC");
$bukuList = $koneksi->query('SELECT id_buku, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC');
?>

<h1 class="h3 mb-2 text-gray-800">Peminjaman</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Peminjaman</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <?php if ($level !== 'peminjam'): ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Peminjam</label>
                <div class="col-sm-10">
                    <select name="id_user" class="form-control" required>
                        <option value="">-- Pilih Peminjam --</option>
                        <?php foreach ($anggota as $a): ?>
                            <option value="<?= (int) $a['id_user']; ?>">
                                <?= htmlspecialchars($a['nama'] . ' (' . $a['username'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Buku</label>
                <div class="col-sm-10">
                    <select name="id_buku" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php foreach ($bukuList as $b): ?>
                            <option value="<?= (int) $b['id_buku']; ?>">
                                <?= htmlspecialchars($b['judul'] . ' (stok: ' . $b['stok'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal Pinjam</label>
                <div class="col-sm-10">
                    <input type="date" name="tanggal_peminjaman" class="form-control"
                           value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=peminjaman" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
