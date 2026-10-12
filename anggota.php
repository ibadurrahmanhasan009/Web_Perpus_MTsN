<?php
$level = $_SESSION['level'] ?? '';
if (!in_array($level, ['admin', 'petugas'], true)) {
    echo '<div class="alert alert-danger">Anda tidak memiliki akses ke halaman ini.</div>';
    return;
}
?>

<h1 class="h3 mb-2 text-gray-800">Anggota</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="?page=anggota_tambah" class="btn btn-primary">Tambah Anggota</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Anggota / User</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = $koneksi->query('SELECT * FROM user ORDER BY id_user ASC');
                    foreach ($query as $data) {
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($data['username']); ?></td>
                        <td><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($data['email'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($data['level']); ?></td>
                        <td>
                            <a href="?page=anggota_edit&id=<?= (int) $data['id_user']; ?>" class="btn btn-info btn-sm">Ubah</a>
                            <?php if ((int) $data['id_user'] !== (int) ($_SESSION['id_user'] ?? 0)): ?>
                            <a onclick="return confirm('Hapus anggota ini?')"
                               href="?page=anggota_hapus&id=<?= (int) $data['id_user']; ?>"
                               class="btn btn-danger btn-sm">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
