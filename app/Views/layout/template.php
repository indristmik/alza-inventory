<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Inventaris') ?> - ALZA Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8fafc; }
        .sidebar { width: 250px; min-height: 100vh; background-color: #1e293b; color: #fff; }
        .sidebar .nav-link { color: #94a3b8; font-size: 14px; padding: 10px 18px; border-radius: 6px; margin: 2px 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #334155; }
        .sidebar-heading { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; padding: 14px 18px 6px; font-weight: bold; }
        .content-area { flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3">
        <div class="d-flex align-items-center mb-4 px-2">
            <h5 class="mb-0 text-white fw-bold">ALZA INVENTORY</h5>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link">Dashboard</a>
            </li>

            <!-- MENU OPERASIONAL (ADMIN) -->
            <?php if (session()->get('role') === 'admin'): ?>
                <div class="sidebar-heading">Master Data</div>
                <li class="nav-item"><a href="/categories" class="nav-link">Kategori Pakaian</a></li>
                <li class="nav-item"><a href="/tailors" class="nav-link">Mitra Penjahit</a></li>
                <li class="nav-item"><a href="/products" class="nav-link">Katalog Produk & Varian</a></li>

                <div class="sidebar-heading">Transaksi Mutasi</div>
                <li class="nav-item"><a href="/stock-in" class="nav-link">Barang Masuk (Setoran)</a></li>
                <li class="nav-item"><a href="/stock-out" class="nav-link">Barang Keluar (Penjualan)</a></li>
                <li class="nav-item"><a href="/returns" class="nav-link">Retur Pakaian</a></li>
            <?php endif; ?>

            <!-- MENU LAPORAN BERSAMA (Admin & Owner) -->
            <div class="sidebar-heading">Laporan & Rekap</div>
            <li class="nav-item"><a href="/reports/sewing-cost" class="nav-link">Rekap Ongkos Jahit</a></li>

            <!-- MENU MANAJERIAL (KHUSUS OWNER) -->
            <?php if (session()->get('role') === 'owner'): ?>
                <li class="nav-item"><a href="/inventory" class="nav-link">Monitoring Stok Fisik</a></li>
                <li class="nav-item"><a href="/reports/sales" class="nav-link">Laporan Penjualan Channel</a></li>
                <li class="nav-item"><a href="/reports/returns" class="nav-link">Analisis Retur Cacat</a></li>
                <li class="nav-item"><a href="/users" class="nav-link">Kelola Akun Pengguna</a></li>
            <?php endif; ?>
        </ul>

        <hr class="border-secondary my-3">
        <div class="px-2">
            <div class="small text-white-50">Masuk sebagai:</div>
            <div class="fw-semibold text-white mb-2"><?= esc(session()->get('name')) ?> (<?= esc(strtoupper(session()->get('role'))) ?>)</div>
            <a href="/logout" class="btn btn-sm btn-outline-danger w-100">Keluar</a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="content-area">
        <header class="bg-white border-bottom py-3 px-4 shadow-sm d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-secondary"><?= esc($title ?? 'Dashboard') ?></h6>
            <span class="badge bg-secondary"><?= date('d F Y') ?></span>
        </header>

        <main class="p-4 flex-grow-1">
            <!-- Flash Notification -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Render child view -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>