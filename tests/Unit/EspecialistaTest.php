<?php

namespace Tests\Unit\Models;

use App\Models\Especialistas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EspecialistaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_especialista_successfully()
    {
        $esp = Especialistas::create([
            'nombres' => 'Juana',
            'apellidos' => 'De la Espriella',
            'contrasena' => '123456',
            'correo' => 'jdelaespriella@holterapp.com',
            'identification' => '98233678',
            'contacto' => 3123453366,
            'especialidad' => 'Neurología',
        ]);

        $this->assertDatabaseHas('especialistas', [
            'correo' => 'jdelaespriella@holterapp.com',
            'identification' => '98233678',
        ]);
    }

    /** @test */
    public function identification_must_be_unique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Especialistas::create([
            'nombres' => 'Juana',
            'apellidos' => 'De la Espriella',
            'contrasena' => '123456',
            'correo' => 'jdelaespriella@holterapp.com',
            'identification' => '98233678',
            'contacto' => 3123453366,
            'especialidad' => 'Neurología',
        ]);

        // Intentar duplicarlo
        Especialistas::create([
            'nombres' => 'Juana',
            'apellidos' => 'De la Espriella',
            'contrasena' => '123456',
            'correo' => 'jdelaespriella@holterapp.com',
            'identification' => '98233678',
            'contacto' => 3123453366,
            'especialidad' => 'Neurología',
        ]);
    }
}
