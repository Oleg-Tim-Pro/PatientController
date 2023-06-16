<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграции.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('age_type');
            $table->timestamps();
        });
    }

    /**
     * Отменить миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
