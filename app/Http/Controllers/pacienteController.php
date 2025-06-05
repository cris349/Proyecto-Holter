<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function paciente()
    {
        return view('paciente');
    }
}
