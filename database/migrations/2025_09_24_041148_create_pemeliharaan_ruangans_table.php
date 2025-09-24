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
        Schema::create('pemeliharaan_ruangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('status');
            $table->dateTime('dijadwalkan_pada')->nullable();
            $table->dateTime('selesai_pada')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->timestamps();
            $table->index(['ruangan_id', 'status', 'dijadwalkan_pada']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan_ruangans');
    }
};
