<?php
/**
 * @var array<string, mixed> $product
 * @var array<string, mixed> $category
 * @var array<int, array<string, mixed>> $variants
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="/products" class="btn btn-sm btn-outline-secondary">&larr; Kembali ke Katalog</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <span class="badge bg-primary mb-2"><?= esc($category['category_name']) ?></span>
                <h4 class="fw-bold mb-1"><?= esc($product['product_name']) ?></h4>
                <p class="text-secondary small mb-2">Kode Model: <strong><?= esc($product['product_code']) ?></strong></p>
                <p class="text-muted mb-0"><?= esc($product['description'] ?? 'Tidak ada catatan deskripsi.') ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Daftar Saldo Stok per Variasi Ukuran</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">SKU</th>
                        <th>Ukuran</th>
                        <th>Stok Fisik Saat Ini</th>
                        <th>Batas Safety Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($variants as $v): ?>
                        <tr>
                            <td class="ps-3 fw-semibold"><?= esc($v['sku']) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($v['size']) ?></span></td>
                            <td class="fw-bold <?= $v['stock'] <= $v['safety_stock'] ? 'text-danger' : 'text-success' ?>">
                                <?= esc((string)$v['stock']) ?> Pcs
                            </td>
                            <td><?= esc((string)$v['safety_stock']) ?> Pcs</td>
                            <td>
                                <?php if ($v['stock'] <= 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif ($v['stock'] <= $v['safety_stock']): ?>
                                    <span class="badge bg-warning text-dark">Kritis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>