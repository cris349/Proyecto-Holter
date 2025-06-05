<?php

namespace Tests\Unit\Models;

use App\Models\Dispositivos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DispositivoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_dispositivo_successfully()
    {
        $dispositivo = Dispositivos::create([
            'modelo' => 'M1234',
            'fabricante' => 'Baxter SA',
            'numero_serie' => 'SN123456',
            'estado' => 'Operativo',
        ]);

        $this->assertDatabaseHas('dispositivos', [
            'fabricante' => 'Baxter SA',
            'numero_serie' => 'SN123456',
        ]);
    }
    /** @test */
    public function numero_serie_is_unique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Dispositivos::create([
            'modelo' => 'M1234',
            'fabricante' => 'Baxter SA',
            'numero_serie' => 'SN123456',
            'estado' => 'Operativo',
        ]);

        // Intentar duplicarlo
        Dispositivos::create([
            'modelo' => 'M1234',
            'fabricante' => 'Baxter SA',
            'numero_serie' => 'SN123456',
            'estado' => 'Operativo',
        ]);
    }
}
