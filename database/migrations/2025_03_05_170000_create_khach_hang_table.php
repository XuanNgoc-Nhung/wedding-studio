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
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id();
            // Chú rể
            $table->string('ho_ten_chu_re');
            $table->date('ngay_sinh_chu_re')->nullable();
            $table->string('gioi_tinh_chu_re', 10)->default('nam');
            $table->string('email_hoac_sdt_chu_re')->nullable();
            $table->string('dia_chi_chu_re')->nullable();
            // Cô dâu
            $table->string('ho_ten_co_dau');
            $table->date('ngay_sinh_co_dau')->nullable();
            $table->string('gioi_tinh_co_dau', 10)->default('nu');
            $table->string('dia_chi_co_dau')->nullable();
            $table->string('email_hoac_sdt_co_dau')->nullable();
            // Ghi chú
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};
