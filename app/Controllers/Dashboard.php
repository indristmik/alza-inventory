<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Mengambil data barang yang stoknya di ambang kritis atau habis dari database view
        $stockAlerts = $db->table('v_stock_monitoring')
            ->whereIn('stock_status', ['Kritis', 'Habis'])
            ->orderBy('stock', 'ASC')
            ->get()
            ->getResultArray();

        // Menghitung total ringkasan untuk kartu dashboard
        $totalProducts = $db->table('products')->countAllResults();
        $totalVariants = $db->table('product_variants')->countAllResults();

        $data = [
            'title'         => 'Dashboard Monitoring',
            'stockAlerts'   => $stockAlerts,
            'totalProducts' => $totalProducts,
            'totalVariants' => $totalVariants,
        ];

        // Memanggil view dengan template layout
        return view('dashboard/index', $data);
    }
}