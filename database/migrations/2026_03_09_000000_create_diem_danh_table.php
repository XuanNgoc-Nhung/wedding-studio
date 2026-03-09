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
        Schema::create('diem_danh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('gio_vao')->nullable(); // giờ vào
            $table->dateTime('gio_ra')->nullable(); // giờ ra
            $table->boolean('di_muon')->default(false); // đi muộn: 1 có, 0 không
            $table->boolean('hop_le')->default(false); // đi muộn hợp lệ: 1 có, 0 không
            $table->string('ly_do')->nullable(); // lý do
            $table->boolean('nghi_phep')->default(false); // nghỉ phép: 0 không, 1 có
            $table->string('loai_phep')->nullable(); // loại nghỉ phép: 1 ngày, nửa ngày,...
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diem_danh');
    }
};
