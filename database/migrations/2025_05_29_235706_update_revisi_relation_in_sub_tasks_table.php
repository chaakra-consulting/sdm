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
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropForeign(['revisi_laporan_id']);
            
            $table->foreign('revisi_laporan_id')
                ->references('id')
                ->on('revisi_laporans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropForeign(['revisi_laporan_id']);
            $table->foreign('revisi_laporan_id')
                ->references('id')
                ->on('revisi_laporans')
                ->onDelete('cascade');
        });
    }
};
