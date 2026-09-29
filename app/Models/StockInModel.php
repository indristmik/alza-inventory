<?php

namespace App\Models;

use CodeIgniter\Model;

class StockInModel extends Model
{
    protected $table         = 'stock_in';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['invoice_number', 'tailor_id', 'user_id', 'transaction_date', 'notes'];
    protected $useTimestamps = false;

    public function getAllWithRelations()
    {
        return $this->select('stock_in.*, tailors.tailor_name, users.name as admin_name')
                    ->join('tailors', 'tailors.id = stock_in.tailor_id')
                    ->join('users', 'users.id = stock_in.user_id')
                    ->orderBy('stock_in.transaction_date', 'DESC')
                    ->orderBy('stock_in.id', 'DESC')
                    ->findAll();
    }
}