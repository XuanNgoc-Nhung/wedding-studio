<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController as Admin;
use App\Http\Controllers\Admin\NhanSuController as AdminNhanSu;
use App\Http\Controllers\Admin\DichVuController as AdminDichVu;
use App\Http\Controllers\Admin\KhachHangController as AdminKhachHang;
use App\Http\Controllers\Admin\TrangPhucController as AdminTrangPhuc;
use App\Http\Controllers\Admin\TaiChinhKeToanController as AdminTaiChinhKeToan;

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
    //Route cho trang phục
    Route::group(['prefix' => 'trang-phuc'], function () {
        Route::get('/', [AdminTrangPhuc::class, 'index'])->name('trang-phuc.index');
        Route::get('/san-pham', [AdminTrangPhuc::class, 'sanPham'])->name('trang-phuc.san-pham');
        Route::post('/san-pham', [AdminTrangPhuc::class, 'storeSanPham'])->name('trang-phuc.san-pham.store');
        Route::put('/san-pham/{trangPhuc}', [AdminTrangPhuc::class, 'updateSanPham'])->name('trang-phuc.san-pham.update');
        Route::delete('/san-pham/{trangPhuc}', [AdminTrangPhuc::class, 'destroySanPham'])->name('trang-phuc.san-pham.destroy');
        Route::get('/kho-hang', [AdminTrangPhuc::class, 'khoHang'])->name('trang-phuc.kho-hang');
        Route::post('/kho-hang', [AdminTrangPhuc::class, 'storeKhoHang'])->name('trang-phuc.store-kho-hang');
        Route::put('/kho-hang/{khoHang}', [AdminTrangPhuc::class, 'updateKhoHang'])->name('trang-phuc.update-kho-hang');
        Route::delete('/kho-hang/{khoHang}', [AdminTrangPhuc::class, 'destroyKhoHang'])->name('trang-phuc.destroy-kho-hang');
        Route::get('/hop-dong', [AdminTrangPhuc::class, 'hopDong'])->name('trang-phuc.hop-dong');
        Route::post('/hop-dong', [AdminTrangPhuc::class, 'storeHopDong'])->name('trang-phuc.store-hop-dong');
        Route::put('/hop-dong/{hopDong}', [AdminTrangPhuc::class, 'updateHopDong'])->name('trang-phuc.update-hop-dong');
        Route::delete('/hop-dong/{hopDong}', [AdminTrangPhuc::class, 'destroyHopDong'])->name('trang-phuc.destroy-hop-dong');
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
    //Route cho tài chính kế toán (bao gồm công nợ và phiếu thu chi)
    Route::group(['prefix' => 'tai-chinh'], function () {
        Route::get('/', [AdminTaiChinhKeToan::class, 'index'])->name('tai-chinh.index');
        Route::get('/cong-no', [AdminTaiChinhKeToan::class, 'congNo'])->name('tai-chinh.cong-no');
        Route::post('/cong-no', [AdminTaiChinhKeToan::class, 'storeCongNo'])->name('tai-chinh.store-cong-no');
        Route::put('/cong-no/{congNo}', [AdminTaiChinhKeToan::class, 'updateCongNo'])->name('tai-chinh.update-cong-no');
        Route::delete('/cong-no/{congNo}', [AdminTaiChinhKeToan::class, 'destroyCongNo'])->name('tai-chinh.destroy-cong-no');
        Route::get('/phieu-thu-chi', [AdminTaiChinhKeToan::class, 'phieuThuChi'])->name('tai-chinh.phieu-thu-chi');
        Route::post('/phieu-thu-chi', [AdminTaiChinhKeToan::class, 'storePhieuThuChi'])->name('tai-chinh.store-phieu-thu-chi');
        Route::put('/phieu-thu-chi/{phieuThuChi}', [AdminTaiChinhKeToan::class, 'updatePhieuThuChi'])->name('tai-chinh.update-phieu-thu-chi');
        Route::put('/phieu-thu-chi/{phieuThuChi}/duyet', [AdminTaiChinhKeToan::class, 'duyetPhieuThuChi'])->name('tai-chinh.duyet-phieu-thu-chi');
        Route::put('/phieu-thu-chi/{phieuThuChi}/huy', [AdminTaiChinhKeToan::class, 'huyPhieuThuChi'])->name('tai-chinh.huy-phieu-thu-chi');
        Route::delete('/phieu-thu-chi/{phieuThuChi}', [AdminTaiChinhKeToan::class, 'destroyPhieuThuChi'])->name('tai-chinh.destroy-phieu-thu-chi');
    });
});