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
        Schema::create('tb_gaji_bulanans_sync', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bukukas_invoice_id')->nullable();
            $table->unsignedBigInteger('bukukas_invoice_item_id')->nullable();
            $table->unsignedBigInteger('tahun')->nullable();
            $table->unsignedBigInteger('bulan')->nullable();
            $table->date('tanggal_sync_pertama')->nullable();
            $table->date('tanggal_sync_terakhir')->nullable();
            $table->boolean('is_done')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_gaji_bulanans_sync');
    }
};
