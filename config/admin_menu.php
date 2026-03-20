<?php

/**
 * Cấu trúc menu sidebar admin.
 * Mỗi tài khoản (nhân viên) chỉ thấy các mục có route nằm trong nhan_vien.ds_menu.
 * Admin thấy toàn bộ.
 */
return [
    [
        'stt' => 1,
        'type' => 'single',
        'route' => 'admin.index',
        'label' => 'Tổng quan',
        'icon' => 'ti tabler-smart-home',
    ],
    [
        'stt' => 2,
        'type' => 'group',
        'routes' => ['admin.diem-danh.diem-danh', 'admin.diem-danh.cham-cong', 'admin.diem-danh.dieu-phoi-cong-viec'],
        'route_prefix' => 'admin.diem-danh.',
        'label' => 'Chấm công',
        'icon' => 'ti tabler-clock',
        'children' => [
            ['stt' => 1, 'route' => 'admin.diem-danh.diem-danh', 'label' => 'Điểm danh'],
            ['stt' => 2, 'route' => 'admin.diem-danh.cham-cong', 'label' => 'Chấm công'],
            ['stt' => 3, 'route' => 'admin.diem-danh.dieu-phoi-cong-viec', 'label' => 'Điều phối công việc'],
        ],
    ],
    [
        'stt' => 3,
        'type' => 'group',
        'routes' => ['admin.concept.concept'],
        'route_prefix' => 'admin.concept.concept',
        'label' => 'Concept',
        'icon' => 'ti tabler-tag',
        'children' => [
            ['stt' => 1, 'route' => 'admin.concept.concept', 'label' => 'Concept'],
        ],
    ],
    [
        'stt' => 4,
        'type' => 'group',
        'routes' => ['admin.nhan-su.danh-sach', 'admin.nhan-su.phan-quyen', 'admin.nhan-su.lich-lam-viec'],
        'route_prefix' => 'admin.nhan-su.',
        'label' => 'Nhân sự',
        'icon' => 'ti tabler-users',
        'children' => [
            ['stt' => 1, 'route' => 'admin.nhan-su.danh-sach', 'label' => 'Danh sách nhân sự'],
            ['stt' => 2, 'route' => 'admin.nhan-su.phan-quyen', 'label' => 'Phân quyền'],
            ['stt' => 3, 'route' => 'admin.nhan-su.lich-lam-viec', 'label' => 'Lịch làm việc'],
        ],
    ],
    [
        'stt' => 5,
        'type' => 'group',
        'routes' => ['admin.khach-hang.danh-sach', 'admin.khach-hang.hop-dong'],
        'route_prefix' => 'admin.khach-hang.',
        'label' => 'Khách hàng',
        'icon' => 'ti tabler-user-circle',
        'children' => [
            ['stt' => 1, 'route' => 'admin.khach-hang.danh-sach', 'label' => 'Danh sách khách hàng'],
            ['stt' => 2, 'route' => 'admin.khach-hang.hop-dong', 'label' => 'Hợp đồng'],
        ],
    ],
    [
        'stt' => 6,
        'type' => 'group',
        'routes' => ['admin.tai-chinh.cong-no', 'admin.tai-chinh.phieu-thu-chi', 'admin.tai-chinh.tinh-luong'],
        'route_prefix' => 'admin.tai-chinh.',
        'label' => 'Tài chính kế toán',
        'icon' => 'ti tabler-cash',
        'children' => [
            ['stt' => 1, 'route' => 'admin.tai-chinh.cong-no', 'label' => 'Công nợ'],
            ['stt' => 2, 'route' => 'admin.tai-chinh.phieu-thu-chi', 'label' => 'Phiếu thu chi'],
            ['stt' => 3, 'route' => 'admin.tai-chinh.tinh-luong', 'label' => 'Tính lương'],
        ],
    ],
    [
        'stt' => 7,
        'type' => 'group',
        'routes' => ['admin.trang-phuc.san-pham', 'admin.trang-phuc.kho-hang', 'admin.trang-phuc.hop-dong'],
        'route_prefix' => 'admin.trang-phuc.',
        'label' => 'Trang phục',
        'icon' => 'ti tabler-shirt',
        'children' => [
            ['stt' => 1, 'route' => 'admin.trang-phuc.san-pham', 'label' => 'Sản phẩm'],
            ['stt' => 2, 'route' => 'admin.trang-phuc.kho-hang', 'label' => 'Kho hàng'],
            ['stt' => 3, 'route' => 'admin.trang-phuc.hop-dong', 'label' => 'Hợp đồng'],
        ],
    ],
    [
        'stt' => 8,
        'type' => 'group',
        'routes' => ['admin.dich-vu.dich-vu-le', 'admin.dich-vu.nhom-dich-vu'],
        'route_prefix' => 'admin.dich-vu.',
        'label' => 'Dịch vụ',
        'icon' => 'ti tabler-briefcase',
        'children' => [
            ['stt' => 1, 'route' => 'admin.dich-vu.dich-vu-le', 'label' => 'Dịch vụ lẻ'],
            ['stt' => 2, 'route' => 'admin.dich-vu.nhom-dich-vu', 'label' => 'Nhóm dịch vụ'],
        ],
    ],
    [
        'stt' => 9,
        'type' => 'single',
        'route' => 'admin.thong-tin-ca-nhan',
        'label' => 'Thông tin cá nhân',
        'icon' => 'ti tabler-user-circle',
    ],
    [
        'stt' => 10,
        'type' => 'group',
        'routes' => ['admin.he-thong.phong-ban'],
        'route_prefix' => 'admin.he-thong.',
        'label' => 'Hệ thống',
        'icon' => 'ti tabler-building',
        'children' => [
            ['stt' => 1, 'route' => 'admin.he-thong.phong-ban', 'label' => 'Phòng ban'],
        ],  
    ],
];
