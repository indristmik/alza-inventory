<?php
/**
 * @var array<int, array<string, mixed>> $tailors
 * @var array<int, array<string, mixed>> $variants
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0 fw-bold">Pencatatan Surat Jalan / Setoran Penjahit</h6>
    </div>
    <div class="card-body p-4">
        <form action="/stock-in/store" method="POST" id="formStockIn">
            <?= csrf_field() ?>

            <!-- Bagian Header Transaksi -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Nomor Surat Jalan / Nota</label>
                    <input type="text" name="invoice_number" class="form-control" placeholder="Contoh: SJ-2026/09/001" required autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Mitra Penjahit (Konveksi)</label>
                    <select name="tailor_id" class="form-select" required>
                        <option value="">-- Pilih Penjahit --</option>
                        <?php foreach ($tailors as $tailor): ?>
                            <option value="<?= $tailor['id'] ?>"><?= esc($tailor['tailor_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tanggal Diterima</label>
                    <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Catatan Mutasi (Opsional)</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Setoran PO Batch September Kloter 1">
                </div>
            </div>

            <hr class="my-4">

            <!-- Bagian Dynamic Detail Item -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Rincian Pakaian yang Diterima</h6>
                <button type="button" class="btn btn-sm btn-outline-success" id="addRowBtn">+ Tambah Baris Pakaian</button>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 320px;">Model & Ukuran (SKU)</th>
                            <th style="width: 140px;">Jumlah (Pcs)</th>
                            <th style="width: 180px;">Ongkos Jahit/Pcs (Rp)</th>
                            <th style="width: 60px;" class="text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="variant_id[]" class="form-select" required>
                                    <option value="">-- Pilih Varian Pakaian --</option>
                                    <?php foreach ($variants as $v): ?>
                                        <option value="<?= $v['id'] ?>">
                                            <?= esc($v['product_name']) ?> [<?= esc($v['size']) ?>] - SKU: <?= esc($v['sku']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" class="form-control" min="1" placeholder="0" required>
                            </td>
                            <td>
                                <input type="number" name="sewing_cost[]" class="form-control" min="0" step="500" placeholder="Contoh: 15000" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger remove-row" disabled>&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between">
                <a href="/stock-in" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Setoran & Update Stok</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#itemsTable tbody');
    const addRowBtn = document.getElementById('addRowBtn');

    addRowBtn.addEventListener('click', function () {
        const firstRow = tableBody.querySelector('tr');
        const newRow = firstRow.cloneNode(true);

        // Reset nilai input baris baru
        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('.remove-row').removeAttribute('disabled');

        tableBody.appendChild(newRow);
    });

    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });
});
</script>
<?= $this->endSection() ?>