<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'khach_hang';

    /** Giới tính: nam */
    public const GIOI_TINH_NAM = 'nam';

    /** Giới tính: nữ */
    public const GIOI_TINH_NU = 'nu';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ho_ten_chu_re',
        'ngay_sinh_chu_re',
        'gioi_tinh_chu_re',
        'email_hoac_sdt_chu_re',
        'dia_chi_chu_re',
        'ho_ten_co_dau',
        'ngay_sinh_co_dau',
        'gioi_tinh_co_dau',
        'dia_chi_co_dau',
        'email_hoac_sdt_co_dau',
        'ghi_chu',
        'nguon_khach',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ngay_sinh_chu_re' => 'date',
            'ngay_sinh_co_dau' => 'date',
        ];
    }
}
