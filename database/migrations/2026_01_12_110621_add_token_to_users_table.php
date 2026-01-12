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
            if (!Schema::hasColumn('users', 'api_token')) {
                $table->text('api_token')->nullable()
                    ->after('password');
            }
            if (!Schema::hasColumn('users', 'api_token_smile')) {
                $table->text('api_token_smile')->nullable()
                    ->after('api_token');
            }
            if (!Schema::hasColumn('users', 'api_token_web')) {
                $table->text('api_token_web')->nullable()
                    ->after('api_token_smile');
            }
            if (!Schema::hasColumn('users', 'jwt_token')) {
                $table->text('jwt_token')->nullable()
                    ->after('api_token_web');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
            $table->dropColumn('api_token_smile');
            $table->dropColumn('api_token_web');
            $table->dropColumn('jwt_token');
        });
    }
};
