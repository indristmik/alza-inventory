<?php
/**
 * @var array<int, array<string, mixed>> $products
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0 text-dark">Katalog Pakaian</h5>
    <a href="/products/new" class="btn btn-primary">+ Tambah Model Pakaian</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode Produk</th>
                        <th>Model Pakaian</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada model pakaian yang didaftarkan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-primary"><?= esc($p['product_code']) ?></td>
                                <td class="fw-semibold"><?= esc($p['product_name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= esc($p['category_name']) ?></span></td>
                                <td class="text-secondary small"><?= esc($p['description'] ?? '-') ?></td>
                                <td class="text-center">
                                    <a href="/products/show/<?= $p['id'] ?>" class="btn btn-sm btn-outline-info">Detail & Stok</a>
                                    <a href="/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk beserta seluruh variannya?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>