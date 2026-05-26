<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Main Routes Web
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'api'], function () {
    Route::get('/nguyen-lieu-tho/list', [HomeController::class, 'listNguyenLieuTho'])->name('api.nguyen.lieu.tho.list');
    Route::get('/nguyen-lieu-phan-loai/list', [HomeController::class, 'listNguyenLieuPhanLoai'])->name('api.nguyen.lieu.phan.loai.list');
    Route::get('/nguyen-lieu-tinh/list', [HomeController::class, 'listNguyenLieuTinh'])->name('api.nguyen.lieu.tinh.list');
    Route::get('/nguyen-lieu-san-xuat/list', [HomeController::class, 'listNguyenLieuSanXuat'])->name('api.nguyen.lieu.san.xuat.list');
    Route::get('/nguyen-lieu-thanh-pham/list', [HomeController::class, 'listNguyenLieuThanhPham'])->name('api.nguyen.lieu.thanh.pham.list');
    Route::get('/thong-tin-san-pham/detail', [HomeController::class, 'thongTinSanPham'])->name('api.thong.tin.san.pham.detail');
    Route::get('/thong-tin-khach-hang/detail', [HomeController::class, 'thongTinKhachHang'])->name('api.khach.hang.detail');
    Route::get('/chi-tiet-nguyen-lieu', [HomeController::class, 'chiTietNguyenLieu'])->name('api.chi.tiet.nguyen.lieu');
    Route::get('/nguon-hang-ban-hang', [HomeController::class, 'get_nguon_hang_ban_hang'])->name('api.nguon.hang.ban.hang');
});

