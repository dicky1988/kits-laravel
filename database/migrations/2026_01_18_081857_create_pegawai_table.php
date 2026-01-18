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
        Schema::create('pegawai', function (Blueprint $table) {
            // PRIMARY KEY
            $table->unsignedBigInteger('pegawaiID')->primary();

            // IDENTITAS
            $table->string('nik', 191)->nullable();

            // DATA PEGAWAI (NOT NULL)
            $table->string('pegawaiName', 191);
            $table->string('pegawaiNIP', 191);
            $table->string('pegawaiNIPLama', 191);
            $table->string('pegawaiUnit', 191);
            $table->string('pegawaiUnitName', 191);

            // INSTANSI UNIT ORGANISASI
            $table->string('s_kd_instansiunitorg', 191);
            $table->string('s_nama_instansiunitorg', 191);

            $table->string('s_kd_instansiunitkerjal1', 191);
            $table->string('s_nama_instansiunitkerjal1', 191);

            $table->string('s_kd_instansiunitkerjal2', 191);
            $table->string('s_nama_instansiunitkerjal2', 191);

            $table->string('s_kd_instansiunitkerjal3', 191);
            $table->string('s_nama_instansiunitkerjal3', 191);

            // JABATAN
            $table->string('s_kd_jabdetail', 191);
            $table->string('jabatan', 191);

            // STRUKTURAL
            $table->tinyInteger('eselon')->nullable();

            // FLAG
            $table->tinyInteger('isPusdiklatwas')->default(0);
            $table->tinyInteger('isPusbinjfa')->default(0);

            // TIMESTAMP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
