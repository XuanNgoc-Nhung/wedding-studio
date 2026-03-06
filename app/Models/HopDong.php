<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HopDong extends Model
{
    use HasFactory;

    protected $table = 'hop_dong';

    /** Trạng thái: chờ xử lý */
    public const TRANG_THAI_CHO_XU_LY = 0;

    /** Trạng thái: đang diễn ra */
    public const TRANG_THAI_DANG_DIEN_RA = 1;

    /** Trạng thái: hoàn thành */
    public const TRANG_THAI_HOAN_THANH = 2;

    protected $fillable = [
        'ten_khach_hang',
        'so_dien_thoai',
        'trang_phuc_id',
        'so_luong_thue',
        'gia_thue',
        'thoi_gian_thue_bat_dau',
        'thoi_gian_du_kien_tra',
        'thoi_gian_tra_hang_thuc_te',
        'ghi_chu',
        'trang_thai',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'thoi_gian_thue_bat_dau' => 'datetime',
            'thoi_gian_du_kien_tra' => 'datetime',
            'thoi_gian_tra_hang_thuc_te' => 'datetime',
            'gia_thue' => 'decimal:2',
            'so_luong_thue' => 'integer',
            'trang_thai' => 'integer',
        ];
    }

    public function trangPhuc(): BelongsTo
    {
        return $this->belongsTo(TrangPhuc::class, 'trang_phuc_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
