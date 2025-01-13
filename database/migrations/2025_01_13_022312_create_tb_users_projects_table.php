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
        Schema::create('tb_users_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProjectPerusahaan::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->enum('status',['belum', 'proses','selesai'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_users_projects');
    }
};
