<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrangPhuc extends Model
{
    use HasFactory;

    protected $table = 'trang_phuc';

    /** Trạng thái: hiển thị */
    public const TRANG_THAI_ACTIVE = 1;

    /** Trạng thái: ẩn */
    public const TRANG_THAI_INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ten_san_pham',
        'ma_san_pham',
        'slug',
        'hinh_anh',
        'mo_ta',
        'ghi_chu',
        'trang_thai',
        'gia_tri',
        'nha_cung_cap',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gia_tri' => 'decimal:2',
            'trang_thai' => 'integer',
        ];
    }

    /**
     * Thông tin kho hàng của sản phẩm (1-1).
     */
    public function khoHang(): HasOne
    {
        return $this->hasOne(KhoHang::class, 'trang_phuc_id', 'id');
    }
}
