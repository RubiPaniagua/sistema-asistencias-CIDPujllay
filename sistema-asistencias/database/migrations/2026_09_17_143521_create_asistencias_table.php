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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->restrictOnDelete();
            $table->date('fecha');
            $table->time('hora_entrada');
            $table->time('hora_salida')->nullable();
            $table->enum('modalidad', ['presencial', 'remoto']);
            $table->enum('estado', ['a_tiempo', 'tardanza', 'falta'])->default('a_tiempo');
            $table->text('justificacion')->nullable();
            $table->timestamps();

            // Una sola asistencia por persona y día (respaldo de la regla 4 del controlador)
            $table->unique(['usuario_id', 'fecha']);
            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
