<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng dịch vụ trong hợp đồng: liên kết hợp đồng với dịch vụ, lưu giá gốc, giá thực và ghi chú.
     */
    public function up(): void
    {
        Schema::create('dich_vu_trong_hop_dong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_hop_dong')->constrained('hop_dong')->cascadeOnDelete();
            $table->foreignId('id_dich_vu')->constrained('dich_vu_le')->cascadeOnDelete();
            $table->decimal('gia_goc', 15, 2)->default(0);
            $table->decimal('gia_thuc', 15, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->unique(['id_hop_dong', 'id_dich_vu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dich_vu_trong_hop_dong');
    }
};
