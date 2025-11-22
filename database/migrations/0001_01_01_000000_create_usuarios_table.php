<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Define la estructura de la tabla 'usuarios'.
     */
    public function up(): void
    {
        // Define la tabla 'usuarios'
        Schema::create('usuarios', function (Blueprint $table) {
            // Id (Identificador principal)
            $table->id(); 
            
            // Nombre
            $table->string('nombre'); 
            
            // Correo (Identificador Único, requerido en la evaluación)
            $table->string('correo')->unique(); 
            
            // Clave (Almacenará la contraseña cifrada)
            $table->string('clave'); 
            
            // created_at y updated_at
            $table->timestamps(); 
        });

        // Las siguientes tablas (password_reset_tokens y sessions) son estándar de Laravel,
        // pero se incluyen aquí para la integridad del entorno si las necesitas.
        
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     * Elimina las tablas si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        // Debe ser la última en eliminarse si se incluye la tabla 'proyectos'
        Schema::dropIfExists('usuarios'); 
    }
};