<?php
/**
 * @var array<int, array<string, mixed>> $categories
 * @var array<int, string> $sizes
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Tambah Katalog Model & Variasi Ukuran</h6>
            </div>
            <div class="card-body p-4">
                <form action="/products/store" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori Pakaian</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['category_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kode Produk</label>
                            <input type="text" name="product_code" class="form-control" placeholder="Contoh: KMJ-FLN-01" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Model Pakaian</label>
                        <input type="text" name="product_name" class="form-control" placeholder="Contoh: Kemeja Flanel Lengan Panjang Maroon" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi / Spesifikasi Bahan</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Katun flanel impor, jahitan double stik..."></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold d-block">Pilih Variasi Ukuran yang Diproduksi</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach ($sizes as $s): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="<?= $s ?>" id="size_<?= $s ?>" <?= in_array($s, ['M', 'L', 'XL']) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="size_<?= $s ?>"><?= $s ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted">Sistem akan otomatis membuat SKU unik per ukuran yang dicentang.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Ambang Batas Minimum (Safety Stock per Ukuran)</label>
                        <input type="number" name="safety_stock" class="form-control" value="10" min="1" style="max-width: 150px;" required>
                        <small class="text-muted">Jika stok varian $\le$ nilai ini, sistem akan memicu status <em>Kritis</em> di dashboard.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/products" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Model & Buat Varian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>