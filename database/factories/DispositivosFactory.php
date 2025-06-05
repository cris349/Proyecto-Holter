<?php

namespace Database\Factories;

use App\Models\Dispositivos;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispositivosFactory extends Factory
{
    protected $model = Dispositivos::class;

    public function definition()
    {
        return [
            'modelo' => $this->faker->word(),
            'fabricante' => $this->faker->company(),
            'numero_serie' => $this->faker->unique()->word(),
            'estado' => $this->faker->randomElement(['Operativo', 'En Uso', 'En Reparación', 'Fuera de Servicio']),
        ];
    }
}
