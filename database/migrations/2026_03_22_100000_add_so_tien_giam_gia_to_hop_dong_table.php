<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->decimal('so_tien_giam_gia', 14, 2)->nullable()->after('nguoi_gioi_thieu');
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->dropColumn('so_tien_giam_gia');
        });
    }
};
