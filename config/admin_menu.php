<?php

/**
 * Cấu trúc menu sidebar admin.
 * Mỗi tài khoản (nhân viên) chỉ thấy các mục có route nằm trong nhan_vien.ds_menu.
 * Admin thấy toàn bộ.
 */
return [
    [
        'type' => 'single',
        'route' => 'admin.index',
        'label' => 'Tổng quan',
        'icon' => 'ti tabler-smart-home',
    ],
    [
        'type' => 'group',
        'routes' => ['admin.diem-danh.diem-danh', 'admin.diem-danh.cham-cong'],
        'route_prefix' => 'admin.diem-danh.',
        'label' => 'Chấm công',
        'icon' => 'ti tabler-clock',
        'children' => [
            ['route' => 'admin.diem-danh.diem-danh', 'label' => 'Điểm danh'],
            ['route' => 'admin.diem-danh.cham-cong', 'label' => 'Chấm công'],
        ],
    ],
    [
        'type' => 'group',
        'routes' => ['admin.nhan-su.danh-sach', 'admin.nhan-su.phan-quyen', 'admin.nhan-su.lich-lam-viec'],
        'route_prefix' => 'admin.nhan-su.',
        'label' => 'Nhân sự',
        'icon' => 'ti tabler-users',
        'children' => [
            ['route' => 'admin.nhan-su.danh-sach', 'label' => 'Danh sách nhân sự'],
            ['route' => 'admin.nhan-su.phan-quyen', 'label' => 'Phân quyền'],
            ['route' => 'admin.nhan-su.lich-lam-viec', 'label' => 'Lịch làm việc'],
        ],
    ],
    [
        'type' => 'group',
        'routes' => ['admin.khach-hang.danh-sach', 'admin.khach-hang.hop-dong'],
        'route_prefix' => 'admin.khach-hang.',
        'label' => 'Khách hàng',
        'icon' => 'ti tabler-user-circle',
        'children' => [
            ['route' => 'admin.khach-hang.danh-sach', 'label' => 'Danh sách khách hàng'],
            ['route' => 'admin.khach-hang.hop-dong', 'label' => 'Hợp đồng'],
        ],
    ],
    [
        'type' => 'group',
        'routes' => ['admin.tai-chinh.cong-no', 'admin.tai-chinh.phieu-thu-chi', 'admin.tai-chinh.tinh-luong'],
        'route_prefix' => 'admin.tai-chinh.',
        'label' => 'Tài chính kế toán',
        'icon' => 'ti tabler-cash',
        'children' => [
            ['route' => 'admin.tai-chinh.cong-no', 'label' => 'Công nợ'],
            ['route' => 'admin.tai-chinh.phieu-thu-chi', 'label' => 'Phiếu thu chi'],
            ['route' => 'admin.tai-chinh.tinh-luong', 'label' => 'Tính lương'],
        ],
    ],
    [
        'type' => 'group',
        'routes' => ['admin.trang-phuc.san-pham', 'admin.trang-phuc.kho-hang', 'admin.trang-phuc.hop-dong'],
        'route_prefix' => 'admin.trang-phuc.',
        'label' => 'Trang phục',
        'icon' => 'ti tabler-shirt',
        'children' => [
            ['route' => 'admin.trang-phuc.san-pham', 'label' => 'Sản phẩm'],
            ['route' => 'admin.trang-phuc.kho-hang', 'label' => 'Kho hàng'],
            ['route' => 'admin.trang-phuc.hop-dong', 'label' => 'Hợp đồng'],
        ],
    ],
    [
        'type' => 'group',
        'routes' => ['admin.dich-vu.dich-vu-le', 'admin.dich-vu.nhom-dich-vu'],
        'route_prefix' => 'admin.dich-vu.',
        'label' => 'Dịch vụ',
        'icon' => 'ti tabler-briefcase',
        'children' => [
            ['route' => 'admin.dich-vu.dich-vu-le', 'label' => 'Dịch vụ lẻ'],
            ['route' => 'admin.dich-vu.nhom-dich-vu', 'label' => 'Nhóm dịch vụ'],
        ],
    ],
    [
        'type' => 'single',
        'route' => 'admin.thong-tin-ca-nhan',
        'label' => 'Thông tin cá nhân',
        'icon' => 'ti tabler-user-circle',
    ],
    [
        'type' => 'group',
        'route' => 'admin.he-thong.phong-ban',
        'label' => 'Hệ thống',
        'icon' => 'ti tabler-building',
        'children' => [
            ['route' => 'admin.he-thong.phong-ban', 'label' => 'Phòng ban'],
        ],  
    ],
];
