<?php

use App\Models\TipeTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_NAME = 'tb_tasks'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = 'tb_tasks_tipe_tasks_id_foreign'
        ");

        if (empty($exists)) {
            Schema::table('tb_tasks', function (Blueprint $table) {
                $table->foreign('tipe_tasks_id')
                    ->references('id')
                    ->on('tb_tipe_tasks')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->dropForeign(['tipe_tasks_id']);

            $table->foreign('tipe_tasks_id')
                ->references('id')
                ->on('tb_tipe_tasks')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }
};
