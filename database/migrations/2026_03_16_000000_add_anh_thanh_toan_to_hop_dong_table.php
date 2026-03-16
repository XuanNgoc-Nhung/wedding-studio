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
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->string('anh_thanh_toan_1')->nullable()->after('thanh_toan_lan_1');
            $table->string('anh_thanh_toan_2')->nullable()->after('thanh_toan_lan_2');
            $table->string('anh_thanh_toan_3')->nullable()->after('thanh_toan_lan_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->dropColumn([
                'anh_thanh_toan_1',
                'anh_thanh_toan_2',
                'anh_thanh_toan_3',
            ]);
        });
    }
};

