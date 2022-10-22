<?php


use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriBeritaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuplierController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


//require_once('includes/kategori.php');
require_once('includes/kategoriberita.php');

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');


    Route::resource('products', ProductController::class);



    Route::get('kategoris', [KategoriController::class, 'index']);
    Route::post('kategoris', [KategoriController::class, 'tambah'])->name('kategoris.tambah');


    Route::get('kategoris/list', [KategoriController::class, 'getKategoris'])->name('kategoris.list');

    Route::resource('kategoris', KategoriController::class);
    // Route::get('/kategoriberita', [KategoriBeritaController::class, 'index']);

    Route::get('supliers', [SuplierController::class, 'index']);
    Route::get('supliers/list', [SuplierController::class, 'getSupliers'])->name('supliers.list');
    Route::post('supliers', [SuplierController::class, 'store'])->name('supliers.store');
    Route::resource('supliers', SuplierController::class);



    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/list', [CustomerController::class, 'getCustomers'])->name('customers.list');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::resource('customers', CustomerController::class);

    Route::resource('orders', OrderController::class);
    Route::resource('penjualans', PenjualanController::class);
    //Route::get('/penjualans/cetaksantri', [PenjualanController::class, 'cetaksantri'])->name('penjualans.cetaksantri');

    Route::get('penjualan/list', [PenjualanController::class, 'getPenjualan'])->name('penjualans.list');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);



    Route::get('/laporan', 'App\Http\Controllers\LaporanController@index');
    Route::get('/exportlaporan', 'App\Http\Controllers\LaporanController@export');
    Route::get('/exportkwitansi', 'App\Http\Controllers\LaporanController@exportkwitansi');
    //Route::get('cetaksantri', 'App\Http\Controllers\PenjualanController@show');
    Route::get('cetaknota/{id}', [PenjualanController::class, 'cetaknota'])->name('cetaknota');
    Route::get('kategoriproduk/{id}', [ProductController::class, 'kategoriproduk'])->name('kategoriproduk');

});


