<?php

namespace App\Http\Controllers;

use App\Models\dispositivos;
use App\Models\especialistas;
use App\Models\pacientes;
use Illuminate\Http\Request;

class ProcedimientosController extends Controller
{
    public function NuevoProcedimiento()
    {
        $pacientesModel = new pacientes;
        $dispositivosModel = new dispositivos;
        $especialistasModel = new especialistas;
        $pacientes = $pacientesModel::where('estado_pcte', '=', 'ACTIVO')->get();
        $especialistas = $especialistasModel::where('estado_esp', '=', 'ACTIVO')->get();
        $dispositivos = $dispositivosModel::where('estado', '=', 'Operativo')->get();
        return [
            'pacientes' => $pacientes,
            'especialistas' => $especialistas,
            'dispositivos' => $dispositivos,
        ];
    }
}
