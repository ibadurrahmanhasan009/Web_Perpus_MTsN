<?php
$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = $koneksi->prepare('DELETE FROM kategori WHERE id_kategori = :id');
    $stmt->execute(['id' => $id]);
    echo '<script>alert("Hapus data berhasil."); location.href="?page=kategori";</script>';
} catch (PDOException $e) {
    echo '<script>alert("Gagal hapus. Kategori masih dipakai oleh buku."); location.href="?page=kategori";</script>';
}
?>
