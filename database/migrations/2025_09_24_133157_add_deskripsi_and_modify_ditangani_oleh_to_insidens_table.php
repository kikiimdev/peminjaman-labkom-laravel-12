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
        Schema::table('insidens', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('tingkat');
            $table->string('ditangani_oleh')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insidens', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
            $table->unsignedBigInteger('ditangani_oleh')->nullable()->change();
        });
    }
};
