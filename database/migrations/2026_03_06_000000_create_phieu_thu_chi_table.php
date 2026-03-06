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
        Schema::create('phieu_thu_chi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_tao_id')->comment('Người tạo phiếu');
            $table->unsignedBigInteger('nguoi_duyet_id')->nullable()->comment('Người duyệt phiếu');
            $table->tinyInteger('loai_phieu')->comment('1: thu, 2: chi');
            $table->decimal('so_tien', 15, 2)->default(0);
            $table->string('ly_do');
            $table->tinyInteger('trang_thai')->default(0)->comment('0: chờ xử lý, 1: đồng ý, -1: từ chối, 2: hoàn thành');
            $table->text('ghi_chu')->nullable();
            $table->dateTime('ngay_duyet')->nullable();
            $table->timestamps();

            $table->foreign('nguoi_tao_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('nguoi_duyet_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phieu_thu_chi');
    }
};
