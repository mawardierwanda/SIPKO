<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
     { Schema::table('users', function (Blueprint $t)
      { $t->string('username',100)->unique()->nullable()->after('name');
       $t->enum('role',['admin','petugas'])->default('petugas')->after('email'); $t->boolean('is_active')->default(true)->after('role'); }); }
    public function down(): void { Schema::table('users', function (Blueprint $t) { $t->dropColumn(['username','role','is_active']); }); }
};
