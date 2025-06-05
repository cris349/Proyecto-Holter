<?php

namespace Database\Factories;

use App\Models\Pacientes;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacientesFactory extends Factory
{
    protected $model = Pacientes::class;

    public function definition()
    {
        return [
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'identificacion' => $this->faker->unique()->numerify('###########'),
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'fecha_nacimiento' => $this->faker->date(),
            'direccion' => $this->faker->address(),
            'celular' => $this->faker->numberBetween(3000000000, 3059999999),
            'estado_pcte' => $this->faker->randomElement(['ACTIVO', 'INACTIVO']),
            'user_id' => $this->faker->numberBetween(5, 45)
        ];
    }
}
