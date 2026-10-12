<?php
$id = (int) ($_GET['id'] ?? 0);

$stmt = $koneksi->prepare('SELECT * FROM peminjaman WHERE id_peminjaman = :id AND status = :status');
$stmt->execute(['id' => $id, 'status' => 'dipinjam']);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<script>alert("Data peminjaman tidak ditemukan."); location.href="?page=peminjaman";</script>';
    return;
}

try {
    $koneksi->beginTransaction();

    $update = $koneksi->prepare(
        'UPDATE peminjaman SET status = :status, tanggal_pengembalian = :tgl WHERE id_peminjaman = :id'
    );
    $update->execute([
        'status' => 'dikembalikan',
        'tgl' => date('Y-m-d'),
        'id' => $id,
    ]);

    $stok = $koneksi->prepare('UPDATE buku SET stok = stok + 1 WHERE id_buku = :id');
    $stok->execute(['id' => $data['id_buku']]);

    $koneksi->commit();
    echo '<script>alert("Buku berhasil dikembalikan."); location.href="?page=peminjaman";</script>';
} catch (PDOException $e) {
    $koneksi->rollBack();
    echo '<script>alert("Pengembalian gagal."); location.href="?page=peminjaman";</script>';
}
?>
