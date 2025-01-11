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
        Schema::create('tb_absensi_harians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('FK_abensi_harians_users');
            $table->date('tanggal_kerja');
            $table->string('hari_kerja')->nullable();
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->string('jenis_keterangan')->nullable();
            $table->string('upload_surat_dokter')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'], 'FK_abensi_harians_users')->references(['id'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_absensi_harians', function (Blueprint $table) {
            $table->dropForeign('FK_abensi_harians_users');
        });
        Schema::dropIfExists('tb_absensi_harians');
    }
};
