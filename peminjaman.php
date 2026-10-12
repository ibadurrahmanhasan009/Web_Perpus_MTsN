<h1 class="h3 mb-2 text-gray-800">Peminjaman</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="?page=peminjaman_tambah" class="btn btn-primary">Tambah Peminjaman</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Peminjaman</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $level = $_SESSION['level'] ?? '';
                    $userId = (int) ($_SESSION['id_user'] ?? 0);

                    if ($level === 'peminjam') {
                        $stmt = $koneksi->prepare(
                            "SELECT p.*, u.nama, b.judul
                             FROM peminjaman p
                             JOIN user u ON u.id_user = p.id_user
                             JOIN buku b ON b.id_buku = p.id_buku
                             WHERE p.id_user = :id_user
                             ORDER BY p.id_peminjaman DESC"
                        );
                        $stmt->execute(['id_user' => $userId]);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $rows = $koneksi->query(
                            "SELECT p.*, u.nama, b.judul
                             FROM peminjaman p
                             JOIN user u ON u.id_user = p.id_user
                             JOIN buku b ON b.id_buku = p.id_buku
                             ORDER BY p.id_peminjaman DESC"
                        )->fetchAll(PDO::FETCH_ASSOC);
                    }

                    foreach ($rows as $data) {
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($data['judul']); ?></td>
                        <td><?= htmlspecialchars($data['tanggal_peminjaman']); ?></td>
                        <td><?= htmlspecialchars($data['tanggal_pengembalian'] ?? '-'); ?></td>
                        <td>
                            <?php if ($data['status'] === 'dipinjam'): ?>
                                <span class="badge badge-warning">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge badge-success">Dikembalikan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($data['status'] === 'dipinjam' && $level !== 'peminjam'): ?>
                                <a href="?page=peminjaman_kembali&id=<?= (int) $data['id_peminjaman']; ?>"
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Konfirmasi pengembalian buku?')">Kembalikan</a>
                            <?php endif; ?>
                            <?php if ($level !== 'peminjam'): ?>
                                <a onclick="return confirm('Hapus data peminjaman ini?')"
                                   href="?page=peminjaman_hapus&id=<?= (int) $data['id_peminjaman']; ?>"
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
