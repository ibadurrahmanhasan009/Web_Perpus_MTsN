<?php
$userId = (int) ($_SESSION['id_user'] ?? 0);

if (isset($_POST['submit'])) {
    $id_buku = (int) ($_POST['id_buku'] ?? 0);
    $ulasan = trim($_POST['ulasan'] ?? '');
    $rating = (int) ($_POST['rating'] ?? 5);

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    if ($id_buku <= 0 || $ulasan === '') {
        echo '<script>alert("Buku dan ulasan wajib diisi.");</script>';
    } else {
        $stmt = $koneksi->prepare(
            'INSERT INTO ulasan (id_user, id_buku, ulasan, rating)
             VALUES (:id_user, :id_buku, :ulasan, :rating)'
        );
        $ok = $stmt->execute([
            'id_user' => $userId,
            'id_buku' => $id_buku,
            'ulasan' => $ulasan,
            'rating' => $rating,
        ]);

        if ($ok) {
            echo '<script>alert("Ulasan berhasil ditambahkan."); location.href="?page=ulasan";</script>';
        } else {
            echo '<script>alert("Gagal menambah ulasan.");</script>';
        }
    }
}

$bukuList = $koneksi->query('SELECT id_buku, judul FROM buku ORDER BY judul ASC');
?>

<h1 class="h3 mb-2 text-gray-800">Ulasan</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Ulasan</h6>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Buku</label>
                <div class="col-sm-10">
                    <select name="id_buku" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php foreach ($bukuList as $b): ?>
                            <option value="<?= (int) $b['id_buku']; ?>"><?= htmlspecialchars($b['judul']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Rating</label>
                <div class="col-sm-10">
                    <select name="rating" class="form-control" required>
                        <?php for ($r = 5; $r >= 1; $r--): ?>
                            <option value="<?= $r; ?>"><?= $r; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ulasan</label>
                <div class="col-sm-10">
                    <textarea name="ulasan" class="form-control" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=ulasan" class="btn btn-danger">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
