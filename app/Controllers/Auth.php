<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung lempar ke dashboard
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function process()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        // Cari data pengguna berdasarkan username
        $user = $userModel->where('username', $username)->first();

        // Verifikasi password terhadap hash BCRYPT di database
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session login
            session()->set([
                'user_id'      => $user['id'],
                'name'         => $user['name'],
                'username'     => $user['username'],
                'role'         => $user['role'],
                'is_logged_in' => true,
            ]);

            return redirect()->to('/dashboard');
        }

        // Jika salah, kembalikan dengan pesan flash error
        return redirect()->back()->withInput()->with('error', 'Username atau Password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}