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
        Schema::create(config('localization.database.translations_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale', 5)->index();
            $table->string('name')->index();
            $table->text('value')->nullable();
            $table->unique(['locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('localization.database.translations_table'));
    }
};
