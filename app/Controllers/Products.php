<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductVariantModel;
use App\Models\CategoryModel;
use Config\Database;

class Products extends BaseController
{
    protected productModel $productModel;
    protected ProductVariantModel $variantModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->variantModel  = new ProductVariantModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Katalog Produk & Pakaian',
            'products' => $this->productModel->getProductsWithCategory(),
        ];
        return view('products/index', $data);
    }

    public function new()
    {
        $data = [
            'title'      => 'Tambah Model Produk Baru',
            'categories' => $this->categoryModel->findAll(),
            'sizes'      => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'],
        ];
        return view('products/new', $data);
    }

    public function store()
    {
        $db = Database::connect();

        $categoryId   = $this->request->getPost('category_id');
        $productCode  = strtoupper(trim($this->request->getPost('product_code')));
        $productName  = trim($this->request->getPost('product_name'));
        $description  = trim($this->request->getPost('description'));
        $selectedSizes = $this->request->getPost('sizes'); // Array ukuran yang dicentang
        $safetyStock  = (int) ($this->request->getPost('safety_stock') ?? 10);

        if (empty($selectedSizes) || !is_array($selectedSizes)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu variasi ukuran pakaian!');
        }

        // Cek duplikasi kode produk
        if ($this->productModel->where('product_code', $productCode)->first()) {
            return redirect()->back()->withInput()->with('error', "Kode Produk '{$productCode}' sudah digunakan.");
        }

        $db->transBegin();

        try {
            // 1. Simpan Header Produk
            $this->productModel->insert([
                'category_id'  => $categoryId,
                'product_code' => $productCode,
                'product_name' => $productName,
                'description'  => $description,
            ]);
            $productId = $this->productModel->getInsertID();

            // 2. Buat Variasi Ukuran & Generate SKU Otomatis
            foreach ($selectedSizes as $size) {
                $sku = $productCode . '-' . str_replace(' ', '', $size);
                
                $this->variantModel->insert([
                    'product_id'   => $productId,
                    'sku'          => $sku,
                    'size'         => $size,
                    'stock'        => 0, // Stok awal selalu 0, bertambah saat mutasi barang masuk
                    'safety_stock' => $safetyStock,
                ]);
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan produk dan variasi ukuran.');
            }

            $db->transCommit();
            return redirect()->to('/products')->with('success', 'Model produk dan variasi ukuran berhasil dibuat.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int|string $id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $category = $this->categoryModel->find($product['category_id']);
        $variants = $this->variantModel->getVariantsByProduct($id);

        $data = [
            'title'    => 'Detail Model & Stok Varian',
            'product'  => $product,
            'category' => $category,
            'variants' => $variants,
        ];

        return view('products/show', $data);
    }

    public function delete(int|string $id)
    {
        try {
            $this->productModel->delete($id);
            return redirect()->to('/products')->with('success', 'Produk dan variannya berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/products')->with('error', 'Gagal menghapus: Produk sudah terikat dengan riwayat transaksi inventaris.');
        }
    }
}