<?php

use App\Models\User;
use App\Models\SubTask;
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
        Schema::create('detail_sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubTask::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('durasi')->nullable();
            $table->boolean('is_active')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sub_tasks');
    }
};
