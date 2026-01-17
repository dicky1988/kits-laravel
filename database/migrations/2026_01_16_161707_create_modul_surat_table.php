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
        Schema::create('modul_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('nomor_pj')->nullable();
            $table->integer('is_aktif')->default(1);
            $table->timestamps();

            // ✅ Soft Delete
            $table->softDeletes(); // adds deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_surat');
    }
};
