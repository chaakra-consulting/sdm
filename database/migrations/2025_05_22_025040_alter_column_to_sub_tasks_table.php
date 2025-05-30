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
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropForeign('sub_tasks_task_id_foreign');
            $table->dropForeign('sub_tasks_user_id_foreign');

            $table->foreign('task_id')
                ->references('id')
                ->on('tb_tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropForeign('sub_tasks_task_id_foreign');
            $table->dropForeign('sub_tasks_user_id_foreign');

            $table->foreign('task_id')
                ->references('id')
                ->on('tb_tasks');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }
};