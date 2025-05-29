<?php

use App\Models\User;
use App\Models\RevisiLaporan;
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
        Schema::create('revisi_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('pesan')->nullable();
            $table->timestamps();
        });
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->foreignIdFor(RevisiLaporan::class)->nullable()->after('status')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropForeign(['revisi_laporan_id']);
            $table->dropColumn('revisi_laporan_id');
        });
        
        Schema::dropIfExists('revisi_laporans');
    }
};
