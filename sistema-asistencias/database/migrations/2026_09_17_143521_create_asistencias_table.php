<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('usuarios');

            $table->foreignId('carrera_id')
                ->constrained('carreras');

            $table->date('fecha');

            $table->dateTime('hora_entrada')
                ->nullable();

            $table->dateTime('hora_salida')
                ->nullable();

            $table->enum('modalidad', ['presencial', 'remoto']);

            $table->enum('estado', ['a_tiempo', 'tardanza'])
                ->nullable();

            $table->text('justificacion')
                ->nullable();

            $table->timestamps();

            $table->unique(['usuario_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};