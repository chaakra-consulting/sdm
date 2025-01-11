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
        Schema::create('tb_gaji_bulanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('FK_gaji_bulanans_users');
            $table->unsignedBigInteger('master_gaji_id')->nullable();
            $table->date('tanggal_gaji')->nullable();
            $table->bigInteger('potongan_kinerja')->nullable();
            $table->bigInteger('potongan_kehadiran')->nullable();
            $table->bigInteger('potongan_pajak')->nullable();
            $table->bigInteger('potongan_kasbon')->nullable();
            $table->bigInteger('potongan_lainnya')->nullable();
            $table->text('keterangan_lainnya')->nullable();
            $table->bigInteger('insentif_kinerja')->nullable();
            $table->bigInteger('insentif_tugas')->nullable();
            $table->bigInteger('insentif_penjualan')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'], 'FK_gaji_bulanans_users')->references(['id'])->on('users')->onUpdate('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_gaji_bulanans');
    }
};
