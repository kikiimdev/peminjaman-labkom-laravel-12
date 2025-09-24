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
        Schema::create('riwayat_status_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->string('dari')->nullable();
            $table->string('menjadi');
            $table->foreignId('aktor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->index(['jadwal_id', 'menjadi', 'aktor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_jadwals');
    }
};
