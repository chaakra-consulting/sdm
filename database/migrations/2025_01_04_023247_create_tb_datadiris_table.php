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
        Schema::create('tb_datadiris', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('foto_user')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat_ktp');
            $table->string('alamat_domisili');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->string('no_hp');
            $table->string('status_pernikahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_datadiris');
    }
};
