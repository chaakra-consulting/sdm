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
        Schema::table('tb_gaji_bulanans', function (Blueprint $table) {
            $table->string('hash')->unique()->after('id');
            $table->unsignedBigInteger('pegawai_id')->after('user_id')->index('FK_gaji_bulanans_pegawais');
            $table->unsignedBigInteger('gaji_pokok')->after('tanggal_gaji');
            $table->unsignedBigInteger('potongan_bpjs_ketenagakerjaan')->after('potongan_pajak');
            $table->unsignedBigInteger('potongan_bpjs_kesehatan')->after('potongan_bpjs_ketenagakerjaan');
            $table->unsignedBigInteger('insentif_uang_makan')->after('insentif_kinerja');
            $table->unsignedBigInteger('insentif_uang_bensin')->after('insentif_uang_makan');
            $table->json('data')->nullable()->after('keterangan_insentif_lainnya');

            $table->dropColumn('insentif_tugas');

            $table->foreign(['pegawai_id'], 'FK_gaji_bulanans_pegawais')->references(['id'])->on('tb_datadiris')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_gaji_bulanans', function (Blueprint $table) {
            $table->dropForeign('FK_gaji_bulanans_pegawais');
            $table->dropColumn('hash');
            $table->dropColumn('pegawai_id');
            $table->dropColumn('gaji_pokok');
            $table->dropColumn('potongan_bpjs_ketenagakerjaan');
            $table->dropColumn('potongan_bpjs_kesehatan');
            $table->dropColumn('insentif_uang_makan');
            $table->dropColumn('insentif_uang_bensin');
            $table->dropColumn('data');
            $table->bigInteger('insentif_tugas')->nullable();
        });
    }
};
