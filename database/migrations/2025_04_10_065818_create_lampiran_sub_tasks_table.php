<?php

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
        Schema::create('lampiran_sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubTask::class, 'sub_task_id')->constrained('sub_tasks');
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_sub_tasks');
    }
};
