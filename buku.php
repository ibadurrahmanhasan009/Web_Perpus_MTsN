<h1 class="h3 mb-2 text-gray-800">Buku</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="?page=buku_tambah" class="btn btn-primary">Tambah Buku</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Buku</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = $koneksi->query(
                        'SELECT b.*, k.kategori
                         FROM buku b
                         JOIN kategori k ON k.id_kategori = b.id_kategori
                         ORDER BY b.id_buku DESC'
                    );
                    foreach ($query as $data) {
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($data['judul']); ?></td>
                        <td><?= htmlspecialchars($data['kategori']); ?></td>
                        <td><?= htmlspecialchars($data['penulis']); ?></td>
                        <td><?= htmlspecialchars($data['penerbit'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($data['tahun_terbit'] ?? '-'); ?></td>
                        <td><?= (int) $data['stok']; ?></td>
                        <td>
                            <a href="?page=buku_edit&id=<?= (int) $data['id_buku']; ?>" class="btn btn-info btn-sm">Ubah</a>
                            <a onclick="return confirm('Apakah anda yakin menghapus data ini?')"
                               href="?page=buku_hapus&id=<?= (int) $data['id_buku']; ?>"
                               class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
