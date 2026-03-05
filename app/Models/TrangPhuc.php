<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrangPhuc extends Model
{
    use HasFactory;

    protected $table = 'trang_phuc';

    /** Trạng thái: đang dùng / có sẵn */
    public const TRANG_THAI_ACTIVE = 'active';

    /** Trạng thái: ngừng / không dùng */
    public const TRANG_THAI_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ten_san_pham',
        'ma_san_pham',
        'slug',
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
        ];
    }
}
