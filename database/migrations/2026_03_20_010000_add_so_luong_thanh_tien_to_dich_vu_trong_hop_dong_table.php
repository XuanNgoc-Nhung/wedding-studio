<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bổ sung cột số lượng và thành tiền cho bảng dịch vụ trong hợp đồng.
     *
     * Lưu ý: các hợp đồng cũ chưa có so_luong/thanh_tien thì sẽ backfill
     * - so_luong = 1
     * - thanh_tien = gia_thuc (giá thực hiện đang được lưu)
     */
    public function up(): void
    {
        Schema::table('dich_vu_trong_hop_dong', function (Blueprint $table) {
            $table->unsignedInteger('so_luong')->default(1)->after('id_dich_vu');
            $table->decimal('thanh_tien', 15, 2)->default(0)->after('gia_thuc');
        });

        // Backfill dữ liệu cũ để tránh hiển thị/ tính toán sai khi sửa hợp đồng.
        DB::table('dich_vu_trong_hop_dong')
            ->where('thanh_tien', 0)
            ->update([
                'so_luong' => 1,
                'thanh_tien' => DB::raw('gia_thuc'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dich_vu_trong_hop_dong', function (Blueprint $table) {
            $table->dropColumn('thanh_tien');
            $table->dropColumn('so_luong');
        });
    }
};

