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
        Schema::create('surat_tte', function (Blueprint $table) {
            // PRIMARY KEY
            $table->char('id', 26)->primary();

            // RELATION / REFERENSI
            $table->unsignedBigInteger('tte_template_id')->nullable();
            $table->unsignedBigInteger('tte_draft_id')->nullable();
            $table->unsignedBigInteger('modul_surat_id')->nullable();

            // DATA DOKUMEN
            $table->string('number', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('unitCode', 255)->nullable();

            // PROSES
            $table->dateTime('upload_date')->nullable();
            $table->integer('signed_by')->nullable();

            // IDENTITAS
            $table->char('unique_code', 20)->nullable();

            // QR LOCATION
            $table->tinyInteger('qr_location_stat')->nullable()
                ->comment('1: every page; 2: first page; 3: last page; 4: custom page');
            $table->tinyInteger('qr_location_page')->nullable();

            // AUDIT
            $table->integer('created_by')->nullable();
            $table->string('phone_number', 15)->nullable();

            // STATUS
            $table->tinyInteger('stat')->default(0)
                ->comment('0: pending, 1: review progress, 2: need revision, 3: need insert number, 4: ready to sign');

            $table->tinyInteger('reviu_last')->default(0);

            // TIMESTAMP & SOFT DELETE
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tte');
    }
};
