<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Form Tambah Penjahit -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Tambah Mitra Penjahit</h6>
            </div>
            <div class="card-body">
                <form action="/tailors/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Konveksi / Penjahit</label>
                        <input type="text" name="tailor_name" class="form-control" placeholder="Contoh: Konveksi Sejahtera" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">No. WhatsApp / HP</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Workshop</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Alamat penjahit..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Mitra</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Penjahit -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Daftar Mitra Penjahit Terdaftar</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Penjahit</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tailors)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada mitra penjahit.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tailors as $t): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?= esc($t['tailor_name']) ?></td>
                                        <td><?= esc($t['phone_number'] ?? '-') ?></td>
                                        <td><?= esc($t['address'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <a href="/tailors/delete/<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data penjahit ini?')">Hapus</a>
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