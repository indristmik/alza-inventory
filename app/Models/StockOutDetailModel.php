<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOutDetailModel extends Model
{
    protected $table         = 'stock_out_details';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['stock_out_id', 'variant_id', 'quantity'];
    protected $useTimestamps = false;

    public function getDetailsByStockOutId(int|string $stockOutId): array
    {
        return $this->select('stock_out_details.*, product_variants.sku, product_variants.size, products.product_name')
                    ->join('product_variants', 'product_variants.id = stock_out_details.variant_id')
                    ->join('products', 'products.id = product_variants.product_id')
                    ->where('stock_out_details.stock_out_id', $stockOutId)
                    ->findAll();
    }
}