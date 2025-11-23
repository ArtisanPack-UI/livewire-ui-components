<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
        });

        Schema::create('language_user', function (Blueprint $table): void {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('language_id')->constrained('languages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('country_id');
            $table->dropColumn('avatar');
            $table->dropColumn('bio');
        });

        Schema::dropIfExists('countries');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('language_user');
    }
};
