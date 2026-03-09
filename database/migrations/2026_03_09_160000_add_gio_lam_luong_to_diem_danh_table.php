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
        Schema::table('diem_danh', function (Blueprint $table) {
            $table->decimal('gio_lam_co_ban', 8, 2)->default(0)->after('ghi_chu');   // giờ làm cơ bản
            $table->decimal('gio_lam_tang_ca', 8, 2)->default(0)->after('gio_lam_co_ban'); // giờ làm tăng ca
            $table->decimal('luong_co_ban', 15, 2)->default(0)->after('gio_lam_tang_ca');  // lương cơ bản
            $table->decimal('luong_tang_ca', 15, 2)->default(0)->after('luong_co_ban');     // lương tăng ca
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diem_danh', function (Blueprint $table) {
            $table->dropColumn([
                'gio_lam_co_ban',
                'gio_lam_tang_ca',
                'luong_co_ban',
                'luong_tang_ca',
            ]);
        });
    }
};
