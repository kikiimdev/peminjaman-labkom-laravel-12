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
        Schema::table('pemeliharaan_ruangans', function (Blueprint $table) {
            $table->dropColumn(['status', 'dijadwalkan_pada', 'selesai_pada', 'biaya']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeliharaan_ruangans', function (Blueprint $table) {
            $table->string('status')->default('TERJADWAL')->after('deskripsi');
            $table->dateTime('dijadwalkan_pada')->after('status');
            $table->dateTime('selesai_pada')->nullable()->after('dijadwalkan_pada');
            $table->decimal('biaya', 10, 2)->nullable()->after('selesai_pada');
        });
    }
};
