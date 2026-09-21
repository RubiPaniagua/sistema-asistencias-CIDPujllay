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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8)->unique(); // DNI de 8 dígitos (string para conservar ceros a la izquierda)
            $table->string('nombre', 150);
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->nullOnDelete();
            $table->enum('rol', ['admin', 'practicante'])->default('practicante');
            $table->enum('modalidad', ['presencial', 'remoto'])->nullable(); // null para admin
            $table->boolean('activo')->default(true);
            $table->string('email')->nullable()->unique();   // solo admin
            $table->string('password')->nullable();          // solo admin
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
