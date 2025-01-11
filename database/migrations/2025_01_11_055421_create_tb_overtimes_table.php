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
        Schema::create('tb_overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_overtime')->nullable();
            $table->bigInteger('durasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->bigInteger('upah')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'], 'FK_overtimes_users')->references(['id'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_overtimes', function (Blueprint $table) {
            $table->dropForeign('FK_overtimes_users');
        });
        Schema::dropIfExists('tb_overtimes');
    }
};
