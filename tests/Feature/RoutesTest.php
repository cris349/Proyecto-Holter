<?php

namespace Tests\Feature;

use App\Models\Dispositivos;
use App\Models\Especialistas;
use App\Models\Pacientes;
use App\Models\Procedimientos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_are_redirected_to_login()
    {
        $routes = [
            'dashboard',
            'pacientes',
            'especialistas',
            'dispositivos',
            'procedimientos',
            'registros',
            'reportes',
        ];

        foreach ($routes as $route) {
            $response = $this->get(route($route, ['id' => 1]));
            $response->assertRedirect('/login');
        }
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $adminRoutes = ['dashboard', 'pacientes', 'especialistas', 'dispositivos'];

        foreach ($adminRoutes as $route) {
            $response = $this->get(route($route));
            $response->assertOk();
        }
    }

    /** @test */
    public function user_can_access_user_routes()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user);

        $userRoutes = ['dashboard', 'procedimientos', 'registros'];

        foreach ($userRoutes as $route) {
            if ($route === 'registros') {
                $response = $this->get(route($route, ['id' => 1]));
            } else {
                $response = $this->get(route($route));
            }
            $response->assertOk();
        }
    }

    /** @test */
    public function cliente_can_access_cliente_routes()
    {
        $cliente = User::factory()->create([
            'role' => 'cliente',
        ]);
        $this->dataFactoryByTest($cliente->id);
        $this->actingAs($cliente);

        $clienteRoutes = ['dashboard', 'reportes'];

        foreach ($clienteRoutes as $route) {
            $response = $this->get(route($route));
            $response->assertOk();
        }
    }

    private function dataFactoryByTest($id)
    {
        // Crear el paciente asociado al cliente
        $paciente = Pacientes::create([
            'nombres' => 'Pedro',
            'apellidos' => 'Pérez',
            'identificacion' => '123456789',
            'genero' => 'Masculino',
            'estado_pcte' => 'ACTIVO',
            'fecha_nacimiento' => '1990-01-01', // Muy importante para calcular edad
            'user_id' => $id,
        ]);

        // Crear un dispositivo dummy
        $dispositivo = Dispositivos::create([
            'modelo' => 'ModeloX',
            'fabricante' => 'FabricanteX',
            'numero_serie' => 'SN123457',
            'estado' => 'Operativo',
        ]);

        // Crear especialista dummy
        $especialista = Especialistas::create([
            'nombres' => 'María',
            'apellidos' => 'Gómez',
            'contrasena' => bcrypt('password'), // Usa bcrypt
            'correo' => 'maria@holterapp.com',
            'identification' => '987654321',
            'contacto' => '3112345678',
            'especialidad' => 'Cardiología',
        ]);

        // Crear un procedimiento CERRADO
        Procedimientos::create([
            'fecha_ini' => now()->subDays(2),
            'fecha_fin' => now()->subDay(),
            'duracion' => 24,
            'edad' => 33,
            'paciente_id' => $paciente->id,
            'dispositivo_id' => $dispositivo->id,
            'especialista_id' => $especialista->id,
            'estado_proc' => 'CERRADO',
            'observaciones' => 'Todo bien',
        ]);
    }
}
