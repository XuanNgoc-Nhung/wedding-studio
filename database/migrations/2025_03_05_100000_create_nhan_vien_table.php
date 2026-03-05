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
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->string('hinh_anh')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gioi_tinh', 10)->nullable(); // nam, nu, khac
            $table->date('ngay_sinh')->nullable();
            $table->string('cccd', 20)->nullable();
            $table->string('vi_tri_lam_viec')->nullable();
            $table->date('ngay_vao_cong_ty')->nullable();
            $table->date('ngay_ky_hop_dong')->nullable();
            $table->json('ds_menu')->nullable(); // danh sách menu quyền truy cập
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_vien');
    }
};
