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
        Schema::table('detail_sub_tasks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'revise'])->default('draft')->after('keterangan');
            
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approved_at');
            $table->timestamp('submitted_at')->nullable()->after('approval_notes');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sub_tasks', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'approval_notes', 'submitted_at']);
            $table->enum('status', ['approve', 'revise'])->nullable()->after('keterangan');
        });
    }
};
