<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhoHang extends Model
{
    use HasFactory;

    protected $table = 'kho_hang';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trang_phuc_id',
        'so_luong',
        'ghi_chu',
        'gia_cho_thue',
        'trang_thai',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'so_luong' => 'integer',
            'gia_cho_thue' => 'decimal:2',
            'trang_thai' => 'integer',
        ];
    }

    /**
     * Sản phẩm tương ứng.
     */
    public function trangPhuc(): BelongsTo
    {
        return $this->belongsTo(TrangPhuc::class, 'trang_phuc_id', 'id');
    }
}

