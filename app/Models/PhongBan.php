<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhongBan extends Model
{
    use HasFactory;

    protected $table = 'phong_ban';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ten_phong_ban',
        'ma_phong_ban',
        'mo_ta',
        'ghi_chu',
    ];

    /**
     * Danh sách nhân viên thuộc phòng ban.
     */
    public function nhanVien(): HasMany
    {
        return $this->hasMany(NhanVien::class);
    }
}

