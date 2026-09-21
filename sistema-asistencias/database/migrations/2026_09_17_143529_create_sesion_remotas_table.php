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
            $table->string('codigo_temporal', 10)->unique();
            $table->foreignId('generado_por')->constrained('usuarios')->cascadeOnDelete();
            $table->dateTime('expira_en');
            $table->boolean('usado')->default(false);
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
