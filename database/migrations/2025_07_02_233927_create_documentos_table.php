<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('estado'); // pendiente, liberado_auxiliar, etc.
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('archivo')->nullable(); // ruta del archivo si aplica
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('numero')->nullable(); // número de documento
            $table->string('tipo')->nullable(); // tipo de documento
            $table->string('solicitante')->nullable(); // quien solicita
            $table->date('fecha_solicitud')->nullable(); // fecha de solicitud
            $table->string('prioridad')->default('normal'); // normal, urgente, alta
            $table->unsignedBigInteger('liberado_por')->nullable(); // quien liberó
            $table->datetime('fecha_liberacion')->nullable(); // cuándo se liberó
            $table->text('observaciones_liberacion')->nullable(); // observaciones al liberar
            $table->unsignedBigInteger('rechazado_por')->nullable(); // quien rechazó
            $table->datetime('fecha_rechazo')->nullable(); // cuándo se rechazó
            $table->text('motivo_rechazo')->nullable(); // motivo del rechazo
            $table->unsignedBigInteger('asignado_a')->nullable(); // a quién está asignado
            $table->unsignedBigInteger('turnado_por')->nullable(); // quien turnó
            $table->datetime('fecha_turnado')->nullable(); // cuándo se turnó
            $table->text('observaciones_turnado')->nullable(); // observaciones al turnar
            $table->timestamps();
            
            // Si tienes relación con usuarios
            // $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
};
