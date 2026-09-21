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
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->string('modalidad')->default('presencial');
            $table->text('actividad')->nullable();
            $table->string('estado')->default('a_tiempo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};