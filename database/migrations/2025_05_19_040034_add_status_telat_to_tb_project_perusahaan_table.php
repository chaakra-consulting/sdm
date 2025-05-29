<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tb_project_perusahaans MODIFY status ENUM('belum', 'proses', 'selesai', 'telat') DEFAULT 'belum'");
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->enum('status', ['belum', 'proses', 'selesai', 'telat'])
                ->default('belum')
                ->after('tgl_task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tb_project_perusahaans MODIFY status ENUM('belum', 'proses', 'selesai') DEFAULT 'belum'");
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
