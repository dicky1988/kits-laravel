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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->after('active_role_id')->nullable();
            $table->integer('eselon')->after('nik')->nullable();
            $table->string('akses_modul')->after('eselon')->nullable();
            $table->integer('is_ujikom')->after('akses_modul')->nullable();
            $table->integer('is_sertifikat')->after('is_ujikom')->nullable();
            $table->integer('is_bangkom')->after('is_sertifikat')->nullable();
            $table->integer('is_skp')->after('is_bangkom')->nullable();
            $table->integer('is_bidang3')->after('is_skp')->nullable();
            $table->integer('is_aktif')->after('is_bidang3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
