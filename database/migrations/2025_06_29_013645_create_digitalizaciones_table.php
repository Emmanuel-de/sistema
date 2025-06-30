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
        Schema::create('digitalizaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['carpeta_preliminar', 'carpeta_procesal', 'amparo', 'oficio', 'evidencia']);
            $table->string('nuc');
            $table->string('presentado_por');
            $table->date('fecha_presentacion');
            $table->text('comentario')->nullable();
            $table->boolean('ocr')->default(false);
            $table->boolean('visor')->default(false);
            $table->json('archivos')->nullable(); // Para almacenar información de archivos
            $table->string('estado')->default('pendiente'); // pendiente, procesando, completado
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index('nuc');
            $table->index('tipo');
            $table->index('fecha_presentacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digitalizaciones');
    }
};
