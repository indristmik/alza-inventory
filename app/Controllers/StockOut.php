<?php

namespace App\Controllers;

use App\Models\StockOutModel;
use App\Models\StockOutDetailModel;
use App\Models\ProductVariantModel;
use Config\Database;

class StockOut extends BaseController
{
    protected StockOutModel $stockOutModel;
    protected StockOutDetailModel $stockOutDetailModel;
    protected ProductVariantModel $variantModel;

    public function __construct()
    {
        $this->stockOutModel       = new StockOutModel();
        $this->stockOutDetailModel = new StockOutDetailModel();
        $this->variantModel        = new ProductVariantModel();
    }

    public function index()
    {
        $data = [
            'title'        => 'Riwayat Barang Keluar (Penjualan)',
            'transactions' => $this->stockOutModel->getAllWithRelations(),
        ];
        return view('stock_out/index', $data);
    }

    public function new()
    {
        $db = Database::connect();

        // Mengambil varian yang memiliki stok > 0 untuk memudahkan pemilihan
        $variants = $db->table('product_variants pv')
            ->select('pv.id, pv.sku, pv.size, pv.stock, p.product_name')
            ->join('products p', 'p.id = pv.product_id')
            ->orderBy('p.product_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Catat Pengeluaran / Penjualan Barang',
            'channels' => ['TikTok', 'Shopee', 'Lazada', 'Offline'],
            'variants' => $variants,
        ];
        return view('stock_out/new', $data);
    }

    public function store()
    {
        $db = Database::connect();

        $transactionCode = trim($this->request->getPost('transaction_code'));
        $channel         = $this->request->getPost('channel');
        $transactionDate = $this->request->getPost('transaction_date');
        $notes           = trim($this->request->getPost('notes'));
        $variantIds      = $this->request->getPost('variant_id');
        $quantities      = $this->request->getPost('quantity');

        if (empty($variantIds) || !is_array($variantIds)) {
            return redirect()->back()->withInput()->with('error', 'Harap masukkan minimal satu item pakaian!');
        }

        // Cek duplikasi kode transaksi / no resi
        if ($this->stockOutModel->where('transaction_code', $transactionCode)->first()) {
            return redirect()->back()->withInput()->with('error', "Kode Transaksi / No. Pesanan '{$transactionCode}' sudah pernah diinput.");
        }

        $db->transBegin();

        try {
            // 1. Simpan Header Transaksi Keluar
            $this->stockOutModel->insert([
                'transaction_code' => $transactionCode,
                'user_id'          => session()->get('user_id'),
                'channel'          => $channel,
                'transaction_date' => $transactionDate,
                'notes'            => $notes,
            ]);
            $stockOutId = $this->stockOutModel->getInsertID();

            // 2. Loop Detail Item & Validasi Saldo Stok
            foreach ($variantIds as $index => $variantId) {
                $qty = (int) $quantities[$index];

                if ($qty <= 0) {
                    throw new \Exception("Jumlah kuantitas item keluar harus lebih dari 0.");
                }

                // Ambil stok terkini dari database
                $variant = $this->variantModel->find($variantId);

                if (!$variant) {
                    throw new \Exception("Varian barang tidak ditemukan di sistem.");
                }

                // Validasi Anti-Stok Minus
                if ($variant['stock'] < $qty) {
                    throw new \Exception("Stok tidak mencukupi untuk SKU '{$variant['sku']}'. Stok saat ini: {$variant['stock']} Pcs, diminta: {$qty} Pcs.");
                }

                // Simpan baris detail
                $this->stockOutDetailModel->insert([
                    'stock_out_id' => $stockOutId,
                    'variant_id'   => $variantId,
                    'quantity'     => $qty,
                ]);

                // Kurangi stok fisik: stock = stock - $qty
                $db->table('product_variants')
                   ->where('id', $variantId)
                   ->set('stock', "stock - {$qty}", false)
                   ->update();
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi barang keluar.');
            }

            $db->transCommit();
            return redirect()->to('/stock-out')->with('success', "Transaksi '{$transactionCode}' berhasil dicatat dan stok telah berkurang.");

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int|string $id)
    {
        $transactions = $this->stockOutModel->getAllWithRelations();
        $trxHeader = null;
        foreach ($transactions as $t) {
            if ($t['id'] == $id) {
                $trxHeader = $t;
                break;
            }
        }

        if (!$trxHeader) {
            return redirect()->to('/stock-out')->with('error', 'Data transaksi keluar tidak ditemukan.');
        }

        $details = $this->stockOutDetailModel->getDetailsByStockOutId($id);

        $data = [
            'title'       => 'Detail Pengeluaran: ' . $trxHeader['transaction_code'],
            'transaction' => $trxHeader,
            'details'     => $details,
        ];
        return view('stock_out/show', $data);
    }
}