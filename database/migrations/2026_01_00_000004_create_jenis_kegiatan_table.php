<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_kegiatan', function (Blueprint $table) {
            $table->dropUnique('jenis_kegiatan_kode_unique');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_kegiatan', function (Blueprint $table) {
            $table->unique('kode');
        });
    }
};
