<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOutModel extends Model
{
    protected $table         = 'stock_out';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['transaction_code', 'user_id', 'channel', 'transaction_date', 'notes'];
    protected $useTimestamps = false;

    public function getAllWithRelations(): array
    {
        return $this->select('stock_out.*, users.name as admin_name')
                    ->join('users', 'users.id = stock_out.user_id')
                    ->orderBy('stock_out.transaction_date', 'DESC')
                    ->orderBy('stock_out.id', 'DESC')
                    ->findAll();
    }
}