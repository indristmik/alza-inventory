<?php

namespace App\Models;

use CodeIgniter\Model;

class TailorModel extends Model
{
    protected $table         = 'tailors';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['tailor_name', 'phone_number', 'address'];
    protected $useTimestamps = false;
}