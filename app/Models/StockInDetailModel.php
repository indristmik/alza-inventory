<?php

namespace App\Models;

use CodeIgniter\Model;

class StockInDetailModel extends Model
{
    protected $table         = 'stock_in_details';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['stock_in_id', 'variant_id', 'quantity', 'sewing_cost_per_pcs'];
    protected $useTimestamps = false;

    public function getDetailsByStockInId(int|string $stockInId)
    {
        return $this->select('stock_in_details.*, product_variants.sku, product_variants.size, products.product_name')
                    ->join('product_variants', 'product_variants.id = stock_in_details.variant_id')
                    ->join('products', 'products.id = product_variants.product_id')
                    ->where('stock_in_details.stock_in_id', $stockInId)
                    ->findAll();
    }
}