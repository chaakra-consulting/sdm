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
            $table->string('alamat')->after('nama_perusahaan')->nullable();
            $table->string('nama_pimpinan')->after('alamat')->nullable();
            $table->string('kontak')->after('nama_pimpinan')->nullable();
            $table->string('gender')->after('kontak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_m_perusahaans', function (Blueprint $table) {
            $table->dropColumn('alamat');
            $table->dropColumn('nama_pimpinan');
            $table->dropColumn('kontak');
            $table->dropColumn('gender');
        });
    }
};
