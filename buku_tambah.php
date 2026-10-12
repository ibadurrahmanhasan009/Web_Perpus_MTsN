<?php
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
        $stmt = $koneksi->prepare(
            'INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun_terbit, stok, deskripsi)
             VALUES (:id_kategori, :judul, :penulis, :penerbit, :tahun_terbit, :stok, :deskripsi)'
        );
        $ok = $stmt->execute([
            'id_kategori' => $id_kategori,
            'judul' => $judul,
            'penulis' => $penulis,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun_terbit,
            'stok' => $stok,
            'deskripsi' => $deskripsi,
        ]);

        if ($ok) {
            echo '<script>alert("Tambah buku berhasil."); location.href="?page=buku";</script>';
        } else {
            echo '<script>alert("Tambah buku gagal.");</script>';
        }
    }
}

$kategoriList = $koneksi->query('SELECT * FROM kategori ORDER BY kategori ASC');
?>

<h1 class="h3 mb-2 text-gray-800">Buku</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Data Buku</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= (int) $k['id_kategori']; ?>"><?= htmlspecialchars($k['kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Judul</label>
                <div class="col-sm-10">
                    <input type="text" name="judul" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penulis</label>
                <div class="col-sm-10">
                    <input type="text" name="penulis" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penerbit</label>
                <div class="col-sm-10">
                    <input type="text" name="penerbit" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tahun Terbit</label>
                <div class="col-sm-10">
                    <input type="number" name="tahun_terbit" class="form-control" min="1900" max="2100">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Stok</label>
                <div class="col-sm-10">
                    <input type="number" name="stok" class="form-control" min="0" value="1" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <a href="?page=buku" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
