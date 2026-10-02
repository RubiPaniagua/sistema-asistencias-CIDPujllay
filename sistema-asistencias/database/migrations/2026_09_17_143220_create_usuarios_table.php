<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('dni')->unique();
            $table->string('nombres');
            $table->string('apellidos');

            $table->foreignId('carrera_id')
                ->nullable()
                ->constrained('carreras');

            $table->foreignId('institucion_id')
                ->nullable()
                ->constrained('instituciones');

            $table->enum('rol', ['admin', 'practicante']);

            $table->enum('modalidad', ['presencial', 'remoto'])
                ->nullable();

            $table->boolean('activo')->default(true);

            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};