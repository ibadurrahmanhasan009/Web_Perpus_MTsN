<?php
$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM kategori WHERE id_kategori = :id');
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<div class="alert alert-danger">Data kategori tidak ditemukan.</div>';
    return;
}

if (isset($_POST['submit'])) {
    $kategori = trim($_POST['kategori'] ?? '');

    if ($kategori === '') {
        echo '<script>alert("Nama kategori wajib diisi.");</script>';
    } else {
        $cek = $koneksi->prepare(
            'SELECT COUNT(*) FROM kategori WHERE LOWER(kategori) = LOWER(:kategori) AND id_kategori != :id'
        );
        $cek->execute(['kategori' => $kategori, 'id' => $id]);

        if ((int) $cek->fetchColumn() > 0) {
            echo '<script>alert("Kategori sudah ada.");</script>';
        } else {
            $update = $koneksi->prepare('UPDATE kategori SET kategori = :kategori WHERE id_kategori = :id');
            if ($update->execute(['kategori' => $kategori, 'id' => $id])) {
                echo '<script>alert("Ubah data berhasil."); location.href="?page=kategori";</script>';
            } else {
                echo '<script>alert("Ubah data gagal.");</script>';
            }
        }
    }
}
?>

<h1 class="h3 mb-2 text-gray-800">Kategori</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Ubah Data Kategori</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row mb-3">
                <div class="col-md-2">Nama Kategori</div>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="kategori"
                           value="<?= htmlspecialchars($data['kategori']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=kategori" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
