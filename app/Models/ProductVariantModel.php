<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductVariantModel extends Model
{
    protected $table            = 'product_variants';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['product_id', 'sku', 'size', 'stock', 'safety_stock'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getVariantsByProductint (int|string $productId)
    {
        return $this->where('product_id', $productId)->findAll();
    }
}