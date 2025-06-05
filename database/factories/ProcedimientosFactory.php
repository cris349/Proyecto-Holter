<?php

namespace Database\Factories;

use App\Models\Procedimientos;
use App\Models\Pacientes;
use App\Models\Dispositivos;
use App\Models\Especialistas;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedimientosFactory extends Factory
{
    protected $model = Procedimientos::class;

    public function definition()
    {
        return [
            'fecha_ini' => $this->faker->dateTime(),
            'fecha_fin' => $this->faker->dateTime(),
            'duracion' => $this->faker->word(),
            'edad' => $this->faker->numberBetween(18, 80),
            'paciente_id' => $this->faker->numberBetween(1, 90),
            'dispositivo_id' => $this->faker->numberBetween(1, 10),
            'especialista_id' => $this->faker->numberBetween(1, 10),
            'observaciones' => $this->faker->paragraph(),
            'estado_proc' => $this->faker->randomElement(['ABIERTO', 'CERRADO', 'CANCELADO']),
        ];
    }
}
