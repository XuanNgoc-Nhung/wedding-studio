<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dich_vu_le', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dich_vu');
            $table->string('ma_dich_vu')->unique();
            $table->string('slug')->unique();
            $table->text('mo_ta')->nullable();
            $table->unsignedTinyInteger('trang_thai')->default(1); // 0=ẩn, 1=hiển thị
            $table->text('ghi_chu')->nullable();
            $table->decimal('gia_dich_vu', 15, 2)->default(0);
            $table->foreignId('nguoi_tao_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dich_vu_le');
    }
};
