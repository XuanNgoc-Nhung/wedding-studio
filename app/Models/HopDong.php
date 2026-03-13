<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HopDong extends Model
{
    use HasFactory;

    protected $table = 'hop_dong';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nguoi_tao_id',
        'khach_hang_id',
        'tho_chup_id',
        'tho_make_id',
        'tho_edit_id',
        'dia_diem',
        'ngay_chup',
        'trang_phuc',
        'concept',
        'ghi_chu_chup',
        'trang_thai_chup',
        'tong_tien',
        'thanh_toan_lan_1',
        'thanh_toan_lan_2',
        'thanh_toan_lan_3',
        'trang_thai_hop_dong',
        'trang_thai_edit',
        'link_file_demo',
        'link_file_in',
        'ngay_tra_link_in',
        'ngay_hen_tra_hang',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ngay_chup' => 'date',
            'ngay_tra_link_in' => 'date',
            'ngay_hen_tra_hang' => 'date',
            'tong_tien' => 'decimal:2',
            'thanh_toan_lan_1' => 'decimal:2',
            'thanh_toan_lan_2' => 'decimal:2',
            'thanh_toan_lan_3' => 'decimal:2',
        ];
    }

    /** Người tạo hợp đồng (user đăng nhập). */
    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id', 'id');
    }

    /** Khách hàng. */
    public function khachHang(): BelongsTo
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id', 'id');
    }

    /** Thợ chụp (nhân viên). */
    public function thoChup(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'tho_chup_id', 'id');
    }

    /** Thợ make (nhân viên). */
    public function thoMake(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'tho_make_id', 'id');
    }

    /** Thợ edit (nhân viên). */
    public function thoEdit(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'tho_edit_id', 'id');
    }
}
