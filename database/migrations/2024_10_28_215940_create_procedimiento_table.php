<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('procedimientos', function (Blueprint $table) {
            $table->id();
            $table->datetime('fecha_ini')->nullable();
            $table->datetime('fecha_fin')->nullable();
            $table->string('duracion')->nullable();
            $table->integer('edad')->nullable();
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('dispositivo_id');
            $table->unsignedBigInteger('especialista_id');
            $table->text('observaciones')->nullable();
            $table->enum('estado_proc', ['ABIERTO', 'CERRADO', 'CANCELADO'])->default('ABIERTO');
            $table->timestamps();

            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('dispositivo_id')->references('id')->on('dispositivos');
            $table->foreign('especialista_id')->references('id')->on('especialistas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedimientos');
    }
};
