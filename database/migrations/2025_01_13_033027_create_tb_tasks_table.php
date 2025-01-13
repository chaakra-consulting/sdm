<?php

use App\Models\User;
use App\Models\ProjectPerusahaan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProjectPerusahaan::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('nama_task');
            $table->date('tgl_task');
            $table->string('keterangan');
            $table->string('upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tasks');
    }
};
