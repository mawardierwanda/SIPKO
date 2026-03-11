<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->unique()->constrained('jadwal')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('petugas')->cascadeOnDelete();
            $table->text('isi_laporan');
            $table->enum('kondisi', ['kondusif', 'tidak kondusif', 'perlu tindak lanjut']);
            $table->unsignedInteger('jumlah_pelanggaran')->default(0);
            $table->json('foto')->nullable();
            $table->dateTime('waktu_laporan');
            $table->enum('status', ['diterima', 'terlambat', 'review'])->default('diterima');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
