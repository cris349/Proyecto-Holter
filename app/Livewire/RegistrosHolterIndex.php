<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Procedimientos;
use App\Models\Registros;

class RegistrosHolterIndex extends Component
{
    public $procedimiento_id;
    public $hora;
    public $fc_min;
    public $hora_fc_min;
    public $fc_max;
    public $hora_fc_max;
    public $rc_medio;
    public $total_latidos;
    public $vent_total;
    public $supr_total;

    public $modalReport = false;
    public $estadoModal;
    public $datosGrafico;

    public $ttVentricular;
    public $ttSupraVentricular;

    public function mount($id)
    {
        $this->procedimiento_id = $id;
    }

    public function getProcedimientoId($id)
    {
        return Procedimientos::where('procedimientos.id', $id)
            ->join('pacientes', 'procedimientos.paciente_id', '=', 'pacientes.id')
            ->join('dispositivos', 'procedimientos.dispositivo_id', '=', 'dispositivos.id')
            ->join('especialistas', 'procedimientos.especialista_id', '=', 'especialistas.id')
            ->select('procedimientos.*', 'pacientes.identificacion', 'pacientes.nombres', 'pacientes.apellidos', 'dispositivos.numero_serie', 'especialistas.nombres as fname_esp', 'especialistas.apellidos as lname_esp', 'especialistas.identification as id_esp', 'especialistas.especialidad as espp',)
            ->get();
    }

    public function getRegistrosProcById($id)
    {
        return Registros::where('procedimiento_id', $id)->get();
    }


    private function getFCMin($id)
    {
        return Registros::where('procedimiento_id', $id)
            ->selectRaw('MIN(fc_min) as min_fc_min')
            ->pluck('min_fc_min')
            ->first();
    }
    private function getFCMax($id)
    {
        return Registros::where('procedimiento_id', $id)
            ->selectRaw('MAX(fc_max) as max_fc_max')
            ->pluck('max_fc_max')
            ->first();
    }
    private function getHoraFCMin($id)
    {
        $fc = $this->fc_min;
        return Registros::where('procedimiento_id', $id)
            ->where('fc_min', $fc)
            ->selectRaw('hora_fc_min as hora_fc_min')
            ->pluck('hora_fc_min')
            ->first();
    }
    private function getHoraFCMax($id)
    {
        $fc = $this->fc_max;
        return Registros::where('procedimiento_id', $id)
            ->where('fc_max', $fc)
            ->selectRaw('hora_fc_max as hora_fc_max')
            ->pluck('hora_fc_max')
            ->first();
    }
    private function getCountLatidos($id)
    {
        return Registros::where('procedimiento_id', $id)
            ->selectRaw('SUM(total_latidos) as total_latidos_est')
            ->pluck('total_latidos_est')
            ->first();
    }
    private function getCountVentricular($id)
    {
        return Registros::where('procedimiento_id', $id)
            ->selectRaw('SUM(vent_total) as total_ventricular')
            ->pluck('total_ventricular')
            ->first();
    }

    private function getCountSupraVentricular($id)
    {
        return Registros::where('procedimiento_id', $id)
            ->selectRaw('SUM(supr_total) as total_supra_ventricular')
            ->pluck('total_supra_ventricular')
            ->first();
    }

    private function contadoresDetallesHolter($id)
    {
        $this->fc_min = $this->getFCMin($id);
        $this->hora_fc_min = $this->getHoraFCMin($id);
        $this->fc_max = $this->getFCMax($id);
        $this->hora_fc_max = $this->getHoraFCMax($id);
        $this->total_latidos = $this->getCountLatidos($id);
        $this->vent_total = $this->getCountVentricular($id);
        $this->supr_total = $this->getCountSupraVentricular($id);
    }

    public function render()
    {
        $id = $this->procedimiento_id;
        $data = $this->getProcedimientoId($id) ?? (object) [];
        $registros = $this->getRegistrosProcById($id);
        $this->contadoresDetallesHolter($id);

        return view('livewire.registros-holter', compact('data', 'registros'));
    }

    public function graficar($id)
    {
        $this->modalReport = true;
        $this->estadoModal = "Informe General de Holter";
        $this->dataGrafico_fcMin($id);
    }

    private function dataGrafico_fcMin($holter_id)
    {
        $this->procedimiento_id = $holter_id;
        $this->datosGrafico = Registros::where('procedimiento_id', $this->procedimiento_id)
            ->orderBy('hora')
            ->get()
            ->map(function ($registro) {
                return [
                    'hora' => $registro->hora,   //->format('H:i'),
                    'fc_min' => $registro->fc_min,
                    'fc_max' => $registro->fc_max,
                    'fc_medio' => $registro->fc_medio,
                    'total_latidos' => $registro->total_latidos,
                    'vent_total' => $registro->vent_total,
                    'supr_total' => $registro->supr_total,
                ];
            });
    }

    public function cerrar()
    {
        $this->modalReport = false;
    }
}
