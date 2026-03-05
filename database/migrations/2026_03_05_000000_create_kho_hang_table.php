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
        Schema::create('kho_hang', function (Blueprint $table) {
            $table->id();

            // id sản phẩm (trang_phuc.id)
            $table->unsignedBigInteger('trang_phuc_id')->unique();

            $table->integer('so_luong')->default(0);
            $table->text('ghi_chu')->nullable();
            $table->decimal('gia_cho_thue', 15, 2)->default(0);
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();

            $table->foreign('trang_phuc_id')
                ->references('id')
                ->on('trang_phuc')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kho_hang');
    }
};

