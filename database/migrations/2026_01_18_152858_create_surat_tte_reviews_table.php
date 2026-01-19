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
        Schema::create('surat_tte_reviews', function (Blueprint $table) {
            // PRIMARY KEY
            $table->char('id', 26)->primary();

            // RELASI SURAT TTE
            $table->char('tte_id', 26)->index();

            // DATA REVIEW
            $table->integer('review_number');
            $table->text('note')->nullable();

            // STATUS & TIPE
            $table->tinyInteger('stat')->default(0)
                ->comment('0: pending, 1: approved, 2: rejected');

            $table->integer('review_by')->nullable();
            $table->tinyInteger('type')->nullable()
                ->comment('1: internal, 2: eksternal');

            $table->tinyInteger('filterable')->default(0);
            $table->tinyInteger('is_reject_to_conceptor')->default(0);

            // WAKTU REVIEW
            $table->dateTime('reviewed_at')->nullable();

            // TIMESTAMP
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('surat_tte_reviews');
    }
};
