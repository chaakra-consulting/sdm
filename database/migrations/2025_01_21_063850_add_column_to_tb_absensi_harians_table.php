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
        Schema::table('tb_absensi_harians', function (Blueprint $table) {
            $table->dropColumn('jenis_keterangan');
            $table->unsignedBigInteger('keterangan_id')->index('FK_absensi_harians_keterangans')->after('upload_surat_dokter');
            $table->bigInteger('durasi_lembur')->nullable()->after('keterangan');
            $table->json('data')->nullable()->after('durasi_lembur');

            $table->foreign(['keterangan_id'], 'FK_absensi_harians_keterangans')->references(['id'])->on('tb_keterangan_absensis')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_absensi_harians', function (Blueprint $table) {
            $table->dropForeign('FK_absensi_harians_keterangans');
            $table->dropColumn('durasi_lembur');
            $table->dropColumn('keterangan_id');
            $table->dropColumn('data');
            $table->string('jenis_keterangan')->nullable()->after('waktu_pulang');
        });
    }
};
