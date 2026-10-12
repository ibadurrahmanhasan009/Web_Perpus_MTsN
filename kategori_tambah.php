<?php
if (isset($_POST['submit'])) {
    $kategori = trim($_POST['kategori'] ?? '');

    if ($kategori === '') {
        echo '<script>alert("Nama kategori wajib diisi.");</script>';
    } else {
        $cek = $koneksi->prepare('SELECT COUNT(*) FROM kategori WHERE LOWER(kategori) = LOWER(:kategori)');
        $cek->execute(['kategori' => $kategori]);

        if ((int) $cek->fetchColumn() > 0) {
            echo '<script>alert("Kategori sudah ada. Masukkan data baru.");</script>';
        } else {
            $stmt = $koneksi->prepare('INSERT INTO kategori (kategori) VALUES (:kategori)');
            if ($stmt->execute(['kategori' => $kategori])) {
                echo '<script>alert("Tambah data berhasil."); location.href="?page=kategori";</script>';
            } else {
                echo '<script>alert("Tambah data gagal.");</script>';
            }
        }
    }
}
?>

<h1 class="h3 mb-2 text-gray-800">Kategori</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Data Kategori</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row mb-3">
                <div class="col-md-2">Nama Kategori</div>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="kategori" placeholder="Masukkan nama kategori..." required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <button type="submit" name="submit" class="btn btn-primary" value="submit">Simpan</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <a href="?page=kategori" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
