<?php

namespace Tests\Unit\Models;

use App\Models\Pacientes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class PacienteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_paciente_successfully()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@holterapp.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);
        $pcte = Pacientes::create([
            'nombres' => 'Jorge',
            'apellidos' => 'Castro Perez',
            'identificacion' => '1202445898',
            'genero' => 'Masculino',
            'estado_pcte' => 'ACTIVO',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('pacientes', [
            'nombres' => 'Jorge',
            'apellidos' => 'Castro Perez',
            'identificacion' => '1202445898',
        ]);
    }
    /** @test */
    public function identificacion_must_be_unique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Pacientes::create([
            'nombres' => 'Jorge',
            'apellidos' => 'Castro Perez',
            'identificacion' => '1202445898',
            'genero' => 'Masculino',
            'estado_pcte' => 'ACTIVO',
            'user_id' => 1,
        ]);

        //Rgistro duplicado
        Pacientes::create([
            'nombres' => 'Jorge',
            'apellidos' => 'Castro Perez',
            'identificacion' => '1202445898',
            'genero' => 'Masculino',
            'estado_pcte' => 'ACTIVO',
            'user_id' => 1,
        ]);
    }
    /** @test */
    public function listado_de_pacientes_debe_ser_rapido()
    {
        //Crear un usuario con rol 'user' para acceder a la ruta
        $user = \App\Models\User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($user);

        //se realiza la poblacion a la base de datos
        \App\Models\User::factory()->count(50)->create();
        \App\Models\Pacientes::factory()->count(1000)->create();

        //Inicio de la medición
        $start = microtime(true);
        $response = $this->get('/pacientes');
        $response->assertStatus(200);
        $end = microtime(true);
        $duration = $end - $start;

        $this->assertLessThan(2, $duration, "La carga tomó más de 2 segundos.");
    }
}
