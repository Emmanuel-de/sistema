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
        Schema::create('datos_complementarios', function (Blueprint $table) {
            $table->id();
            
            // Campos principales
            $table->string('folio_unico', 50)->unique()->comment('Folio único del documento');
            $table->enum('tipo_documento', ['oficio', 'memorandum', 'circular', 'informe'])->comment('Tipo de documento');
            $table->string('nuc', 100)->nullable()->comment('Número Único de Caso');
            $table->date('fecha_recepcion')->nullable()->comment('Fecha de recepción del documento');
            $table->string('quien_presenta', 255)->nullable()->comment('Persona que presenta el documento');
            $table->integer('numero_hojas')->nullable()->comment('Número de hojas del documento');
            $table->integer('numero_anexos')->default(0)->comment('Número de anexos');
            $table->text('descripcion')->nullable()->comment('Descripción del documento');
            $table->string('numero_oficio', 100)->nullable()->comment('Número de oficio');
            $table->date('fecha_oficio')->nullable()->comment('Fecha del oficio');
            $table->enum('tipo_audiencia', ['inicial', 'intermedia', 'juicio', 'sentencia'])->nullable()->comment('Tipo de audiencia');
            $table->string('numero_amparo', 100)->nullable()->comment('Número de amparo');
            $table->string('precedencia', 255)->nullable()->comment('Precedencia del documento');
            $table->string('entidad', 255)->nullable()->comment('Entidad relacionada');
            $table->enum('solicita_informe', ['si', 'no'])->nullable()->comment('Solicita informe');
            
            // Campos de control
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->comment('Estado del registro');
            $table->unsignedBigInteger('usuario_creacion')->nullable()->comment('Usuario que creó el registro');
            $table->unsignedBigInteger('usuario_modificacion')->nullable()->comment('Usuario que modificó el registro');
            $table->unsignedBigInteger('usuario_eliminacion')->nullable()->comment('Usuario que eliminó el registro');
            $table->timestamp('fecha_eliminacion')->nullable()->comment('Fecha de eliminación');
            
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['folio_unico', 'estado']);
            $table->index(['tipo_documento', 'estado']);
            $table->index(['fecha_recepcion', 'estado']);
            $table->index(['nuc', 'estado']);
            $table->index('created_at');
            
            // Comentario de la tabla
            $table->comment('Tabla para almacenar datos complementarios de documentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_complementarios');
    }
};