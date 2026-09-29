<?php
/**
 * Anotasi agar VS Code Intelephense mengenali variabel dari Controller
 * @var int $totalProducts
 * @var int $totalVariants
 * @var array<int, array<string, mixed>> $stockAlerts
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="text-secondary small">Total Katalog Model</span>
                <h3 class="fw-bold mb-0 text-dark"><?= $totalProducts ?? 0 ?> Model</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="text-secondary small">Total Varian Ukuran Aktif</span>
                <h3 class="fw-bold mb-0 text-dark"><?= $totalVariants ?? 0 ?> SKU</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold text-danger">Peringatan Safety Stock (Perlu Restock)</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">SKU</th>
                        <th>Model Pakaian</th>
                        <th>Ukuran</th>
                        <th>Stok Fisik</th>
                        <th>Safety Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stockAlerts)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada stok di ambang kritis. Semua stok aman.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stockAlerts as $item): ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?= esc($item['sku']) ?></td>
                                <td><?= esc($item['product_name']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($item['size']) ?></span></td>
                                <td class="fw-bold <?= $item['stock'] <= 0 ? 'text-danger' : 'text-warning' ?>">
                                    <?= esc((string)$item['stock']) ?> Pcs
                                </td>
                                <td><?= esc((string)$item['safety_stock']) ?> Pcs</td>
                                <td>
                                    <?php if ($item['stock_status'] === 'Habis'): ?>
                                        <span class="badge bg-danger">Habis</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Kritis</span>
                                    <?php endif; ?>
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