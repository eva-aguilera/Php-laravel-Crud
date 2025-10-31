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
       Schema::create('proyectos', function (Blueprint $table) {
            // Clave Primaria
            $table->id(); // Equivale a bigIncrements('id')

            // Campos definidos
            $table->string('nombre', 100);
            $table->date('fecha_inicio');
            $table->string('estado', 50)->default('Activo'); // Valor por defecto útil
            $table->string('responsable', 100);
            $table->decimal('monto', 10, 2);

            // Tiempos de Laravel
            $table->timestamps(); // Crea las columnas created_at y updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
