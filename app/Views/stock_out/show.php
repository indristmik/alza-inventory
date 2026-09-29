<?php
/**
 * @var array<string, mixed> $transaction
 * @var array<int, array<string, mixed>> $details
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="/stock-out" class="btn btn-sm btn-outline-secondary">&larr; Kembali ke Riwayat</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Bukti Pengeluaran Barang (Surat Jalan / Invoice)</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-secondary" style="width: 160px;">No. Pesanan / Resi</td>
                        <td class="fw-bold">: <?= esc($transaction['transaction_code']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Sales Channel</td>
                        <td>: <span class="badge bg-secondary"><?= esc($transaction['channel']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Tanggal Pengeluaran</td>
                        <td>: <?= date('d F Y', strtotime($transaction['transaction_date'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-secondary" style="width: 150px;">Petugas Gudang</td>
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
        <h6 class="card-title mb-0 fw-bold">Daftar Pakaian yang Dikeluarkan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">SKU</th>
                        <th>Model Pakaian</th>
                        <th>Ukuran</th>
                        <th class="text-end pe-4">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalQty = 0; 
                    foreach ($details as $d): 
                        $totalQty += $d['quantity'];
                    ?>
                        <tr>
                            <td class="ps-3 fw-semibold"><?= esc($d['sku']) ?></td>
                            <td><?= esc($d['product_name']) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($d['size']) ?></span></td>
                            <td class="text-end pe-4 fw-bold text-danger">-<?= esc((string)$d['quantity']) ?> Pcs</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-center">TOTAL BARANG KELUAR</td>
                        <td class="text-end pe-4 text-danger"><?= $totalQty ?> Pcs</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>