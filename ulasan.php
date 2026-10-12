<h1 class="h3 mb-2 text-gray-800">Ulasan</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="?page=ulasan_tambah" class="btn btn-primary">Tambah Ulasan</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Ulasan Buku</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Buku</th>
                        <th>Ulasan</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = $koneksi->query(
                        'SELECT ul.*, u.nama, b.judul
                         FROM ulasan ul
                         JOIN user u ON u.id_user = ul.id_user
                         JOIN buku b ON b.id_buku = ul.id_buku
                         ORDER BY ul.id_ulasan DESC'
                    );
                    foreach ($query as $data) {
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($data['judul']); ?></td>
                        <td><?= htmlspecialchars($data['ulasan']); ?></td>
                        <td><?= (int) $data['rating']; ?>/5</td>
                        <td>
                            <a onclick="return confirm('Hapus ulasan ini?')"
                               href="?page=ulasan_hapus&id=<?= (int) $data['id_ulasan']; ?>"
                               class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
