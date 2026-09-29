<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected categoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Kategori Pakaian',
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('categories/index', $data);
    }

    public function store()
    {
        $categoryName = trim($this->request->getPost('category_name'));

        if (!empty($categoryName)) {
            $this->categoryModel->insert(['category_name' => $categoryName]);
            return redirect()->to('/categories')->with('success', 'Kategori berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Nama kategori tidak boleh kosong.');
    }

    public function delete(int|string $id)
    {
        try {
            $this->categoryModel->delete($id);
            return redirect()->to('/categories')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/categories')->with('error', 'Gagal menghapus: Kategori sedang digunakan oleh produk pakaian.');
        }
    }
}