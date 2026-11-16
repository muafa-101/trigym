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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('Aktif')->after('password');
            $table->string('photo')->default('assets/1.jpeg')->after('status');
            $table->foreignId('package_id')->nullable()->after('photo')->constrained('packages')->nullOnDelete();
            $table->date('expires_at')->nullable()->after('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['status', 'photo', 'package_id', 'expires_at']);
        });
    }
};
