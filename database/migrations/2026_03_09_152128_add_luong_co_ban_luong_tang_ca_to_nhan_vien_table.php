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
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->unsignedBigInteger('luong_co_ban')->default(50000)->after('ngay_ky_hop_dong');
            $table->unsignedBigInteger('luong_tang_ca')->default(80000)->after('luong_co_ban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->dropColumn(['luong_co_ban', 'luong_tang_ca']);
        });
    }
};
