<?php

use App\Models\ProjectPerusahaan;
use App\Models\TipeTask;
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
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->foreignIdFor(TipeTask::class, 'tipe_tasks_id')->nullable()
            ->after('id')
            ->constrained('tb_tipe_tasks')
            ->onUpdate('cascade')
            ->onDelete('set null');

            $table->foreignIdFor(ProjectPerusahaan::class, 'project_perusahaan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_tasks', function (Blueprint $table) {
            $table->dropForeign(['tipe_tasks_id']);
            $table->dropColumn('tipe_tasks_id');
            $table->foreignIdFor(ProjectPerusahaan::class, 'project_perusahaan_id')->nullable(false)->change();
        });
    }
};
