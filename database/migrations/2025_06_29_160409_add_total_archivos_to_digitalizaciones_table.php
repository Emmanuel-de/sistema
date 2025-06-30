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
        Schema::table('digitalizaciones', function (Blueprint $table) {
            // Añade la nueva columna 'total_archivos' como un entero, no nula, con valor por defecto 0.
            // Puedes usar 'after()' para especificar dónde la quieres en la tabla (ej. después de 'archivos').
            $table->integer('total_archivos')->default(0)->after('archivos'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digitalizaciones', function (Blueprint $table) {
            // Elimina la columna si se revierte la migración.
            $table->dropColumn('total_archivos');
        });
    }
};
