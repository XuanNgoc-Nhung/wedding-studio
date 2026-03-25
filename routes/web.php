<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController as Admin;
use App\Http\Controllers\Admin\NhanSuController as AdminNhanSu;
use App\Http\Controllers\Admin\DichVuController as AdminDichVu;
use App\Http\Controllers\Admin\KhachHangController as AdminKhachHang;
use App\Http\Controllers\Admin\TrangPhucController as AdminTrangPhuc;
use App\Http\Controllers\Admin\TaiChinhKeToanController as AdminTaiChinhKeToan;
use App\Http\Controllers\Admin\DiemDanhController as AdminDiemDanh;
use App\Http\Controllers\Admin\ConceptController as AdminConcept;
use App\Http\Controllers\Admin\HeThongController as AdminHeThong;

Route::get('/', function () {
    return view('welcome');
});

// Đăng nhập / Đăng xuất / Đăng ký (guest mới vào được login, register; auth mới vào được logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'checkRoute']], function () {
    Route::get('/', [Admin::class, 'index'])->name('index');
    Route::get('/thong-tin-ca-nhan', [Admin::class, 'thongTinCaNhan'])->name('thong-tin-ca-nhan');
    Route::put('/thong-tin-ca-nhan', [Admin::class, 'capNhatThongTinCaNhan'])->name('thong-tin-ca-nhan.update');
    Route::put('/doi-mat-khau', [Admin::class, 'doiMatKhau'])->name('doi-mat-khau');
    Route::group(['prefix' => 'nhan-su'], function () {
        Route::get('/danh-sach', [AdminNhanSu::class, 'danhSach'])->name('nhan-su.danh-sach');
        Route::post('/danh-sach', [AdminNhanSu::class, 'store'])->name('nhan-su.store');
        Route::put('/danh-sach/{user}', [AdminNhanSu::class, 'update'])->name('nhan-su.update');
        Route::put('/doi-mat-khau/{user}', [AdminNhanSu::class, 'doiMatKhau'])->name('nhan-su.doi-mat-khau');
        Route::delete('/danh-sach/{user}', [AdminNhanSu::class, 'destroy'])->name('nhan-su.destroy');
        Route::get('/phan-quyen', [AdminNhanSu::class, 'phanQuyen'])->name('nhan-su.phan-quyen');
        Route::post('/phan-quyen', [AdminNhanSu::class, 'luuPhanQuyen'])->name('nhan-su.luu-phan-quyen');
        Route::get('/lich-lam-viec', [AdminNhanSu::class, 'lichLamViec'])->name('nhan-su.lich-lam-viec');
        Route::get('/lich-lam-viec/data', [AdminNhanSu::class, 'lichLamViecData'])->name('nhan-su.lich-lam-viec.data');
        Route::get('/lich-lam-viec/chi-tiet-ngay', [AdminNhanSu::class, 'lichLamViecChiTietNgay'])->name('nhan-su.lich-lam-viec.chi-tiet-ngay');
    });
    //Route cho khách hàng
    Route::group(['prefix' => 'khach-hang'], function () {
        Route::get('/danh-sach', [AdminKhachHang::class, 'danhSach'])->name('khach-hang.danh-sach');
        Route::post('/danh-sach', [AdminKhachHang::class, 'store'])->name('khach-hang.store');
        Route::put('/danh-sach/{khachHang}', [AdminKhachHang::class, 'update'])->name('khach-hang.update');
        Route::delete('/danh-sach/{khachHang}', [AdminKhachHang::class, 'destroy'])->name('khach-hang.destroy');
        Route::get('/hop-dong', [AdminKhachHang::class, 'hopDong'])->name('khach-hang.hop-dong');
        Route::post('/hop-dong/kiem-tra-ma-gioi-thieu', [AdminKhachHang::class, 'kiemTraMaGioiThieu'])->name('khach-hang.kiem-tra-ma-gioi-thieu');
        Route::post('/hop-dong', [AdminKhachHang::class, 'storeHopDong'])->name('khach-hang.store-hop-dong');
        Route::get('/hop-dong/{hopDong}/dich-vu', [AdminKhachHang::class, 'dichVuTrongHopDong'])->name('khach-hang.hop-dong.dich-vu');
        Route::post('/hop-dong/upload-anh-thanh-toan', [AdminKhachHang::class, 'uploadAnhThanhToan'])->name('khach-hang.hop-dong.upload-anh-thanh-toan');
        Route::put('/hop-dong/{hopDong}', [AdminKhachHang::class, 'updateHopDong'])->name('khach-hang.update-hop-dong');
        Route::delete('/hop-dong/{hopDong}', [AdminKhachHang::class, 'destroyHopDong'])->name('khach-hang.destroy-hop-dong');
    });
    //Route cho trang phục
    Route::group(['prefix' => 'trang-phuc'], function () {
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
        // tính lương
        Route::get('/tinh-luong', [AdminTaiChinhKeToan::class, 'tinhLuong'])->name('tai-chinh.tinh-luong');
        Route::post('/tinh-luong', [AdminTaiChinhKeToan::class, 'storeTinhLuong'])->name('tai-chinh.store-tinh-luong');
        Route::put('/tinh-luong/{tinhLuong}', [AdminTaiChinhKeToan::class, 'updateTinhLuong'])->name('tai-chinh.update-tinh-luong');
        Route::delete('/tinh-luong/{tinhLuong}', [AdminTaiChinhKeToan::class, 'destroyTinhLuong'])->name('tai-chinh.destroy-tinh-luong');
    });
    //Route cho điểm danh
    Route::group(['prefix' => 'diem-danh'], function () {
        Route::get('/', [AdminDiemDanh::class, 'diemDanh'])->name('diem-danh.diem-danh');
        Route::get('/cham-cong', [AdminDiemDanh::class, 'chamCong'])->name('diem-danh.cham-cong');
        Route::get('/check-in', [AdminDiemDanh::class, 'checkIn'])->name('diem-danh.check-in');
        Route::get('/check-out', [AdminDiemDanh::class, 'checkOut'])->name('diem-danh.check-out');
        //Điều phối công việc
        Route::get('/dieu-phoi-cong-viec', [AdminDiemDanh::class, 'dieuPhoiCongViec'])->name('diem-danh.dieu-phoi-cong-viec');
        Route::put('/dieu-phoi-cong-viec/{hopDong}/phan-cong', [AdminDiemDanh::class, 'phanCongCongViec'])->name('diem-danh.phan-cong-cong-viec');
        Route::post('/dieu-phoi-cong-viec', [AdminDiemDanh::class, 'storeDieuPhoiCongViec'])->name('diem-danh.store-dieu-phoi-cong-viec');
        Route::put('/dieu-phoi-cong-viec/{dieuPhoiCongViec}', [AdminDiemDanh::class, 'updateDieuPhoiCongViec'])->name('diem-danh.update-dieu-phoi-cong-viec');
        Route::delete('/dieu-phoi-cong-viec/{dieuPhoiCongViec}', [AdminDiemDanh::class, 'destroyDieuPhoiCongViec'])->name('diem-danh.destroy-dieu-phoi-cong-viec');
    });
    // Route cho Concept (tách khỏi nhóm điểm danh)
    Route::group(['prefix' => 'concept'], function () {
        Route::get('/', [AdminConcept::class, 'concept'])->name('concept.concept');
        Route::post('/', [AdminConcept::class, 'store'])->name('concept.concept.store');
        Route::put('/{concept}', [AdminConcept::class, 'update'])->name('concept.concept.update');
        Route::delete('/{concept}', [AdminConcept::class, 'destroy'])->name('concept.concept.destroy');
    });
    //Route cho hệ thống
    Route::group(['prefix' => 'he-thong'], function () {
        Route::get('/phong-ban', [AdminHeThong::class, 'phongBan'])->name('he-thong.phong-ban');
        Route::post('/phong-ban', [AdminHeThong::class, 'storePhongBan'])->name('he-thong.phong-ban.store');
        Route::put('/phong-ban/{phongBan}', [AdminHeThong::class, 'updatePhongBan'])->name('he-thong.phong-ban.update');
        Route::delete('/phong-ban/{phongBan}', [AdminHeThong::class, 'destroyPhongBan'])->name('he-thong.phong-ban.destroy');
    });
});