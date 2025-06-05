<?php

namespace Tests\Unit\Models;

use App\Models\Dispositivos;
use App\Models\Especialistas;
use App\Models\Pacientes;
use App\Models\Procedimientos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class ProcedimientoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_procedimiento_successfully()
    {
        [$user, $paciente, $dispositivo, $especialista] = $this->create_rows_aux()[0];
        $proc = Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => $paciente->id,
            'dispositivo_id' => $dispositivo->id,
            'especialista_id' => $especialista->id,
            'estado_proc' => 'ABIERTO'
        ]);

        $this->assertDatabaseHas('procedimientos', [
            'fecha_ini' => '2025-04-08 18:12:00',
            'duracion' => '24',
            'paciente_id' => $paciente->id,
        ]);
    }
    /** @test */
    public function foreign_keys_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => 1,
            'dispositivo_id' => 1,
            'especialista_id' => 1,
            'estado_proc' => 'ABIERTO'
        ]);

        //Duplicado
        Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => 1,
            'dispositivo_id' => 1,
            'especialista_id' => 1,
            'estado_proc' => 'ABIERTO'
        ]);
    }

    private function create_rows_aux()
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
        $disp = Dispositivos::create([
            'modelo' => 'M1234',
            'fabricante' => 'Baxter SA',
            'numero_serie' => 'SN123456',
            'estado' => 'Operativo',
        ]);
        $esp = Especialistas::create([
            'nombres' => 'Juana',
            'apellidos' => 'De la Espriella',
            'contrasena' => '123456',
            'correo' => 'jdelaespriella@holterapp.com',
            'identification' => '98233678',
            'contacto' => 3123453366,
            'especialidad' => 'Neurología',
        ]);
        $data[] = [$user, $pcte, $disp, $esp];
        return $data;
    }

    /** @test */
    public function it_belongs_to_a_paciente()
    {
        [$user, $paciente, $dispositivo, $especialista] = $this->create_rows_aux()[0];

        $procedimiento = Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => $paciente->id,
            'dispositivo_id' => $dispositivo->id,
            'especialista_id' => $especialista->id,
            'estado_proc' => 'ABIERTO',
        ]);

        $this->assertInstanceOf(Pacientes::class, $procedimiento->paciente);
        $this->assertEquals($paciente->id, $procedimiento->paciente->id);
    }

    /** @test */
    public function it_belongs_to_a_dispositivo()
    {
        [$user, $paciente, $dispositivo, $especialista] = $this->create_rows_aux()[0];

        $procedimiento = Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => $paciente->id,
            'dispositivo_id' => $dispositivo->id,
            'especialista_id' => $especialista->id,
            'estado_proc' => 'ABIERTO',
        ]);

        $this->assertInstanceOf(Dispositivos::class, $procedimiento->dispositivo);
        $this->assertEquals($dispositivo->id, $procedimiento->dispositivo->id);
    }

    /** @test */
    public function it_belongs_to_an_especialista()
    {
        [$user, $paciente, $dispositivo, $especialista] = $this->create_rows_aux()[0];

        $procedimiento = Procedimientos::create([
            'fecha_ini' => '2025-04-08 18:12:00',
            'fecha_fin' => '2025-04-09 19:12:00',
            'duracion' => '24',
            'edad' => 30,
            'paciente_id' => $paciente->id,
            'dispositivo_id' => $dispositivo->id,
            'especialista_id' => $especialista->id,
            'estado_proc' => 'ABIERTO',
        ]);

        $this->assertInstanceOf(Especialistas::class, $procedimiento->especialista);
        $this->assertEquals($especialista->id, $procedimiento->especialista->id);
    }

    /** @test */
    public function procedimiento_listado_debe_ser_rapido()
    {
        // Crear un usuario con rol 'user'
        $user = \App\Models\User::factory()->create([
            'role' => 'user',
        ]);

        // Autenticar al usuario
        $this->actingAs($user);

        $start = microtime(true);

        $response = $this->get('/procedimientos');
        $response->assertStatus(200);

        $end = microtime(true);
        $duration = $end - $start;

        $this->assertLessThan(2, $duration, "La carga tomó más de 2 segundos.");
    }
}
