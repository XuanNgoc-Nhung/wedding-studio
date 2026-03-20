<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;

    protected $table = 'concept';

    /** Trạng thái: đang hoạt động */
    public const TRANG_THAI_ACTIVE = 1;

    /** Trạng thái: ngưng hoạt động */
    public const TRANG_THAI_INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ten_concept',
        'hinh_anh',
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
            'trang_thai' => 'integer',
        ];
    }
}

