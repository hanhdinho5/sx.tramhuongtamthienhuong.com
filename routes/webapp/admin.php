<?php

/*
|--------------------------------------------------------------------------
| Admin Routes Web
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\admin\AdminBanHangController;
use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminKhachHangController;
use App\Http\Controllers\admin\AdminLoaiQuyController;
use App\Http\Controllers\admin\AdminNguyenLieuPhanLoaiController;
use App\Http\Controllers\admin\AdminNguyenLieuSanXuatController;
use App\Http\Controllers\admin\AdminNguyenLieuThanhPhamController;
use App\Http\Controllers\admin\AdminNguyenLieuThoController;
use App\Http\Controllers\admin\AdminNguyenLieuTinhController;
use App\Http\Controllers\admin\AdminNhaCungCapController;
use App\Http\Controllers\admin\AdminNhomKhachHangController;
use App\Http\Controllers\admin\AdminNhomQuyController;
use App\Http\Controllers\admin\AdminPhieuSanXuatController;
use App\Http\Controllers\admin\AdminSanPhamController;
use App\Http\Controllers\admin\AdminSettingController;
use App\Http\Controllers\admin\AdminSoQuyController;
use App\Http\Controllers\admin\AdminThongTinController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\LichSuThanhToanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.home');

Route::group(['prefix' => 'app-settings'], function () {
    Route::get('/index', [AdminSettingController::class, 'index'])->name('admin.app.setting.index');
    Route::post('/store', [AdminSettingController::class, 'appSetting'])->name('admin.app.setting.store');
});

Route::group(['prefix' => 'nha-cung-cap'], function () {
    Route::get('/index', [AdminNhaCungCapController::class, 'index'])->name('admin.nha.cung.cap.index');
    Route::get('/payment', [AdminSoQuyController::class, 'payment'])->name('admin.nha.cung.cap.payment');
    Route::get('/detail/{id}', [AdminNhaCungCapController::class, 'detail'])->name('admin.nha.cung.cap.detail');
    Route::post('/store', [AdminNhaCungCapController::class, 'store'])->name('admin.nha.cung.cap.store');
    Route::put('/update/{id}', [AdminNhaCungCapController::class, 'update'])->name('admin.nha.cung.cap.update');
    Route::delete('/delete/{id}', [AdminNhaCungCapController::class, 'delete'])->name('admin.nha.cung.cap.delete');
});

Route::group(['prefix' => 'nguyen-lieu-tho'], function () {
    Route::get('/index', [AdminNguyenLieuThoController::class, 'index'])->name('admin.nguyen.lieu.tho.index');
    Route::get('/detail/{id}', [AdminNguyenLieuThoController::class, 'detail'])->name('admin.nguyen.lieu.tho.detail');
    Route::post('/store', [AdminNguyenLieuThoController::class, 'store'])->name('admin.nguyen.lieu.tho.store');
    Route::put('/update/{id}', [AdminNguyenLieuThoController::class, 'update'])->name('admin.nguyen.lieu.tho.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuThoController::class, 'delete'])->name('admin.nguyen.lieu.tho.delete');
});

Route::group(['prefix' => 'nguyen-lieu-phan-loai'], function () {
    Route::get('/index', [AdminNguyenLieuPhanLoaiController::class, 'index'])->name('admin.nguyen.lieu.phan.loai.index');
    Route::get('/detail/{id}', [AdminNguyenLieuPhanLoaiController::class, 'detail'])->name('admin.nguyen.lieu.phan.loai.detail');
    Route::post('/store', [AdminNguyenLieuPhanLoaiController::class, 'store'])->name('admin.nguyen.lieu.phan.loai.store');
    Route::put('/update/{id}', [AdminNguyenLieuPhanLoaiController::class, 'update'])->name('admin.nguyen.lieu.phan.loai.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuPhanLoaiController::class, 'delete'])->name('admin.nguyen.lieu.phan.loai.delete');
});

Route::group(['prefix' => 'nguyen-lieu-tinh'], function () {
    Route::get('/index', [AdminNguyenLieuTinhController::class, 'index'])->name('admin.nguyen.lieu.tinh.index');
    Route::get('/detail/{id}', [AdminNguyenLieuTinhController::class, 'detail'])->name('admin.nguyen.lieu.tinh.detail');
    Route::post('/store', [AdminNguyenLieuTinhController::class, 'store'])->name('admin.nguyen.lieu.tinh.store');
    Route::put('/update/{id}', [AdminNguyenLieuTinhController::class, 'update'])->name('admin.nguyen.lieu.tinh.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuTinhController::class, 'delete'])->name('admin.nguyen.lieu.tinh.delete');
});

Route::group(['prefix' => 'phieu-san-xuat'], function () {
    Route::get('/index', [AdminPhieuSanXuatController::class, 'index'])->name('admin.phieu.san.xuat.index');
    Route::get('/detail/{id}', [AdminPhieuSanXuatController::class, 'detail'])->name('admin.phieu.san.xuat.detail');
    Route::post('/store', [AdminPhieuSanXuatController::class, 'store'])->name('admin.phieu.san.xuat.store');
    Route::put('/update/{id}', [AdminPhieuSanXuatController::class, 'update'])->name('admin.phieu.san.xuat.update');
    Route::delete('/delete/{id}', [AdminPhieuSanXuatController::class, 'delete'])->name('admin.phieu.san.xuat.delete');
});

Route::group(['prefix' => 'nguyen-lieu-thanh-pham'], function () {
    Route::get('/index', [AdminNguyenLieuThanhPhamController::class, 'index'])->name('admin.nguyen.lieu.thanh.pham.index');
    Route::get('/detail/{id}', [AdminNguyenLieuThanhPhamController::class, 'detail'])->name('admin.nguyen.lieu.thanh.pham.detail');
    Route::post('/store', [AdminNguyenLieuThanhPhamController::class, 'store'])->name('admin.nguyen.lieu.thanh.pham.store');
    Route::put('/update/{id}', [AdminNguyenLieuThanhPhamController::class, 'update'])->name('admin.nguyen.lieu.thanh.pham.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuThanhPhamController::class, 'delete'])->name('admin.nguyen.lieu.thanh.pham.delete');
});

Route::group(['prefix' => 'nguyen-lieu-san-xuat'], function () {
    Route::get('/index', [AdminNguyenLieuSanXuatController::class, 'index'])->name('admin.nguyen.lieu.san.xuat.index');
    Route::get('/detail/{id}', [AdminNguyenLieuSanXuatController::class, 'detail'])->name('admin.nguyen.lieu.san.xuat.detail');
    Route::post('/store', [AdminNguyenLieuSanXuatController::class, 'store'])->name('admin.nguyen.lieu.san.xuat.store');
    Route::put('/update/{id}', [AdminNguyenLieuSanXuatController::class, 'update'])->name('admin.nguyen.lieu.san.xuat.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuSanXuatController::class, 'delete'])->name('admin.nguyen.lieu.san.xuat.delete');
});

Route::group(['prefix' => 'nhom-khach-hang'], function () {
    Route::get('/index', [AdminNhomKhachHangController::class, 'index'])->name('admin.nhom.khach.hang.index');
    Route::get('/detail/{id}', [AdminNhomKhachHangController::class, 'detail'])->name('admin.nhom.khach.hang.detail');
    Route::post('/store', [AdminNhomKhachHangController::class, 'store'])->name('admin.nhom.khach.hang.store');
    Route::put('/update/{id}', [AdminNhomKhachHangController::class, 'update'])->name('admin.nhom.khach.hang.update');
    Route::delete('/delete/{id}', [AdminNhomKhachHangController::class, 'delete'])->name('admin.nhom.khach.hang.delete');
});

Route::group(['prefix' => 'khach-hang'], function () {
    Route::get('/index', [AdminKhachHangController::class, 'index'])->name('admin.khach.hang.index');
    Route::get('/detail/{id}', [AdminKhachHangController::class, 'detail'])->name('admin.khach.hang.detail');
    Route::post('/store', [AdminKhachHangController::class, 'store'])->name('admin.khach.hang.store');
    Route::put('/update/{id}', [AdminKhachHangController::class, 'update'])->name('admin.khach.hang.update');
    Route::delete('/delete/{id}', [AdminKhachHangController::class, 'delete'])->name('admin.khach.hang.delete');
});

Route::group(['prefix' => 'profile'], function () {
    Route::get('/index', [UserController::class, 'index'])->name('admin.profile.index');
    Route::post('/change-info', [UserController::class, 'changeInfo'])->name('admin.profile.change.info');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('admin.profile.change.password');
});

Route::group(['prefix' => 'nhan-vien'], function () {
    Route::get('/list', [AdminUserController::class, 'list'])->name('admin.nhan.vien.list');
    Route::get('/detail/{id}', [AdminUserController::class, 'detail'])->name('admin.nhan.vien.detail');
    Route::get('/create', [AdminUserController::class, 'create'])->name('admin.nhan.vien.create');
    Route::post('/store', [AdminUserController::class, 'store'])->name('admin.nhan.vien.store');
    Route::put('/update/{id}', [AdminUserController::class, 'update'])->name('admin.nhan.vien.update');
    Route::delete('/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.nhan.vien.delete');
});

Route::group(['prefix' => 'thong-tin'], function () {
    Route::get('/index', [AdminThongTinController::class, 'index'])->name('admin.thong.tin.index');
    Route::get('/detail/{id}', [AdminThongTinController::class, 'detail'])->name('admin.thong.tin.detail');
    Route::post('/store', [AdminThongTinController::class, 'store'])->name('admin.thong.tin.store');
    Route::put('/update/{id}', [AdminThongTinController::class, 'update'])->name('admin.thong.tin.update');
    Route::delete('/delete/{id}', [AdminThongTinController::class, 'delete'])->name('admin.thong.tin.delete');
});

Route::group(['prefix' => 'san-pham'], function () {
    Route::get('/index', [AdminSanPhamController::class, 'index'])->name('admin.san.pham.index');
    Route::get('/detail/{id}', [AdminSanPhamController::class, 'detail'])->name('admin.san.pham.detail');
    Route::post('/store', [AdminSanPhamController::class, 'store'])->name('admin.san.pham.store');
    Route::put('/update/{id}', [AdminSanPhamController::class, 'update'])->name('admin.san.pham.update');
    Route::delete('/delete/{id}', [AdminSanPhamController::class, 'delete'])->name('admin.san.pham.delete');
});

Route::group(['prefix' => 'ban-hang'], function () {
    Route::get('/index', [AdminBanHangController::class, 'index'])->name('admin.ban.hang.index');
    Route::get('/create', [AdminBanHangController::class, 'create'])->name('admin.ban.hang.create');
    Route::get('/detail/{id}', [AdminBanHangController::class, 'detail'])->name('admin.ban.hang.detail');
    Route::post('/store', [AdminBanHangController::class, 'store'])->name('admin.ban.hang.store');
    Route::put('/update/{id}', [AdminBanHangController::class, 'update'])->name('admin.ban.hang.update');
    Route::delete('/delete/{id}', [AdminBanHangController::class, 'delete'])->name('admin.ban.hang.delete');
});

Route::group(['prefix' => 'lich-su-thanh-toan'], function () {
    Route::post('/store', [LichSuThanhToanController::class, 'store'])->name('admin.thanh.toan.store');
});

Route::group(['prefix' => 'loai-quy'], function () {
    Route::get('/index', [AdminLoaiQuyController::class, 'index'])->name('admin.loai.quy.index');
    Route::get('/detail/{id}', [AdminLoaiQuyController::class, 'detail'])->name('admin.loai.quy.detail');
    Route::post('/store', [AdminLoaiQuyController::class, 'store'])->name('admin.loai.quy.store');
    Route::put('/update/{id}', [AdminLoaiQuyController::class, 'update'])->name('admin.loai.quy.update');
    Route::delete('/delete/{id}', [AdminLoaiQuyController::class, 'delete'])->name('admin.loai.quy.delete');
});

Route::group(['prefix' => 'so-quy'], function () {
    Route::get('/index', [AdminSoQuyController::class, 'index'])->name('admin.so.quy.index');
    Route::post('/payment', [AdminSoQuyController::class, 'payment_store'])->name('admin.so.quy.store.payment');
    Route::get('/detail/{id}', [AdminSoQuyController::class, 'detail'])->name('admin.so.quy.detail');
    Route::post('/store', [AdminSoQuyController::class, 'store'])->name('admin.so.quy.store');
    Route::put('/update/{id}', [AdminSoQuyController::class, 'update'])->name('admin.so.quy.update');
    Route::delete('/delete/{id}', [AdminSoQuyController::class, 'delete'])->name('admin.so.quy.delete');
});

Route::group(['prefix' => 'nhom-quy'], function () {
    Route::get('/index', [AdminNhomQuyController::class, 'index'])->name('admin.nhom.quy.index');
    Route::get('/detail/{id}', [AdminNhomQuyController::class, 'detail'])->name('admin.nhom.quy.detail');
    Route::post('/store', [AdminNhomQuyController::class, 'store'])->name('admin.nhom.quy.store');
    Route::put('/update/{id}', [AdminNhomQuyController::class, 'update'])->name('admin.nhom.quy.update');
    Route::delete('/delete/{id}', [AdminNhomQuyController::class, 'delete'])->name('admin.nhom.quy.delete');
});

Route::group(['prefix' => 'api'], function () {
    Route::delete('/delete/items', [AdminHomeController::class, 'deleteItem'])->name('api.admin.delete.items');

    Route::group(['prefix' => 'nha-cung-cap'], function () {
        Route::get('/show', [AdminNhaCungCapController::class, 'show'])->name('api.nha.cung.cap.show');
    });

    Route::group(['prefix' => 'khach-hang'], function () {
        Route::get('/show', [AdminKhachHangController::class, 'show'])->name('api.khach.hang.show');
    });
});
