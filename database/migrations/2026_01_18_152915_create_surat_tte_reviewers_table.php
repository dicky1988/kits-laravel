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
        Schema::create('surat_tte_reviewers', function (Blueprint $table) {
            // PRIMARY KEY (AUTO INCREMENT)
            $table->bigIncrements('id');

            // RELASI KE TTE SURAT
            $table->char('tte_id', 26)->index();

            // DATA ESELON
            $table->tinyInteger('eselon')->nullable();
            $table->integer('review_by')->nullable();

            // TIMESTAMP
            $table->timestamps();
            $table->softDeletes();

            // OPTIONAL FK (aktifkan jika perlu)
            $table->foreign('tte_id')
                   ->references('id')
                   ->on('surat_tte')
                   ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tte_reviewers');
    }
};
