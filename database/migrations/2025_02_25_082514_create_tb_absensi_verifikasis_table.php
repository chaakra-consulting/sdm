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
        Schema::create('tb_absensi_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('FK_absensi_verifikasis_users');
            $table->unsignedBigInteger('pegawai_id')->index('FK_absensi_verifikasis_pegawai');
            $table->unsignedBigInteger('tahun');
            $table->unsignedBigInteger('bulan');
            $table->date('tanggal_verifikasi');
            $table->boolean('is_done')->default(1);
            $table->timestamps();

            $table->foreign(['user_id'], 'FK_absensi_verifikasis_users')->references(['id'])->on('users')->onUpdate('CASCADE');
            $table->foreign(['pegawai_id'], 'FK_absensi_verifikasis_pegawais')->references(['id'])->on('tb_datadiris')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_absensi_verifikasis');
    }
};
