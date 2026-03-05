<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bổ sung cột giá gốc và cột thẻ (phục vụ tìm kiếm) cho bảng nhóm dịch vụ.
     */
    public function up(): void
    {
        Schema::table('nhom_dich_vu', function (Blueprint $table) {
            $table->decimal('gia_goc', 15, 2)->default(0)->after('gia_tien')->comment('Giá gốc');
            $table->text('the')->nullable()->after('gia_goc')->comment('Thẻ/từ khóa phục vụ tìm kiếm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhom_dich_vu', function (Blueprint $table) {
            $table->dropColumn(['gia_goc', 'the']);
        });
    }
};
