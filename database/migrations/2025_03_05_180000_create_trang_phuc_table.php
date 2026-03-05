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
        Schema::create('trang_phuc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_san_pham');
            $table->string('ma_san_pham')->unique();
            $table->string('slug')->unique();
            $table->text('mo_ta')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->string('trang_thai', 50)->default('active');
            $table->decimal('gia_tri', 15, 2)->default(0);
            $table->string('nha_cung_cap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trang_phuc');
    }
};
