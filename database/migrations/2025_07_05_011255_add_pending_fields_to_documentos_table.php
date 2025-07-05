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
        Schema::table('documentos', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('documentos', 'persona_ordena')) {
                $table->string('persona_ordena')->nullable();
            }
            if (!Schema::hasColumn('documentos', 'fecha_limite')) {
                $table->date('fecha_limite')->nullable();
            }
            if (!Schema::hasColumn('documentos', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            if (!Schema::hasColumn('documentos', 'creado_por')) {
                $table->unsignedBigInteger('creado_por')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn(['persona_ordena', 'fecha_limite', 'observaciones', 'creado_por']);
        });
    }
};