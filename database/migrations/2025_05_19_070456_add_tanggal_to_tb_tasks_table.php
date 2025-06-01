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
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->date('tgl_selesai')
                ->nullable()
                ->after('tgl_task');
            $table->date('deadline')
                ->nullable()
                ->after('tgl_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->dropColumn('tgl_selesai');
            $table->dropColumn('deadline');
        });
    }
};
