<?php

use App\Models\Perusahaan;
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
        Schema::create('tb_project_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Perusahaan::class)->constrained();
            $table->string('nama_project');
            $table->enum('skala_project', ['kecil', 'sedang', 'besar'])->nullable();
            $table->date('waktu_mulai')->nullable();
            $table->date('waktu_berakhir')->nullable();
            $table->date('deadline')->nullable();
            $table->double('progres')->nullable();
            $table->enum('status',['belum', 'proses','selesai'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_project_perusahaans');
    }
};
