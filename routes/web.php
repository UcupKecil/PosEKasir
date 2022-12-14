<?php


use App\Http\Controllers\CartController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductBuyController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\HistoryStockController;
use App\Http\Controllers\KategoriBeritaController;
use App\Http\Controllers\SettingController;


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
    Route::resource('productbuys', ProductBuyController::class);
    Route::resource('stoks', StokController::class);

    Route::get('history_stoks', [HistoryStockController::class, 'index'])->name('history_stoks.index');;
    Route::get('history_stoks/list', [HistoryStockController::class, 'getHistories'])->name('history_stoks.list');
    Route::resource('history_stoks', HistoryStockController::class);


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
    Route::resource('pembelians', PembelianController::class);


    Route::get('penjualan/list', [PenjualanController::class, 'getPenjualan'])->name('penjualans.list');
    Route::get('pembelian/list', [PembelianController::class, 'getPembelian'])->name('pembelians.list');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);

    Route::get('/buy', [BuyController::class, 'index'])->name('buy.index');
    Route::post('/buy', [BuyController::class, 'store'])->name('buy.store');
    Route::post('/buy/change-qty', [BuyController::class, 'changeQty']);
    Route::delete('/buy/delete', [BuyController::class, 'delete']);
    Route::delete('/buy/empty', [BuyController::class, 'empty']);



    Route::get('/laporan', 'App\Http\Controllers\LaporanController@index');
    Route::get('/exportlaporan', 'App\Http\Controllers\LaporanController@export');
    Route::get('/exportkwitansi', 'App\Http\Controllers\LaporanController@exportkwitansi');

    Route::get('cetaknotacustomer/{id}', [PenjualanController::class, 'cetaknotacustomer'])->name('cetaknotacustomer');
    Route::get('cetaknotasuplier/{id}', [PembelianController::class, 'cetaknotasuplier'])->name('cetaknotasuplier');
    Route::get('kategoriproduk/{id}', [ProductController::class, 'kategoriproduk'])->name('kategoriproduk');

});


