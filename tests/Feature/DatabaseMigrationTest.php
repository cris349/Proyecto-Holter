<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_migrations_run_successfully()
    {
        // Aquí simplemente tratamos de hacer operaciones en tablas migradas
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('dispositivos'));
        $this->assertTrue(Schema::hasTable('especialistas'));
        $this->assertTrue(Schema::hasTable('pacientes'));
        $this->assertTrue(Schema::hasTable('procedimientos'));
        $this->assertTrue(Schema::hasTable('registros_holters'));
    }
}
