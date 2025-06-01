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
        Schema::table('lampiran_sub_tasks', function (Blueprint $table) {
            $table->dropForeign('lampiran_sub_tasks_sub_task_id_foreign');

            $table->foreign('sub_task_id')
                ->references('id')
                ->on('sub_tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lampiran_sub_tasks', function (Blueprint $table) {
            $table->dropForeign('lampiran_sub_tasks_sub_task_id_foreign');

            $table->foreign('sub_task_id')
                ->references('id')
                ->on('sub_tasks');
        });
    }
};
