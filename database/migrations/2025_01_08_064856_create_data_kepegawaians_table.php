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
        Schema::create('data_kepegawaians', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('sub_jabatan_id');
            $table->integer('status_pekerjaan_id');
            $table->date('tgl_masuk');
            $table->date('tgl_berakhir');
            $table->bigInteger('no_npwp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kepegawaians');
    }
};
