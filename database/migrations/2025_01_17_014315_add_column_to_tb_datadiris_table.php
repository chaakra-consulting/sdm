<?php

use App\Models\DataKepegawaian;
use Dflydev\DotAccessData\Data;
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
        Schema::table('tb_datadiris', function (Blueprint $table) {
            $table->foreignIdFor(DataKepegawaian::class)
            ->nullable()
            ->after('user_id')
            ->constrained()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_datadiris', function (Blueprint $table) {
            //
        });
    }
};
