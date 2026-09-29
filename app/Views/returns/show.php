<?php
/**
 * @var array<string, mixed> $return
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="/returns" class="btn btn-sm btn-outline-secondary">&larr; Kembali ke Riwayat</a>
</div>

<div class="card shadow-sm border-0 col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Detail Laporan Retur Pakaian</h6>
    </div>
    <div class="card-body">
        <table class="table table-borderless align-middle mb-0">
            <tr>
                <td class="text-secondary" style="width: 220px;">Nomor Retur</td>
                <td class="fw-bold">: <?= esc($return['return_code']) ?></td>
            </tr>
            <tr>
                <td class="text-secondary">Tanggal Masuk Retur</td>
                <td>: <?= date('d F Y', strtotime($return['return_date'])) ?></td>
            </tr>
            <tr>
                <td class="text-secondary">Petugas Penerima</td>
                <td>: <?= esc($return['admin_name']) ?></td>
            </tr>
            <tr>
                <td class="text-secondary">Sales Channel</td>
                <td>: <span class="badge bg-secondary"><?= esc($return['channel']) ?></span></td>
            </tr>
            <tr>
                <td class="text-secondary">Model Pakaian</td>
                <td class="fw-semibold">: <?= esc($return['product_name']) ?></td>
            </tr>
            <tr>
                <td class="text-secondary">Ukuran & SKU</td>
                <td>: <span class="badge bg-light text-dark border"><?= esc($return['size']) ?></span> (<?= esc($return['sku']) ?>)</td>
            </tr>
            <tr>
                <td class="text-secondary">Jumlah Diretur</td>
                <td class="fw-bold text-danger">: <?= esc((string)$return['quantity']) ?> Pcs</td>
            </tr>
            <tr>
                <td class="text-secondary">Alasan Kerusakan/Retur</td>
                <td>: 
                    <?php 
                    $reasonLabel = match($return['reason']) {
                        'cacat_jahitan' => 'Cacat Jahitan (Lepas / Miring)',
                        'salah_ukuran'  => 'Salah Ukuran / Tukar Size',
                        'rusak_kain'    => 'Rusak Kain / Cacat Bahan',
                        default         => $return['reason']
                    };
                    echo esc($reasonLabel);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="text-secondary">Keputusan Tindakan</td>
                <td>: 
                    <?php if ($return['action_taken'] === 'masuk_kembali_stok'): ?>
                        <span class="badge bg-success">Masuk Kembali ke Stok Gudang</span>
                    <?php elseif ($return['action_taken'] === 'kembali_ke_penjahit'): ?>
                        <span class="badge bg-warning text-dark">Dikembalikan ke Penjahit</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Afkir / Dibuang</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="text-secondary">Catatan Khusus</td>
                <td>: <?= esc($return['notes'] ?? '-') ?></td>
            </tr>
        </table>
    </div>
</div>
<?= $this->endSection() ?>