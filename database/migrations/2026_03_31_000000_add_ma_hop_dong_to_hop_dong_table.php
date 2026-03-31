<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->string('ma_hop_dong')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong', function (Blueprint $table) {
            $table->dropUnique(['ma_hop_dong']);
            $table->dropColumn('ma_hop_dong');
        });
    }
};

