<?php
$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = $koneksi->prepare('DELETE FROM buku WHERE id_buku = :id');
    $stmt->execute(['id' => $id]);
    echo '<script>alert("Hapus buku berhasil."); location.href="?page=buku";</script>';
} catch (PDOException $e) {
    echo '<script>alert("Gagal hapus buku."); location.href="?page=buku";</script>';
}
?>
