<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class DichVuLe extends Model
{
    use HasFactory;

    protected $table = 'dich_vu_le';

    /** Trạng thái: ẩn */
    public const TRANG_THAI_AN = 0;

    /** Trạng thái: hiển thị */
    public const TRANG_THAI_HIEN_THI = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ten_dich_vu',
        'ma_dich_vu',
        'slug',
        'mo_ta',
        'trang_thai',
        'ghi_chu',
        'gia_dich_vu',
        'nguoi_tao_id',
        'phong_ban_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gia_dich_vu' => 'decimal:2',
            'trang_thai' => 'integer',
        ];
    }

    /**
     * Boot: tự tạo slug từ tên dịch vụ nếu chưa có.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (DichVuLe $model) {
            if (empty($model->slug) && ! empty($model->ten_dich_vu)) {
                $model->slug = Str::slug($model->ten_dich_vu);
            }
        });

        static::updating(function (DichVuLe $model) {
            if ($model->isDirty('ten_dich_vu') && empty($model->slug)) {
                $model->slug = Str::slug($model->ten_dich_vu);
            }
        });
    }

    /**
     * Người tạo (user).
     */
    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id');
    }

    /**
     * Phòng ban phụ trách dịch vụ này (1 dịch vụ thuộc 1 phòng ban).
     */
    public function phongBan(): BelongsTo
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    /**
     * Các hợp đồng có chọn dịch vụ lẻ này (many-to-many).
     */
    public function hopDong(): BelongsToMany
    {
        return $this->belongsToMany(
            HopDong::class,
            'hop_dong_dich_vu_le',
            'dich_vu_le_id',
            'hop_dong_id'
        )
            ->using(HopDongDichVuLe::class)
            ->withPivot('gia_goc', 'gia_thuc')
            ->withTimestamps();
    }

    /**
     * Các nhóm dịch vụ chứa dịch vụ lẻ này (many-to-many).
     */
    public function nhomDichVu(): BelongsToMany
    {
        return $this->belongsToMany(
            NhomDichVu::class,
            'dich_vu_le_nhom_dich_vu',
            'dich_vu_le_id',
            'nhom_dich_vu_id'
        )
            ->using(DichVuLeNhomDichVu::class)
            ->withPivot('so_luong')
            ->withTimestamps();
    }
}
