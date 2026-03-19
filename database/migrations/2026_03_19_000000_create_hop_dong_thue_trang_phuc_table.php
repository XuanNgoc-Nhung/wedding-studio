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
        Schema::create('hop_dong_thue_trang_phuc', function (Blueprint $table) {
            $table->id();

            $table->string('ten_khach_hang');
            $table->string('sdt_khach_hang', 20);

            $table->foreignId('san_pham_id')
                ->constrained('trang_phuc')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('so_luong')->default(1);
            $table->decimal('gia_thue', 15, 2)->default(0);

            $table->date('ngay_thue');
            $table->date('ngay_tra_du_kien')->nullable();
            $table->date('ngay_tra_thuc_te')->nullable();

            $table->string('trang_thai', 50)->default('moi');
            $table->text('ghi_chu')->nullable();

            $table->foreignId('nguoi_cho_thue')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['sdt_khach_hang']);
            $table->index(['san_pham_id', 'trang_thai']);
            $table->index(['nguoi_cho_thue', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hop_dong_thue_trang_phuc');
    }
};

