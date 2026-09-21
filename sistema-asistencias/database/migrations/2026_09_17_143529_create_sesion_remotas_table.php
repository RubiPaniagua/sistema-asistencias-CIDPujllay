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
        Schema::create('sesion_remotas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('generado_por')->constrained('usuarios')->onDelete('cascade');
            $table->timestamp('expira_at');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_remotas');
    }
};