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
        Schema::dropIfExists('lampiran_jadwals');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('lampiran_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained()->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe');
            $table->string('mime_type');
            $table->integer('ukuran');
            $table->timestamps();
        });
    }
};
