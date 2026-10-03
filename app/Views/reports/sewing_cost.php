<?php
/**
 * @var array<int, array<string, mixed>> $tailors
 * @var array<int, array<string, mixed>> $records
 * @var string $startDate
 * @var string $endDate
 * @var string|null $selectedTailor
 * @var int $totalPcs
 * @var float $totalCost
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Rekap Tagihan Ongkos Jahit (Maklun)</h5>
        <small class="text-secondary">Pantau total produksi masuk dan estimasi kewajiban pembayaran jasa jahit.</small>
    </div>
    <button class="btn btn-outline-dark btn-sm" onclick="window.print()">
        Cetak Laporan
    </button>
</div>

<!-- Card Filter Rentang Tanggal & Penjahit -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/reports/sewing-cost" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= esc($startDate) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= esc($endDate) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Mitra Penjahit</label>
                <select name="tailor_id" class="form-select form-select-sm">
                    <option value="">-- Semua Penjahit --</option>
                    <?php foreach ($tailors as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($selectedTailor == $t['id']) ? 'selected' : '' ?>>
                            <?= esc($t['tailor_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter Data</button>
                <a href="/reports/sewing-cost" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Widget Ringkasan Angka -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body py-3">
                <div class="text-secondary small fw-bold">TOTAL PAKAIAN DISELESAIKAN</div>
                <div class="fs-4 fw-bold text-dark mt-1"><?= number_format($totalPcs, 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">Pcs</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body py-3">
                <div class="text-white-50 small fw-bold">TOTAL TAGIHAN JASA JAHIT</div>
                <div class="fs-4 fw-bold mt-1">Rp <?= number_format($totalCost, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>No. Surat Jalan</th>
                        <th>Mitra Penjahit</th>
                        <th>Model Pakaian</th>
                        <th>Ukuran</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-end">Ongkos/Pcs</th>
                        <th class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Tidak ada rekaman transaksi setoran pada rentang tanggal yang dipilih.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $r): ?>
                            <tr>
                                <td class="ps-3 small"><?= date('d/m/Y', strtotime($r['transaction_date'])) ?></td>
                                <td class="fw-semibold text-primary small"><?= esc($r['invoice_number']) ?></td>
                                <td><?= esc($r['tailor_name']) ?></td>
                                <td><?= esc($r['product_name']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($r['size']) ?></span></td>
                                <td class="text-end fw-bold text-success">+<?= esc((string)$r['quantity']) ?></td>
                                <td class="text-end small">Rp <?= number_format($r['sewing_cost_per_pcs'], 0, ',', '.') ?></td>
                                <td class="text-end pe-3 fw-bold">Rp <?= number_format($r['subtotal_cost'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-center">TOTAL KESELURUHAN</td>
                        <td class="text-end text-success"><?= number_format($totalPcs, 0, ',', '.') ?> Pcs</td>
                        <td></td>
                        <td class="text-end pe-3 text-primary">Rp <?= number_format($totalCost, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>