<h1 class="h3 mb-2 text-gray-800">Kategori</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="?page=kategori_tambah" class="btn btn-primary">Tambah Kategori</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Kategori</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = $koneksi->query('SELECT * FROM kategori ORDER BY id_kategori ASC');
                    foreach ($query as $data) {
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($data['kategori']); ?></td>
                        <td>
                            <a href="?page=kategori_edit&id=<?= (int) $data['id_kategori']; ?>" class="btn btn-info btn-sm">Ubah</a>
                            <a onclick="return confirm('Apakah anda yakin menghapus data ini?')"
                               href="?page=kategori_hapus&id=<?= (int) $data['id_kategori']; ?>"
                               class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
