<?php

use App\Models\User;
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
        if (Schema::hasTable('sub_tasks')) {
            Schema::table('sub_tasks', function (Blueprint $table){
                if (Schema::hasColumn('sub_tasks', 'revisi_laporan_id')) {
                    $table->dropForeign(['revisi_laporan_id']);
                    $table->dropColumn('revisi_laporan_id');
                }
            });
        }
        Schema::dropIfExists('revisi_laporans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('revisi_laporans', function (Blueprint $table){
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('pesan')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('sub_tasks')) {
            Schema::table('sub_tasks', function (Blueprint $table){
                if (!Schema::hasColumn('sub_tasks', 'revisi_laporan_id')) {
                    $table->unsignedBigInteger('revisi_laporan_id')->nullable()->after('status');
                    $table->foreign('revisi_laporan_id')->references('id')->on('revisi_laporans')->onDelete('cascade');
                }
            });
        }
    }
};
