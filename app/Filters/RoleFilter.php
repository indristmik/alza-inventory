<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = session()->get('role');

        // Jika rute menetapkan argumen role (misal: ['admin'] atau ['owner'])
        // dan role pengguna saat ini tidak terdaftar di dalamnya
        if (!empty($arguments) && !in_array($userRole, $arguments)) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang pada modul ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan
    }
}