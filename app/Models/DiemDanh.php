<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiemDanh extends Model
{
    use HasFactory;

    protected $table = 'diem_danh';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'gio_vao',
        'gio_ra',
        'di_muon',
        'hop_le',
        'ly_do',
        'nghi_phep',
        'loai_phep',
        'ghi_chu',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gio_vao' => 'datetime',
            'gio_ra' => 'datetime',
            'di_muon' => 'boolean',
            'hop_le' => 'boolean',
            'nghi_phep' => 'boolean',
        ];
    }

    /**
     * Liên kết với bảng users (người điểm danh).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
