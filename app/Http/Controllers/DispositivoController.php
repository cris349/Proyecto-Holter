<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispositivoController extends Controller
{
    public function agregar_dispositivo()
    {

        return view('agregar_dispositivo');
    }
}
