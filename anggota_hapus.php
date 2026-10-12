<?php
$level = $_SESSION['level'] ?? '';
if (!in_array($level, ['admin', 'petugas'], true)) {
    echo '<script>alert("Akses ditolak."); location.href="index.php";</script>';
    return;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id === (int) ($_SESSION['id_user'] ?? 0)) {
    echo '<script>alert("Tidak bisa menghapus akun sendiri."); location.href="?page=anggota";</script>';
    return;
}

$stmt = $koneksi->prepare('DELETE FROM user WHERE id_user = :id');
$stmt->execute(['id' => $id]);
echo '<script>alert("Anggota dihapus."); location.href="?page=anggota";</script>';
?>
