<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot: dịch vụ lẻ được chọn trong hợp đồng (chỉ lưu khi user tích checkbox), kèm giá gốc và giá thực.
     */
    public function up(): void
    {
        Schema::create('hop_dong_dich_vu_le', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hop_dong_id')->constrained('hop_dong')->cascadeOnDelete();
            $table->foreignId('dich_vu_le_id')->constrained('dich_vu_le')->cascadeOnDelete();
            $table->decimal('gia_goc', 15, 2)->default(0);
            $table->decimal('gia_thuc', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['hop_dong_id', 'dich_vu_le_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hop_dong_dich_vu_le');
    }
};
