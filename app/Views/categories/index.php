<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Form Tambah Kategori -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Tambah Kategori</h6>
            </div>
            <div class="card-body">
                <form action="/categories/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control" placeholder="Contoh: Kemeja Flanel, Polos, Batik" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Daftar Kategori Pakaian</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 60px;">No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada kategori pakaian.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($categories as $cat): ?>
                                    <tr>
                                        <td class="ps-3 text-secondary"><?= $no++ ?></td>
                                        <td class="fw-semibold"><?= esc($cat['category_name']) ?></td>
                                        <td class="text-center">
                                            <a href="/categories/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>