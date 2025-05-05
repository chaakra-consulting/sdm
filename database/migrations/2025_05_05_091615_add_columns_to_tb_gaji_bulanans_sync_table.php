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
        Schema::table('tb_gaji_bulanans_sync', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index('FK_tb_gaji_bulanans_sync_users')->after('bukukas_invoice_item_id');
            $table->string('tipe')->nullable()->after('user_id');

            $table->foreign(['user_id'], 'FK_tb_gaji_bulanans_sync_users')->references(['id'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_gaji_bulanans_sync', function (Blueprint $table) {
            $table->dropForeign('FK_m_gajis_pegawais');
            $table->dropColumn('tipe');
        });
    }
};
