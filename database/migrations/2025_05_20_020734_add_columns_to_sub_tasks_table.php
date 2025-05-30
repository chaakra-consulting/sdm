<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->string('nama_subtask')->after('user_id')->nullable();
            $table->date('tgl_selesai')->after('tgl_sub_task')->nullable();
            $table->date('deadline')->after('tgl_selesai')->nullable();
        });

        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropColumn('durasi');
            $table->dropColumn('keterangan');
            $table->dropColumn('is_active');
        });
    }
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->string('durasi')->after('tgl_sub_task')->nullable();
            $table->string('keterangan')->after('durasi')->nullable();
            $table->boolean('is_active')->default(false)->after('keterangan');
        });

        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropColumn('nama_subtask');
            $table->dropColumn('tgl_selesai');
            $table->dropColumn('deadline');
        });
    }
};
