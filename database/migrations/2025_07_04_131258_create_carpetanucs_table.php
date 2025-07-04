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
        Schema::create('carpetanucs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_carpeta')->unique();
            $table->string('tipo_carpeta')->default('preliminar');
            $table->boolean('separacion_procesos')->default(false);
            $table->string('estado')->default('activa');
            $table->string('fiscal_asignado')->nullable();
            $table->string('secretario_asignado')->nullable();
            $table->string('delito_principal')->nullable();
            $table->text('delitos_secundarios')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->string('municipio')->nullable();
            $table->string('agencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('imputados')->nullable(); // Para almacenar datos de imputados
            $table->json('victimas')->nullable(); // Para almacenar datos de víctimas
            $table->json('audiencias')->nullable(); // Para almacenar datos de audiencias
            $table->json('documentos')->nullable(); // Para almacenar referencias a documentos
            $table->integer('total_folios')->default(0);
            $table->string('ubicacion_fisica')->nullable();
            $table->boolean('digitalizacion_completa')->default(false);
            $table->timestamp('fecha_ultima_actualizacion')->nullable();
            $table->string('usuario_ultima_actualizacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para optimizar búsquedas
            $table->index('numero_carpeta');
            $table->index('estado');
            $table->index('fiscal_asignado');
            $table->index('fecha_inicio');
            $table->index('municipio');
            $table->index('agencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carpetanucs');
    }
};
