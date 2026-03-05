<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController as Admin;
use App\Http\Controllers\Admin\NhanSuController as AdminNhanSu;

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
});