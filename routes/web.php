<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController as Admin;
use App\Http\Controllers\Admin\NhanSuController as AdminNhanSu;
use App\Http\Controllers\Admin\DichVuController as AdminDichVu;
use App\Http\Controllers\Admin\KhachHangController as AdminKhachHang;

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [Admin::class, 'index'])->name('index');
    Route::group(['prefix' => 'nhan-su'], function () {
        Route::get('/', [AdminNhanSu::class, 'index'])->name('nhan-su.index');
        Route::get('/danh-sach', [AdminNhanSu::class, 'danhSach'])->name('nhan-su.danh-sach');
        Route::post('/danh-sach', [AdminNhanSu::class, 'store'])->name('nhan-su.store');
        Route::put('/danh-sach/{user}', [AdminNhanSu::class, 'update'])->name('nhan-su.update');
        Route::put('/doi-mat-khau/{user}', [AdminNhanSu::class, 'doiMatKhau'])->name('nhan-su.doi-mat-khau');
        Route::delete('/danh-sach/{user}', [AdminNhanSu::class, 'destroy'])->name('nhan-su.destroy');
        Route::get('/phan-quyen', [AdminNhanSu::class, 'phanQuyen'])->name('nhan-su.phan-quyen');
        Route::get('/lich-lam-viec', [AdminNhanSu::class, 'lichLamViec'])->name('nhan-su.lich-lam-viec');
    });
    //Route cho khách hàng
    Route::group(['prefix' => 'khach-hang'], function () {
        Route::get('/', [AdminKhachHang::class, 'index'])->name('khach-hang.index');
        Route::get('/danh-sach', [AdminKhachHang::class, 'danhSach'])->name('khach-hang.danh-sach');
        Route::post('/danh-sach', [AdminKhachHang::class, 'store'])->name('khach-hang.store');
        Route::put('/danh-sach/{khachHang}', [AdminKhachHang::class, 'update'])->name('khach-hang.update');
        Route::delete('/danh-sach/{khachHang}', [AdminKhachHang::class, 'destroy'])->name('khach-hang.destroy');
    });
    //route cho dịch vụ
    Route::group(['prefix' => 'dich-vu'], function () {
        Route::get('/', [AdminDichVu::class, 'index'])->name('dich-vu.index');
        Route::get('/dich-vu-le', [AdminDichVu::class, 'dichVuLe'])->name('dich-vu.dich-vu-le');
        Route::post('/dich-vu-le', [AdminDichVu::class, 'store'])->name('dich-vu.store');
        Route::put('/dich-vu-le/{dichVu}', [AdminDichVu::class, 'update'])->name('dich-vu.update');
        Route::delete('/dich-vu-le/{dichVu}', [AdminDichVu::class, 'destroy'])->name('dich-vu.destroy');
        //Route cho nhóm dịch vụ
        Route::get('/nhom-dich-vu', [AdminDichVu::class, 'nhomDichVu'])->name('dich-vu.nhom-dich-vu');
        Route::post('/nhom-dich-vu', [AdminDichVu::class, 'storeNhomDichVu'])->name('dich-vu.store-nhom-dich-vu');
        Route::put('/nhom-dich-vu/{nhomDichVu}', [AdminDichVu::class, 'updateNhomDichVu'])->name('dich-vu.update-nhom-dich-vu');
        Route::delete('/nhom-dich-vu/{nhomDichVu}', [AdminDichVu::class, 'destroyNhomDichVu'])->name('dich-vu.destroy-nhom-dich-vu');

    });
});