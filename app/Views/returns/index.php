<?php
/**
 * @var array<int, array<string, mixed>> $returns
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0 text-dark">Riwayat Retur Pakaian Masuk</h5>
    <a href="/returns/new" class="btn btn-primary">+ Catat Retur Baru</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Retur</th>
                        <th>Tanggal</th>
                        <th>Model & SKU</th>
                        <th>Channel</th>
                        <th class="text-center">Jumlah</th>
                        <th>Alasan Retur</th>
                        <th>Tindakan Lanjut</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($returns)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat pengembalian barang (retur).</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($returns as $r): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-primary"><?= esc($r['return_code']) ?></td>
                                <td><?= date('d/m/Y', strtotime($r['return_date'])) ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($r['product_name']) ?></div>
                                    <small class="text-secondary">SKU: <?= esc($r['sku']) ?> (<?= esc($r['size']) ?>)</small>
                                </td>
                                <td><span class="badge bg-secondary"><?= esc($r['channel']) ?></span></td>
                                <td class="text-center fw-bold text-danger"><?= esc((string)$r['quantity']) ?> pcs</td>
                                <td>
                                    <?php 
                                    $reasonLabel = match($r['reason']) {
                                        'cacat_jahitan' => 'Cacat Jahitan',
                                        'salah_ukuran'  => 'Salah Ukuran',
                                        'rusak_kain'    => 'Rusak Bahan',
                                        default         => $r['reason']
                                    };
                                    ?>
                                    <span class="badge bg-light text-dark border"><?= $reasonLabel ?></span>
                                </td>
                                <td>
                                    <?php if ($r['action_taken'] === 'masuk_kembali_stok'): ?>
                                        <span class="badge bg-success">Masuk Kembali ke Stok</span>
                                    <?php elseif ($r['action_taken'] === 'kembali_ke_penjahit'): ?>
                                        <span class="badge bg-warning text-dark">Retur ke Penjahit</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Afkir / Dibuang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="/returns/show/<?= $r['id'] ?>" class="btn btn-sm btn-outline-info">Detail</a>
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