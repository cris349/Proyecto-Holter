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
        Schema::create('registros_holters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedimiento_id');
            $table->time('hora')->default('00:00')->nullable();

            $table->string('fc_min', 3);
            $table->time('hora_fc_min')->default('00:00')->nullable();
            $table->string('fc_max', 3);
            $table->time('hora_fc_max')->default('00:00')->nullable();
            $table->string('fc_medio', 3);
            $table->string('total_latidos', 10);
            $table->string('vent_total', 3);
            $table->string('supr_total', 3);
            $table->foreign('procedimiento_id')->references('id')->on('procedimientos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_holters');
    }
};
