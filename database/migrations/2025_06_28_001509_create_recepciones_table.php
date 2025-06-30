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
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->string('nuc')->index(); // Índice para búsquedas rápidas
            $table->string('tipo_audiencia');
            $table->string('quien_presenta')->index(); // Índice para búsquedas
            $table->string('numero_oficio', 100)->index(); // Índice para búsquedas
            $table->date('fecha_oficio');
            $table->integer('numero_fojas')->unsigned();
            $table->boolean('accion_penal')->default(false);
            $table->boolean('tiene_anexos')->default(false);
            $table->integer('numero_anexos')->unsigned()->nullable();
            $table->text('descripcion_anexos')->nullable();
            $table->timestamps();
            
            // Índices compuestos para consultas comunes
            $table->index(['fecha_oficio', 'created_at']);
            $table->index(['tiene_anexos', 'numero_anexos']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones');
    }
};