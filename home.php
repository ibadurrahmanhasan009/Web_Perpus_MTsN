<?php
$total_peminjaman = (int) $koneksi->query('SELECT COUNT(*) FROM peminjaman')->fetchColumn();
$total_buku = (int) $koneksi->query('SELECT COUNT(*) FROM buku')->fetchColumn();
$total_petugas = (int) $koneksi->query("SELECT COUNT(*) FROM user WHERE level IN ('admin','petugas')")->fetchColumn();
$total_ulasan = (int) $koneksi->query('SELECT COUNT(*) FROM ulasan')->fetchColumn();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="?page=peminjaman" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> Lihat Peminjaman
    </a>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Peminjaman</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_peminjaman; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Buku</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_buku; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Petugas</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $total_petugas; ?></div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Ulasan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_ulasan; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Peminjaman Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $q = $koneksi->query(
                                "SELECT p.*, u.nama, b.judul
                                 FROM peminjaman p
                                 JOIN user u ON u.id_user = p.id_user
                                 JOIN buku b ON b.id_buku = p.id_buku
                                 ORDER BY p.id_peminjaman DESC
                                 LIMIT 5"
                            );
                            foreach ($q as $row) {
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['tanggal_peminjaman']); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'dipinjam'): ?>
                                        <span class="badge badge-warning">Dipinjam</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Dikembalikan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
