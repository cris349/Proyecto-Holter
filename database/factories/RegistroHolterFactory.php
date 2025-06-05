<?php

namespace Database\Factories;

use App\Models\Registros;
use App\Models\Procedimientos;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistroHolterFactory extends Factory
{
    protected $model = Registros::class;

    public function definition()
    {
        return [
            'procedimiento_id' => Procedimientos::factory(),
            'hora' => $this->faker->time(),
            'fc_min' => $this->faker->randomNumber(2),
            'hora_fc_min' => $this->faker->time(),
            'fc_max' => $this->faker->randomNumber(2),
            'hora_fc_max' => $this->faker->time(),
            'fc_medio' => $this->faker->randomNumber(2),
            'total_latidos' => $this->faker->randomNumber(5),
            'vent_total' => $this->faker->randomNumber(2),
            'supr_total' => $this->faker->randomNumber(2),
        ];
    }
}
