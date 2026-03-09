<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhan_vien';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hinh_anh',
        'user_id',
        'gioi_tinh',
        'ngay_sinh',
        'cccd',
        'vi_tri_lam_viec',
        'ngay_vao_cong_ty',
        'ngay_ky_hop_dong',
        'luong_co_ban',
        'luong_tang_ca',
        'ds_menu',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ngay_sinh' => 'date',
            'ngay_vao_cong_ty' => 'date',
            'ngay_ky_hop_dong' => 'date',
            'luong_co_ban' => 'integer',
            'luong_tang_ca' => 'integer',
            'ds_menu' => 'array',
        ];
    }

    /**
     * Liên kết với bảng users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
