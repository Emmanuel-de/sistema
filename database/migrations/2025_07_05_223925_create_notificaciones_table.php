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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->datetime('fecha_notificacion')->nullable();
            $table->datetime('fecha_alta')->nullable();
            $table->string('persona_notifico')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('numero_telefono', 20)->nullable();
            $table->string('audio_path')->nullable();
            $table->unsignedBigInteger('usuario_notifica_id')->nullable();
            $table->string('seleccione_notificacion')->nullable();
            $table->string('nuc', 50)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('usuario_notifica_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['fecha_notificacion', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};