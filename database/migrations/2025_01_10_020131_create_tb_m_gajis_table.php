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
        Schema::create('tb_m_gajis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index('FK_m_gajis_users');
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->bigInteger('gaji_pokok')->nullable();
            $table->bigInteger('uang_makan')->nullable();
            $table->bigInteger('uang_bensin')->nullable();
            $table->bigInteger('bpjs_ketenagakerjaan')->nullable();
            $table->bigInteger('bpjs_kesehatan')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'], 'FK_m_gajis_users')->references(['id'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_m_gajis', function (Blueprint $table) {
            $table->dropForeign('FK_m_gajis_users');
        });
        Schema::dropIfExists('tb_m_gajis');
    }
};
