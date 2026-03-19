<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HopDongThueTrangPhuc extends Model
{
    use HasFactory;

    protected $table = 'hop_dong_thue_trang_phuc';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ten_khach_hang',
        'sdt_khach_hang',
        'san_pham_id',
        'so_luong',
        'gia_thue',
        'ngay_thue',
        'ngay_tra_du_kien',
        'ngay_tra_thuc_te',
        'trang_thai',
        'ghi_chu',
        'nguoi_cho_thue',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'san_pham_id' => 'integer',
            'so_luong' => 'integer',
            'gia_thue' => 'decimal:2',
            'ngay_thue' => 'date',
            'ngay_tra_du_kien' => 'date',
            'ngay_tra_thuc_te' => 'date',
        ];
    }

    public function sanPham(): BelongsTo
    {
        return $this->belongsTo(TrangPhuc::class, 'san_pham_id', 'id');
    }

    public function nguoiChoThue(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_cho_thue', 'id');
    }
}

