<?php

namespace App\Controllers;

use App\Models\ItemReturnModel;
use App\Models\ProductVariantModel;
use Config\Database;

class ItemReturns extends BaseController
{
    protected ItemReturnModel $returnModel;
    protected ProductVariantModel $variantModel;

    public function __construct()
    {
        $this->returnModel  = new ItemReturnModel();
        $this->variantModel = new ProductVariantModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Riwayat Retur Pakaian',
            'returns' => $this->returnModel->getAllWithRelations(),
        ];
        return view('returns/index', $data);
    }

    public function new()
    {
        $db = Database::connect();

        $variants = $db->table('product_variants pv')
            ->select('pv.id, pv.sku, pv.size, p.product_name')
            ->join('products p', 'p.id = pv.product_id')
            ->orderBy('p.product_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Catat Retur Pakaian Masuk',
            'channels' => ['TikTok', 'Shopee', 'Lazada', 'Offline'],
            'reasons'  => [
                'cacat_jahitan' => 'Cacat Jahitan (Lepas / Miring)',
                'salah_ukuran'  => 'Salah Ukuran / Tukar Size',
                'rusak_kain'    => 'Rusak Kain / Cacat Bahan',
            ],
            'actions'  => [
                'masuk_kembali_stok'  => 'Masuk Kembali ke Stok (Layak Jual)',
                'kembali_ke_penjahit' => 'Kembalikan ke Penjahit (Maklun)',
                'dibuang'             => 'Dibuang / Afkir (Write-off)',
            ],
            'variants' => $variants,
        ];
        return view('returns/new', $data);
    }

    public function store()
    {
        $db = Database::connect();

        $returnCode  = trim($this->request->getPost('return_code'));
        $variantId   = $this->request->getPost('variant_id');
        $channel     = $this->request->getPost('channel');
        $quantity    = (int) $this->request->getPost('quantity');
        $reason      = $this->request->getPost('reason');
        $actionTaken = $this->request->getPost('action_taken');
        $returnDate  = $this->request->getPost('return_date');
        $notes       = trim($this->request->getPost('notes'));

        if ($quantity <= 0) {
            return redirect()->back()->withInput()->with('error', 'Jumlah barang retur harus lebih dari 0.');
        }

        // Cek duplikasi nomor retur
        if ($this->returnModel->where('return_code', $returnCode)->first()) {
            return redirect()->back()->withInput()->with('error', "Nomor Retur '{$returnCode}' sudah pernah terdaftar.");
        }

        $db->transBegin();

        try {
            // 1. Simpan Transaksi Retur
            $this->returnModel->insert([
                'return_code'  => $returnCode,
                'variant_id'   => $variantId,
                'user_id'      => session()->get('user_id'),
                'channel'      => $channel,
                'quantity'     => $quantity,
                'reason'       => $reason,
                'action_taken' => $actionTaken,
                'return_date'  => $returnDate,
                'notes'        => $notes,
            ]);

            // 2. Jika tindakannya masuk kembali ke stok, saldo gudang ditambah
            if ($actionTaken === 'masuk_kembali_stok') {
                $db->table('product_variants')
                   ->where('id', $variantId)
                   ->set('stock', "stock + {$quantity}", false)
                   ->update();
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi retur.');
            }

            $db->transCommit();

            $msg = ($actionTaken === 'masuk_kembali_stok') 
                ? "Retur '{$returnCode}' berhasil dicatat dan {$quantity} pcs telah dikembalikan ke stok aktif."
                : "Retur '{$returnCode}' berhasil dicatat (tidak menambah stok aktif gudang).";

            return redirect()->to('/returns')->with('success', $msg);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int|string $id)
    {
        $returns = $this->returnModel->getAllWithRelations();
        $ret = null;
        foreach ($returns as $r) {
            if ($r['id'] == $id) {
                $ret = $r;
                break;
            }
        }

        if (!$ret) {
            return redirect()->to('/returns')->with('error', 'Data retur tidak ditemukan.');
        }

        $data = [
            'title'  => 'Detail Retur: ' . $ret['return_code'],
            'return' => $ret,
        ];
        return view('returns/show', $data);
    }
}