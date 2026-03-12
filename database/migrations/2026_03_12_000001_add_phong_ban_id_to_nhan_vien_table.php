<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bổ sung cột phong_ban_id để liên kết nhân viên với phòng ban.
     */
    public function up(): void
    {
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->foreignId('phong_ban_id')->nullable()->after('user_id')->constrained('phong_ban')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->dropForeign(['phong_ban_id']);
        });
    }
};
