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
        Schema::create('surat_tte_files', function (Blueprint $table) {
            $table->id(); // bigint auto increment

            $table->char('tte_id', 26);
            $table->string('name', 255);
            $table->dateTime('signed_at')->nullable();
            $table->string('signed_link', 255)->nullable();
            $table->string('signed_path', 255)->nullable();
            $table->char('unique_code', 20);
            $table->unsignedBigInteger('mimetype_id');

            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at

            // Optional: index / foreign key
            // $table->foreign('mimetype_id')->references('id')->on('mimetypes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tte_files');
    }
};
