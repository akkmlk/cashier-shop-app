<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\Kategori\KategoriController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Promo\PromoController;
use App\Http\Controllers\Register\RegisrationController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Transaksi\TransaksiController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->name('home')->middleware('auth');

Route::middleware(['guest'])->group(function() {
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.login');
    Route::get('register', [RegisrationController::class, 'index'])->name('register');
    Route::post('register', [RegisrationController::class, 'register'])->name('register.register');
    Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password.index');
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgot'])->name('forgot-password');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'indexResetPassword'])->name('password.reset');
    Route::put('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::singleton('profile', ProfileController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('product', ProductController::class);
    Route::get('stock/product', [StockController::class, 'product'])->name('stock.product');
    Route::resource('stock', StockController::class)->only('index', 'create', 'store', 'destroy');

    Route::get('transaksi/product', [TransaksiController::class, 'product'])->name('transaksi.product');
    Route::get('transaksi/pelanggan/search', [TransaksiController::class, 'pelanggan'])->name('transaksi.pelanggan');
    Route::get('transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
    Route::post('transaksi/pelanggan', [TransaksiController::class, 'addPelanggan'])->name('transaksi.pelanggan.add');
    Route::get('transaksi/pelanggan', [TransaksiController::class, 'pelanggan'])->name('transaksi.pelanggan');

    Route::resource('transaksi', TransaksiController::class)->except('edit', 'update');
    Route::get('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::resource('cart', CartController::class)->except('create', 'edit', 'show')->parameters(['cart' => 'hash']);

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    
    Route::middleware(['can:admin'])->group(function() {
        Route::resource('kategori', KategoriController::class);
        Route::resource('user', UserController::class);
    });
});
