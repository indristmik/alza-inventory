<?php

namespace App\Controllers;

use App\Models\TailorModel;

class Tailors extends BaseController
{
    protected $tailorModel;

    public function __construct()
    {
        $this->tailorModel = new TailorModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Daftar Mitra Penjahit (Maklun)',
            'tailors' => $this->tailorModel->findAll(),
        ];
        return view('tailors/index', $data);
    }

    public function store()
    {
        $this->tailorModel->insert([
            'tailor_name'  => $this->request->getPost('tailor_name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address'      => $this->request->getPost('address'),
        ]);

        return redirect()->to('/tailors')->with('success', 'Data mitra penjahit berhasil disimpan.');
    }

    public function delete($id)
    {
        try {
            $this->tailorModel->delete($id);
            return redirect()->to('/tailors')->with('success', 'Mitra penjahit berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/tailors')->with('error', 'Gagal menghapus: Penjahit sudah terikat dengan riwayat barang masuk.');
        }
    }
}