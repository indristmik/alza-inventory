<?php
/**
 * @var array<string, mixed> $transaction
 * @var array<int, array<string, mixed>> $details
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="/stock-in" class="btn btn-sm btn-outline-secondary">&larr; Kembali ke Riwayat</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Bukti Penerimaan Barang (Setoran Penjahit)</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-secondary" style="width: 150px;">No. Surat Jalan</td>
                        <td class="fw-bold">: <?= esc($transaction['invoice_number']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Mitra Penjahit</td>
                        <td class="fw-bold">: <?= esc($transaction['tailor_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Tanggal Masuk</td>
                        <td>: <?= date('d F Y', strtotime($transaction['transaction_date'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-secondary" style="width: 150px;">Penerima (Admin)</td>
                        <td>: <?= esc($transaction['admin_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Catatan</td>
                        <td>: <?= esc($transaction['notes'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Rincian Item Pakaian</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">SKU</th>
                        <th>Model Pakaian</th>
                        <th>Ukuran</th>
                        <th class="text-end">Jumlah Diterima</th>
                        <th class="text-end">Ongkos Jahit/Pcs</th>
                        <th class="text-end pe-3">Subtotal Ongkos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalQty = 0; 
                    $totalCost = 0; 
                    foreach ($details as $d): 
                        $subtotal = $d['quantity'] * $d['sewing_cost_per_pcs'];
                        $totalQty += $d['quantity'];
                        $totalCost += $subtotal;
                    ?>
                        <tr>
                            <td class="ps-3 fw-semibold"><?= esc($d['sku']) ?></td>
                            <td><?= esc($d['product_name']) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($d['size']) ?></span></td>
                            <td class="text-end fw-bold text-success">+<?= esc((string)$d['quantity']) ?> Pcs</td>
                            <td class="text-end">Rp <?= number_format($d['sewing_cost_per_pcs'], 0, ',', '.') ?></td>
                            <td class="text-end pe-3 fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                        <td class="text-end text-success"><?= $totalQty ?> Pcs</td>
                        <td></td>
                        <td class="text-end pe-3 text-primary">Rp <?= number_format($totalCost, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>