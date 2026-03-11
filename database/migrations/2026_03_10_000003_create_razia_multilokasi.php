<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel titik razia — lokasi-lokasi dalam satu jadwal razia
        Schema::create('titik_razia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->nullOnDelete();
            $table->string('nama_titik', 100);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->string('status', 20)->default('belum'); // belum|selesai
            $table->timestamps();
        });

        // Tabel checkin petugas di titik razia
        Schema::create('checkin_razia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titik_razia_id')->constrained('titik_razia')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('petugas')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('waktu_checkin')->useCurrent();
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('checkin_razia');
        Schema::dropIfExists('titik_razia');
    }
};
