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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_video');
            $table->date('fecha_subida');
            $table->string('archivo_path'); // Ruta del archivo en storage
            $table->string('archivo_original'); // Nombre original del archivo
            $table->string('extension', 10); // Extensión del archivo
            $table->bigInteger('tamano'); // Tamaño en bytes
            $table->string('mime_type', 50); // Tipo MIME del archivo
            $table->enum('estado', ['activo', 'inactivo', 'procesando'])->default('activo');
            $table->text('descripcion')->nullable(); // Descripción opcional
            $table->integer('duracion')->nullable(); // Duración en segundos
            $table->string('thumbnail_path')->nullable(); // Ruta de la miniatura
            $table->integer('vistas')->default(0); // Contador de vistas
            $table->json('metadata')->nullable(); // Metadatos adicionales en JSON
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index('fecha_subida');
            $table->index('estado');
            $table->index(['nombre_video', 'fecha_subida']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
