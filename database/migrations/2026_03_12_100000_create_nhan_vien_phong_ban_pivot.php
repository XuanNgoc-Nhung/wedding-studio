<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng pivot: một nhân viên có thể trực thuộc nhiều phòng ban.
     */
    public function up(): void
    {
        Schema::create('nhan_vien_phong_ban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nhan_vien_id')->constrained('nhan_vien')->cascadeOnDelete();
            $table->foreignId('phong_ban_id')->constrained('phong_ban')->cascadeOnDelete();
            $table->unique(['nhan_vien_id', 'phong_ban_id']);
            $table->timestamps();
        });

        // Chuyển dữ liệu từ cột phong_ban_id cũ sang bảng pivot
        $rows = DB::table('nhan_vien')->whereNotNull('phong_ban_id')->get(['id', 'phong_ban_id']);
        foreach ($rows as $row) {
            DB::table('nhan_vien_phong_ban')->insert([
                'nhan_vien_id' => $row->id,
                'phong_ban_id' => $row->phong_ban_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->dropForeign(['phong_ban_id']);
            $table->dropColumn('phong_ban_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->foreignId('phong_ban_id')->nullable()->after('user_id')->constrained('phong_ban')->nullOnDelete();
        });

        $pivot = DB::table('nhan_vien_phong_ban')->get();
        foreach ($pivot as $row) {
            DB::table('nhan_vien')->where('id', $row->nhan_vien_id)->update(['phong_ban_id' => $row->phong_ban_id]);
        }

        Schema::dropIfExists('nhan_vien_phong_ban');
    }
};
