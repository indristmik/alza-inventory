<?php

namespace App\Controllers;

use App\Models\StockInModel;
use App\Models\StockInDetailModel;
use App\Models\TailorModel;
use App\Models\ProductVariantModel;
use Config\Database;

class StockIn extends BaseController
{
    protected stockInModel $stockInModel ;
    protected StockInDetailModel $stockInDetailModel;
    protected TailorModel $tailorModel;
    protected ProductVariantModel $variantModel;

    public function __construct()
    {
        $this->stockInModel       = new StockInModel();
        $this->stockInDetailModel = new StockInDetailModel();
        $this->tailorModel        = new TailorModel();
        $this->variantModel       = new ProductVariantModel();
    }

    public function index()
    {
        $data = [
            'title'        => 'Riwayat Barang Masuk (Setoran Penjahit)',
            'transactions' => $this->stockInModel->getAllWithRelations(),
        ];
        return view('stock_in/index', $data);
    }

    public function new()
    {
        $db = Database::connect();

        // Mengambil seluruh SKU varian lengkap dengan nama produk untuk dropdown
        $variants = $db->table('product_variants pv')
            ->select('pv.id, pv.sku, pv.size, p.product_name')
            ->join('products p', 'p.id = pv.product_id')
            ->orderBy('p.product_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Input Setoran Baru dari Penjahit',
            'tailors'  => $this->tailorModel->findAll(),
            'variants' => $variants,
        ];
        return view('stock_in/new', $data);
    }

    public function store()
    {
        $db = Database::connect();

        $invoiceNumber   = trim($this->request->getPost('invoice_number'));
        $tailorId        = $this->request->getPost('tailor_id');
        $transactionDate = $this->request->getPost('transaction_date');
        $notes           = trim($this->request->getPost('notes'));
        $variantIds      = $this->request->getPost('variant_id');
        $quantities      = $this->request->getPost('quantity');
        $sewingCosts     = $this->request->getPost('sewing_cost');

        // Validasi input item minimal 1 baris
        if (empty($variantIds) || !is_array($variantIds)) {
            return redirect()->back()->withInput()->with('error', 'Harap masukkan minimal satu item pakaian!');
        }

        // Cek duplikasi nomor nota/surat jalan
        if ($this->stockInModel->where('invoice_number', $invoiceNumber)->first()) {
            return redirect()->back()->withInput()->with('error', "Nomor Surat Jalan/Nota '{$invoiceNumber}' sudah pernah diinput.");
        }

        $db->transBegin();

        try {
            // 1. Simpan Header Transaksi
            $this->stockInModel->insert([
                'invoice_number'   => $invoiceNumber,
                'tailor_id'        => $tailorId,
                'user_id'          => session()->get('user_id'),
                'transaction_date' => $transactionDate,
                'notes'            => $notes,
            ]);
            $stockInId = $this->stockInModel->getInsertID();

            // 2. Loop Detail Item, Simpan dan Tambahkan Stok Fisik
            foreach ($variantIds as $index => $variantId) {
                $qty  = (int) $quantities[$index];
                $cost = (float) $sewingCosts[$index];

                if ($qty <= 0) {
                    throw new \Exception("Jumlah pakaian pada salah satu baris harus lebih dari 0.");
                }

                // Insert baris detail
                $this->stockInDetailModel->insert([
                    'stock_in_id'         => $stockInId,
                    'variant_id'          => $variantId,
                    'quantity'            => $qty,
                    'sewing_cost_per_pcs' => $cost,
                ]);

                // Update saldo stok pada product_variants: stock = stock + $qty
                $db->table('product_variants')
                   ->where('id', $variantId)
                   ->set('stock', "stock + {$qty}", false)
                   ->update();
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses mutasi stok masuk.');
            }

            $db->transCommit();
            return redirect()->to('/stock-in')->with('success', "Setoran nota '{$invoiceNumber}' berhasil dicatat dan stok telah bertambah.");

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $transaction = $this->stockInModel->getAllWithRelations();
        $trxHeader = null;
        foreach ($transaction as $t) {
            if ($t['id'] == $id) {
                $trxHeader = $t;
                break;
            }
        }

        if (!$trxHeader) {
            return redirect()->to('/stock-in')->with('error', 'Data transaksi tidak ditemukan.');
        }

        $details = $this->stockInDetailModel->getDetailsByStockInId($id);

        $data = [
            'title'       => 'Detail Surat Jalan Masuk: ' . $trxHeader['invoice_number'],
            'transaction' => $trxHeader,
            'details'     => $details,
        ];
        return view('stock_in/show', $data);
    }
}