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
        Schema::table('detail_sub_tasks', function (Blueprint $table) {
            $table->enum('status', ['approve', 'revise'])->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sub_tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
