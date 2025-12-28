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
        Schema::table('tb_m_perusahaans', function (Blueprint $table) {
            $table->unsignedBigInteger('bukukas_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_m_perusahaans', function (Blueprint $table) {
            $table->dropColumn('bukukas_id');
        });
    }
};
