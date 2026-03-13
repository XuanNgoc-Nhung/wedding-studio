<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng hợp đồng dịch vụ chụp ảnh (thay thế cấu trúc cũ hop_dong thuê trang phục).
     */
    public function up(): void
    {
        Schema::dropIfExists('hop_dong');

        Schema::create('hop_dong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_tao_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('khach_hang_id')->constrained('khach_hang')->cascadeOnDelete();
            $table->foreignId('tho_chup_id')->nullable()->constrained('nhan_vien')->nullOnDelete();
            $table->foreignId('tho_make_id')->nullable()->constrained('nhan_vien')->nullOnDelete();
            $table->foreignId('tho_edit_id')->nullable()->constrained('nhan_vien')->nullOnDelete();
            $table->string('dia_diem')->nullable();
            $table->date('ngay_chup')->nullable();
            $table->text('trang_phuc')->nullable();
            $table->text('concept')->nullable();
            $table->text('ghi_chu_chup')->nullable();
            $table->string('trang_thai_chup', 50)->nullable();
            $table->decimal('tong_tien', 15, 2)->default(0);
            $table->decimal('thanh_toan_lan_1', 15, 2)->nullable();
            $table->decimal('thanh_toan_lan_2', 15, 2)->nullable();
            $table->decimal('thanh_toan_lan_3', 15, 2)->nullable();
            $table->string('trang_thai_hop_dong', 50)->nullable();
            $table->string('trang_thai_edit', 50)->nullable();
            $table->string('link_file_demo')->nullable();
            $table->string('link_file_in')->nullable();
            $table->date('ngay_tra_link_in')->nullable();
            $table->timestamps();
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
