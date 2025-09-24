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
        Schema::table('tanggal_jadwals', function (Blueprint $table) {
            $table->string('jam_mulai')->nullable()->change();
            $table->string('jam_berakhir')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanggal_jadwals', function (Blueprint $table) {
            $table->string('jam_mulai')->nullable(false)->change();
            $table->string('jam_berakhir')->nullable(false)->change();
        });
    }
};
