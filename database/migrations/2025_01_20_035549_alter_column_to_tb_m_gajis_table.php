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
        Schema::table('tb_m_gajis', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawai_id')->nullable()->index('FK_m_gajis_pegawais')->after('user_id');
            $table->dropColumn('tanggal_berlaku');
            $table->dropColumn('tanggal_berakhir');

            $table->foreign(['pegawai_id'], 'FK_m_gajis_pegawais')->references(['id'])->on('tb_datadiris')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_m_gajis', function (Blueprint $table) {
            $table->dropForeign('FK_m_gajis_pegawais');
        });
        Schema::dropIfExists('tb_m_gajis');
    }
};
