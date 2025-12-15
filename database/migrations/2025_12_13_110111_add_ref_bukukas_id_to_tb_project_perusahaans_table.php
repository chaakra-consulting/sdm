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
        Schema::table('tb_project_perusahaans', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_bukukas_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_project_perusahaans', function (Blueprint $table) {
            $table->dropColumn('ref_bukukas_id');
        });
    }
};
