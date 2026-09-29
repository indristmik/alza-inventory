<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['category_id', 'product_code', 'product_name', 'description'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Mengambil produk beserta nama kategori
    public function getProductsWithCategory()
    {
        return $this->select('products.*, categories.category_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }
}