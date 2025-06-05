<?php

namespace Database\Factories;

use App\Models\Especialistas;
use Illuminate\Database\Eloquent\Factories\Factory;

class EspecialistasFactory extends Factory
{
    protected $model = Especialistas::class;

    public function definition()
    {
        return [
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'correo' => $this->faker->unique()->email(),
            'contrasena' => bcrypt('password'),
            'identification' => $this->faker->unique()->numerify('##########'),
            'especialidad' => $this->faker->word(),
            'contacto' => $this->faker->numberBetween(3000000000, 3059999999),
            'estado_esp' => $this->faker->randomElement(['ACTIVO', 'INACTIVO']),
        ];
    }
}
