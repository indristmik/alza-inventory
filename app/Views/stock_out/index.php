<?php
/**
 * @var array<int, array<string, mixed>> $transactions
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0 text-dark">Riwayat Barang Keluar (Penjualan)</h5>
    <a href="/stock-out/new" class="btn btn-primary">+ Catat Pengeluaran Baru</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode Transaksi / No. Resi</th>
                        <th>Tanggal</th>
                        <th>Sales Channel</th>
                        <th>Petugas Input</th>
                        <th>Catatan</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada catatan barang keluar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-primary"><?= esc($t['transaction_code']) ?></td>
                                <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                                <td>
                                    <?php 
                                    $badgeClass = match($t['channel']) {
                                        'TikTok'  => 'bg-dark',
                                        'Shopee'  => 'bg-warning text-dark',
                                        'Lazada'  => 'bg-primary',
                                        'Offline' => 'bg-secondary',
                                        default   => 'bg-light text-dark'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($t['channel']) ?></span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($t['admin_name']) ?></span></td>
                                <td class="text-secondary small"><?= esc($t['notes'] ?? '-') ?></td>
                                <td class="text-center">
                                    <a href="/stock-out/show/<?= $t['id'] ?>" class="btn btn-sm btn-outline-info">Detail Nota</a>
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