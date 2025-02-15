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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('genre');
            $table->string('director');
            $table->string('cast');
            $table->string('version');
            $table->string('language');
            $table->integer('running_time');
            $table->dateTime('release_date');
            $table->dateTime('sneaky_show');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('has_sneaky_show')->default(false);
            $table->boolean('trending')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
