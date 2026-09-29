<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Publik (Autentikasi)
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');

// 2. Rute Terproteksi (Wajib Login: Admin & Owner)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    
    // Halaman Dashboard Bersama
    $routes->get('dashboard', 'Dashboard::index');

    // --------------------------------------------------
    // RUTE KHUSUS OPERASIONAL (ROLE: ADMIN)
    // --------------------------------------------------
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        
        // Master Data Mitra Penjahit (Maklun)
        $routes->get('tailors', 'Tailors::index');
        $routes->post('tailors/store', 'Tailors::store');
        $routes->get('tailors/delete/(:num)', 'Tailors::delete/$1');

        // (Nanti Master Kategori, Produk, dan Transaksi Mutasi ditaruh di sini)

        $routes->get('categories', 'Categories::index');
        $routes->post('categories/store', 'Categories::store');
        $routes->get('categories/delete/(:num)', 'Categories::delete/$1');


        $routes->get('products', 'Products::index');
        $routes->get('products/new', 'Products::new');
        $routes->post('products/store', 'Products::store');
        $routes->get('products/show/(:num)', 'Products::show/$1');
        $routes->get('products/delete/(:num)', 'Products::delete/$1');

        $routes->get('stock-in', 'StockIn::index');
        $routes->get('stock-in/new', 'StockIn::new');
        $routes->post('stock-in/store', 'StockIn::store');
        $routes->get('stock-in/show/(:num)', 'StockIn::show/$1');


        $routes->get('stock-out', 'StockOut::index');
        $routes->get('stock-out/new', 'StockOut::new');
        $routes->post('stock-out/store', 'StockOut::store');
        $routes->get('stock-out/show/(:num)', 'StockOut::show/$1');

        $routes->get('returns', 'ItemReturns::index');
        $routes->get('returns/new', 'ItemReturns::new');
        $routes->post('returns/store', 'ItemReturns::store');
        $routes->get('returns/show/(:num)', 'ItemReturns::show/$1');

    });

    // --------------------------------------------------
    // RUTE KHUSUS MANAJERIAL (ROLE: OWNER)
    // --------------------------------------------------
    $routes->group('', ['filter' => 'role:owner'], function ($routes) {
        
        // (Nanti Laporan Keuangan, Omset, dan Manajemen User ditaruh di sini)
    });
});