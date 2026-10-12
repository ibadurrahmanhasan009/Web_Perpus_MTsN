<?php
$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('DELETE FROM ulasan WHERE id_ulasan = :id');
$stmt->execute(['id' => $id]);
echo '<script>alert("Ulasan dihapus."); location.href="?page=ulasan";</script>';
?>
