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
        Schema::create('concept', function (Blueprint $table) {
            $table->id();
            $table->string('ten_concept');
            $table->string('hinh_anh')->nullable();
            $table->unsignedTinyInteger('trang_thai')->default(1); // 0=ngưng hoạt động, 1=đang hoạt động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept');
    }
};

