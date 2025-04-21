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
            $table->foreignId('status_pengerjaans_id')->after('user_id')
            ->nullable()
            ->constrained('status_pengerjaans')
            ->onUpdate('cascade')
            ->onDelete('set null');
        });
        Schema::table('tb_project_perusahaans', function (Blueprint $table) {
            $table->foreignId('status_pengerjaans_id')->after('perusahaan_id')
            ->nullable()
            ->constrained('status_pengerjaans')
            ->onUpdate('cascade')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->dropForeign(['status_pengerjaans_id']);
            $table->dropColumn('status_pengerjaans_id');
        });
        Schema::table('tb_project_perusahaans', function (Blueprint $table) {
            $table->dropForeign(['status_pengerjaans_id']);
            $table->dropColumn('status_pengerjaans_id');
        });
    }
};
