<?php
/**
 * @var array<int, string> $channels
 * @var array<string, string> $reasons
 * @var array<string, string> $actions
 * @var array<int, array<string, mixed>> $variants
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Pencatatan Retur Barang Masuk</h6>
            </div>
            <div class="card-body p-4">
                <form action="/returns/store" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor / Kode Retur</label>
                            <input type="text" name="return_code" class="form-control" placeholder="Contoh: RET-202609001" required autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Diterima</label>
                            <input type="date" name="return_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Pilih Model Pakaian & Ukuran (SKU)</label>
                            <select name="variant_id" class="form-select" required>
                                <option value="">-- Pilih Varian Pakaian --</option>
                                <?php foreach ($variants as $v): ?>
                                    <option value="<?= $v['id'] ?>">
                                        <?= esc($v['product_name']) ?> [<?= esc($v['size']) ?>] — (SKU: <?= esc($v['sku']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jumlah Retur (Pcs)</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Asal Saluran Penjualan (Channel)</label>
                            <select name="channel" class="form-select" required>
                                <option value="">-- Pilih Channel --</option>
                                <?php foreach ($channels as $c): ?>
                                    <option value="<?= $c ?>"><?= $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Alasan Retur / Cacat</label>
                            <select name="reason" class="form-select" required>
                                <option value="">-- Pilih Alasan --</option>
                                <?php foreach ($reasons as $val => $label): ?>
                                    <option value="<?= $val ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keputusan Tindakan Lanjut</label>
                        <select name="action_taken" class="form-select" required>
                            <option value="">-- Pilih Tindakan --</option>
                            <?php foreach ($actions as $val => $label): ?>
                                <option value="<?= $val ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1">
                            *Pilihan <strong>Masuk Kembali ke Stok</strong> akan otomatis menaikkan stok fisik barang di gudang.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Keterangan / Deskripsi Cacat</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Jahitan lengan kiri sobek, pembeli minta tukar ukuran XL..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/returns" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Laporan Retur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>