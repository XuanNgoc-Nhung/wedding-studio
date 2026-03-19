<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `hop_dong` MODIFY `ngay_chup` DATETIME NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE hop_dong ALTER COLUMN ngay_chup TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
            DB::statement('ALTER TABLE hop_dong ALTER COLUMN ngay_chup DROP NOT NULL');
            return;
        }

        if ($driver === 'sqlite') {
            // SQLite doesn't support altering column types easily; leave as-is.
            // For SQLite dev, a fresh migrate will create correct type if base migration is adjusted.
            return;
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `hop_dong` MODIFY `ngay_chup` DATE NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE hop_dong ALTER COLUMN ngay_chup TYPE DATE');
            DB::statement('ALTER TABLE hop_dong ALTER COLUMN ngay_chup DROP NOT NULL');
            return;
        }
    }
};

