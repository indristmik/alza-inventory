<?php

namespace App\Controllers;

use App\Models\TailorModel;
use Config\Database;

class Reports extends BaseController
{
    protected TailorModel $tailorModel;

    public function __construct()
    {
        $this->tailorModel = new TailorModel();
    }

    /**
     * Laporan Rekap Ongkos Jahit / Setoran Maklun
     */
    public function sewingCost()
    {
        $db = Database::connect();

        // Ambil filter dari query parameter GET (default 30 hari terakhir)
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $tailorId  = $this->request->getGet('tailor_id');

        // Query rincian setoran penjahit dengan builder
        $builder = $db->table('stock_in_details sid')
            ->select('
                si.invoice_number,
                si.transaction_date,
                t.tailor_name,
                p.product_name,
                pv.size,
                pv.sku,
                sid.quantity,
                sid.sewing_cost_per_pcs,
                (sid.quantity * sid.sewing_cost_per_pcs) AS subtotal_cost
            ')
            ->join('stock_in si', 'si.id = sid.stock_in_id')
            ->join('tailors t', 't.id = si.tailor_id')
            ->join('product_variants pv', 'pv.id = sid.variant_id')
            ->join('products p', 'p.id = pv.product_id')
            ->where('si.transaction_date >=', $startDate)
            ->where('si.transaction_date <=', $endDate);

        if (!empty($tailorId)) {
            $builder->where('si.tailor_id', $tailorId);
        }

        $records = $builder->orderBy('si.transaction_date', 'DESC')->get()->getResultArray();

        // Hitung total ringkasan
        $totalPcs  = 0;
        $totalCost = 0;
        foreach ($records as $row) {
            $totalPcs  += (int) $row['quantity'];
            $totalCost += (float) $row['subtotal_cost'];
        }

        $data = [
            'title'      => 'Rekapitulasi Ongkos Jahit (Maklun)',
            'tailors'    => $this->tailorModel->findAll(),
            'records'    => $records,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'selectedTailor' => $tailorId,
            'totalPcs'   => $totalPcs,
            'totalCost'  => $totalCost,
        ];

        return view('reports/sewing_cost', $data);
    }
}