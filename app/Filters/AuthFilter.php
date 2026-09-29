<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika belum login, redirect ke form login
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan
    }
}