<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemReturnModel extends Model
{
    protected $table         = 'item_returns';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'return_code',
        'variant_id',
        'user_id',
        'channel',
        'quantity',
        'reason',
        'action_taken',
        'return_date',
        'notes',
    ];
    protected $useTimestamps = false;

    public function getAllWithRelations(): array
    {
        return $this->select('item_returns.*, product_variants.sku, product_variants.size, products.product_name, users.name as admin_name')
                    ->join('product_variants', 'product_variants.id = item_returns.variant_id')
                    ->join('products', 'products.id = product_variants.product_id')
                    ->join('users', 'users.id = item_returns.user_id')
                    ->orderBy('item_returns.return_date', 'DESC')
                    ->orderBy('item_returns.id', 'DESC')
                    ->findAll();
    }
}