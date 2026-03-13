<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bổ sung cột phong_ban_id: dịch vụ lẻ do phòng ban nào phụ trách (1 dịch vụ thuộc 1 phòng ban).
     */
    public function up(): void
    {
        Schema::table('dich_vu_le', function (Blueprint $table) {
            $table->foreignId('phong_ban_id')->nullable()->after('nguoi_tao_id')->constrained('phong_ban')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dich_vu_le', function (Blueprint $table) {
            $table->dropForeign(['phong_ban_id']);
        });
    }
};
