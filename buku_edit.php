<?php
$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM buku WHERE id_buku = :id');
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<div class="alert alert-danger">Data buku tidak ditemukan.</div>';
    return;
}

if (isset($_POST['submit'])) {
    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $penerbit = trim($_POST['penerbit'] ?? '');
    $tahun_terbit = $_POST['tahun_terbit'] !== '' ? (int) $_POST['tahun_terbit'] : null;
    $stok = (int) ($_POST['stok'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($judul === '' || $penulis === '' || $id_kategori <= 0) {
        echo '<script>alert("Judul, penulis, dan kategori wajib diisi.");</script>';
    } else {
        $update = $koneksi->prepare(
            'UPDATE buku SET id_kategori = :id_kategori, judul = :judul, penulis = :penulis,
             penerbit = :penerbit, tahun_terbit = :tahun_terbit, stok = :stok, deskripsi = :deskripsi
             WHERE id_buku = :id'
        );
        $ok = $update->execute([
            'id_kategori' => $id_kategori,
            'judul' => $judul,
            'penulis' => $penulis,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun_terbit,
            'stok' => $stok,
            'deskripsi' => $deskripsi,
            'id' => $id,
        ]);

        if ($ok) {
            echo '<script>alert("Ubah buku berhasil."); location.href="?page=buku";</script>';
        } else {
            echo '<script>alert("Ubah buku gagal.");</script>';
        }
    }

    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$kategoriList = $koneksi->query('SELECT * FROM kategori ORDER BY kategori ASC');
?>

<h1 class="h3 mb-2 text-gray-800">Buku</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Ubah Data Buku</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select name="id_kategori" class="form-control" required>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= (int) $k['id_kategori']; ?>"
                                <?= ((int) $k['id_kategori'] === (int) $data['id_kategori']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($k['kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Judul</label>
                <div class="col-sm-10">
                    <input type="text" name="judul" class="form-control"
                           value="<?= htmlspecialchars($data['judul']); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penulis</label>
                <div class="col-sm-10">
                    <input type="text" name="penulis" class="form-control"
                           value="<?= htmlspecialchars($data['penulis']); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penerbit</label>
                <div class="col-sm-10">
                    <input type="text" name="penerbit" class="form-control"
                           value="<?= htmlspecialchars($data['penerbit'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tahun Terbit</label>
                <div class="col-sm-10">
                    <input type="number" name="tahun_terbit" class="form-control" min="1900" max="2100"
                           value="<?= htmlspecialchars($data['tahun_terbit'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Stok</label>
                <div class="col-sm-10">
                    <input type="number" name="stok" class="form-control" min="0"
                           value="<?= (int) $data['stok']; ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=buku" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
