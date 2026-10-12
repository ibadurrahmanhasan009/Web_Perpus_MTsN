<?php
$id = (int) ($_GET['id'] ?? 0);

$stmt = $koneksi->prepare('SELECT * FROM peminjaman WHERE id_peminjaman = :id');
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<script>alert("Data tidak ditemukan."); location.href="?page=peminjaman";</script>';
    return;
}

try {
    $koneksi->beginTransaction();

    if ($data['status'] === 'dipinjam') {
        $stok = $koneksi->prepare('UPDATE buku SET stok = stok + 1 WHERE id_buku = :id');
        $stok->execute(['id' => $data['id_buku']]);
    }

    $hapus = $koneksi->prepare('DELETE FROM peminjaman WHERE id_peminjaman = :id');
    $hapus->execute(['id' => $id]);

    $koneksi->commit();
    echo '<script>alert("Data peminjaman dihapus."); location.href="?page=peminjaman";</script>';
} catch (PDOException $e) {
    $koneksi->rollBack();
    echo '<script>alert("Gagal menghapus data."); location.href="?page=peminjaman";</script>';
}
?>
