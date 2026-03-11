<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_kegiatan_id')->constrained('jenis_kegiatan');
            $table->string('nama_kegiatan');
            $table->date('tanggal');
            $table->foreignId('shift_id')->constrained('shifts');
            $table->foreignId('lokasi_id')->constrained('lokasi');
            $table->string('satuan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
