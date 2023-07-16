<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    KategoriDetailController,
    LaporanController,
    LaporanPembelianController,
    LaporanPenjualanController,
    ProdukController,
    ProdukDetailController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    RestokController,
    PembayaranController,
    SupplierController,
    SupplierDetailController,
    UserController,
    UserDetailController,
};
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

Route::get('/', fn () => redirect()->route('login'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});


Route::group(['middleware', 'auth'], function  () {

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/kategoridetail/{id}', [KategoriDetailController::class, 'show'])->name('kategoridetail.show');
    Route::get('/kategoridetail/{id}', [KategoriDetailController::class, 'index'])->name('kategoridetail.index');
    Route::put('/kategoridetail/{id}', [KategoriDetailController::class, 'update'])->name('kategoridetail.update');
    Route::delete('/kategoridetail/{id}', [KategoriDetailController::class, 'destroy'])->name('kategoridetail.destroy');

    
    Route::get('/produk/index', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::resource('/produk', ProdukController::class);

    Route::get('/restok/index', [RestokController::class, 'index'])->name('restok.index');
    Route::post('/restok/store', [RestokController::class, 'store'])->name('restok.store');
    Route::get('/restok/data', [RestokController::class, 'data'])->name('restok.data');
    Route::put('/restok/{id}', [RestokController::class, 'update'])->name('restok.update');
    Route::resource('/restok', RestokController::class);

    Route::get('/produkdetail/{id}', [ProdukDetailController::class, 'show'])->name('produkdetail.show');
    Route::get('/produkdetail/{id}', [ProdukDetailController::class, 'index'])->name('produkdetail.index');
    Route::put('/produkdetail/{id}', [ProdukDetailController::class, 'update'])->name('produkdetail.update');
    Route::delete('/produkdetail/{id}', [ProdukDetailController::class, 'destroy'])->name('produkdetail.destroy');

    Route::get('/supplier', [KategoriController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/supplierdetail/{id}', [SupplierDetailController::class, 'show'])->name('supplierdetail.show');
    Route::get('/supplierdetail/{id}', [SupplierDetailController::class, 'index'])->name('supplierdetail.index');
    Route::put('/supplierdetail/{id}', [SupplierDetailController::class, 'update'])->name('supplierdetail.update');
    Route::delete('/supplierdetail/{id}', [SupplierDetailController::class, 'destroy'])->name('supplierdetail.destroy');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('/user', UserController::class);

    Route::get('/userdetail/{id}', [UserDetailController::class, 'index'])->name('userdetail.index');
    Route::put('/userdetail/{id}', [UserDetailController::class, 'update'])->name('userdetail.update');
    Route::delete('/userdetail/{id}', [UserDetailController::class, 'destroy'])->name('userdetail.destroy');

    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', PengeluaranController::class);

    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class)
        ->except('create');

    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PembelianDetailController::class)
        ->except('create', 'show', 'edit');

    
    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan/{id}/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::get('/penjualan/add_to_cart/{id}', [PenjualanController::class, 'addToCart'])->name('penjualan.add_to_cart');
    Route::patch('/penjualan/update-cart', [PenjualanController::class, 'update'])->name('update_cart');
    Route::patch('/penjualan/remove_from_cart', [PenjualanController::class, 'remove'])->name('remove_from_cart');  
    Route::get('/penjualan/cart', [PenjualanController::class, 'Cart'])->name('penjualan.cart');
    Route::get('/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
    Route::get('/penjualan/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('penjualan.nota_kecil');
    Route::resource('/penjualan', PenjualanController::class)
        ->except(['create']);

    Route::post('/pembayaran', [PembayaranController::class, 'session'])->name('pembayaran.session');
        
    
    Route::get('/penjualan_detail/{id}', [PenjualanDetailController::class, 'index'])->name('penjualan_detail.index');
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

    Route::get('/laporanpembelian', [LaporanPembelianController::class, 'index'])->name('laporanpembelian.index');
    Route::get('/laporanpembelian/data/{awal}/{akhir}', [LaporanPembelianController::class, 'data'])->name('laporanpembelian.data');
    Route::get('/laporanpembelian/pdf/{awal}/{akhir}', [LaporanPembelianController::class, 'exportPDF'])->name('laporan.export_pdf');
    
    Route::get('/laporanpenjualan', [LaporanPenjualanController::class, 'index'])->name('laporanpenjualan.index');
    Route::get('/laporanpenjualan/data/{awal}/{akhir}', [LaporanPenjualanController::class, 'data'])->name('laporanpenjualan.data');
    Route::get('/laporanpenjualan/pdf/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportPDF'])->name('laporan.export_pdf');
});