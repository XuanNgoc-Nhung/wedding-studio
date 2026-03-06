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
        Schema::create('hop_dong', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khach_hang');
            $table->string('so_dien_thoai', 20)->nullable();
            $table->unsignedBigInteger('trang_phuc_id');
            $table->unsignedInteger('so_luong_thue')->default(1);
            $table->decimal('gia_thue', 15, 2)->default(0);
            $table->dateTime('thoi_gian_thue_bat_dau');
            $table->dateTime('thoi_gian_du_kien_tra');
            $table->dateTime('thoi_gian_tra_hang_thuc_te')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->tinyInteger('trang_thai')->default(0); // 0 chờ xử lý, 1 đang diễn ra, 2 hoàn thành
            $table->unsignedBigInteger('user_id')->nullable(); // người cho thuê (đăng nhập)
            $table->timestamps();

            $table->foreign('trang_phuc_id')->references('id')->on('trang_phuc')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hop_dong');
    }
};
