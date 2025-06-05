<?php

namespace App\Livewire;

use App\Http\Controllers\ProcedimientosController;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Dispositivos;
use App\Models\Especialistas;
use App\Models\Pacientes;
use App\Models\Procedimientos;
use App\Models\Registros;

class ProcedimientosIndex extends Component
{
    use WithFileUploads;
    public $listaPacientes = [];
    public $listaEspecialistas = [];
    public $listaDispositivos = [];

    public $datosPaciente;
    public $datosEspecialista;
    public $datosDispositivo;
    public $search;

    #[Validate('required', as: 'fecha_ini',  message: 'La :attribute es obligatorio')]
    public $fecha_ini;
    #[Validate('required', as: 'fecha_fin',  message: 'La :attribute son obligatorio')]
    public $fecha_fin;
    #[Validate('required', as: 'duracion',  message: 'La :attribute es obligatorio')]
    public $duracion;
    #[Validate('required', as: 'paciente_id',  message: 'El :attribute es obligatorio')]
    public $paciente_id;
    #[Validate('required', as: 'especialista_id',  message: 'El :attribute es obligatorio')]
    public $especialista_id;
    #[Validate('required', as: 'dispositivo_id',  message: 'El :attribute es obligatorio')]
    public $dispositivo_id;
    #[Validate('required', as: 'estado_proc',  message: 'El :attribute es obligatorio')]
    public $estado_proc;

    public $edad = 0;
    public $observaciones;

    public $listadoProcedimientos;
    public $modalNuevo = false;
    public $modalDelete = false;
    public $modalRegistros = false;
    public $modalRegistrosExcel = false;
    public $estadoModal;
    public $datosProcediminto;
    public $id;
    public $procedimientoEliminar;

    public $dataProcedimiento = [];
    public $csv_file;
    public $hora, $fc_min, $hora_fc_min, $fc_max, $hora_fc_max;
    public $fc_medio, $total_latidos, $vent_total, $supr_total;
    public $cerrarCaso = false;


    public function mount()
    {
        $this->listarProcedimientos();
    }
    public function listarProcedimientos()
    {
        $this->listadoProcedimientos = Procedimientos::with(['paciente', 'dispositivo'])
            ->orderBy('id')
            ->get();
    }
    public function render()
    {
        return view('livewire.procedimientos', ['listadoProcedimientos', $this->listadoProcedimientos]);
    }

    public function buscarProcedimiento()
    {
        Log::info('Valor de search:', ['search' => $this->search]);
        if (!$this->search) {
            $this->listarProcedimientos();
            return;
        }

        $this->listadoProcedimientos = Procedimientos::with(['paciente', 'dispositivo'])
            ->where(function ($query) {
                $query->where('fecha_ini', 'like', '%' . $this->search . '%')
                    ->orWhereHas('paciente', function ($q) {
                        $q->where('nombres', 'like', '%' . $this->search . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                            ->orWhere('identificacion', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('dispositivo', function ($q) {
                        $q->where('numero_serie', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('id')
            ->get();
    }


    public function NuevoProcedimiento()
    {
        $procedimientos = new ProcedimientosController();
        $lista = $procedimientos->NuevoProcedimiento();
        foreach ($lista['pacientes'] as $key => $pcte) {
            $this->listaPacientes[$key] = $pcte;
        }
        foreach ($lista['especialistas'] as $key => $esp) {
            $this->listaEspecialistas[$key] = $esp;
        }
        foreach ($lista['dispositivos'] as $key => $disp) {
            $this->listaDispositivos[$key] = $disp;
        }
    }

    public function datosSeleccion($id, $model)
    {
        switch ($model) {
            case 'pacientes':
                $this->datosPaciente = Pacientes::find($id);
                $n = $this->datosPaciente->fecha_nacimiento;
                $this->calcEdadPcte($n);
                break;
            case 'especialistas':
                $this->datosEspecialista = Especialistas::find($id);
                break;
            case 'dispositivos':
                $this->datosDispositivo = Dispositivos::find($id);
                break;
        }
    }

    private function calcEdadPcte($nac)
    {
        $fechaNacimiento = Carbon::parse($nac);
        $this->edad = $fechaNacimiento->age;
    }

    public function cerrar()
    {
        $this->modalNuevo = false;
        $this->modalDelete = false;
        $this->modalRegistros = false;
        $this->modalRegistrosExcel = false;
        $this->reset(['csv_file']);
    }

    public function creacion()
    {
        $this->estadoModal = "Crear Nuevo Procedimiento";
        $this->NuevoProcedimiento();
        $this->modalNuevo = true;
    }

    public function confirmarEliminar($id)
    {
        $this->modalDelete = true;
        $this->procedimientoEliminar = procedimientos::find($id);
        $this->id = $id;
    }

    public function eliminar($id)
    {
        if (!$id == $this->id) return;
        try {
            if (Procedimientos::destroy($this->id)) {
                $this->dispatch('ProcedimientoEliminado', type: 'success', title: 'Eliminado', text: 'El procedimiento se ha eliminado correctamente');
            }
        } catch (ValidationException $e) {
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }

    private function actualizarDispositivos($id, $estado)
    {
        try {
            $dispositivo = Dispositivos::findOrFail($id);
            $dispositivo->estado = $this->obtenerEstadoDispositivo($estado);
            $dispositivo->save();
            return true;
        } catch (\Exception $e) {
            Log::error("Error al actualizar el dispositivo ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    private function obtenerEstadoDispositivo($estado)
    {
        return ($estado === 'abrir') ? 'En Uso' : 'Operativo';
    }

    public function crearProcedimiento()
    {
        if (!$this->validate()) {
            return;
        }

        try {
            $procedimiento = Procedimientos::updateOrCreate(
                ['id' => $this->id],
                [
                    'fecha_ini' => $this->fecha_ini,
                    'fecha_fin' => $this->fecha_fin,
                    'duracion' => $this->duracion,
                    'edad' => $this->edad,
                    'paciente_id' => $this->paciente_id,
                    'especialista_id' => $this->especialista_id,
                    'dispositivo_id' => $this->dispositivo_id,
                    'estado_proc' => $this->estado_proc,
                    'observaciones' => $this->observaciones,
                ]
            );

            if (in_array($this->estado_proc, ['ABIERTO', 'CERRADO', 'CANCELADO'])) {
                $estado = $this->estado_proc === 'ABIERTO' ? 'abrir' : 'cerrar';
                if (!$this->actualizarDispositivos($this->dispositivo_id, $estado)) {
                    $this->dispatch('EspecialistaError', type: 'error', title: 'Error', text: 'Error al actualizar los dispositivos');
                }
            }

            $this->reset();
            $this->listarProcedimientos();
            $this->dispatch('ProcedimientoCreado', type: 'success', title: 'Registro exitoso', text: 'El procedimiento se ha guardado correctamente');
        } catch (ValidationException $e) {
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'Error en la base de datos');
        } catch (\Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'Error en el servidor');
        }
    }

    public function editar($id)
    {
        $this->id = $id;
        $this->modalNuevo = true;
        $this->estadoModal = "Editar Datos del Procedimiento";

        $this->NuevoProcedimiento();
        $datosProcediminto = Procedimientos::find($id);
        $this->fecha_ini = $datosProcediminto['fecha_ini'];
        $this->fecha_fin = $datosProcediminto['fecha_fin'];
        $this->duracion = $datosProcediminto['duracion'];
        $this->edad = $datosProcediminto['edad'];
        $this->paciente_id = $datosProcediminto['paciente_id'];
        $this->especialista_id = $datosProcediminto['especialista_id'];
        $this->dispositivo_id = $datosProcediminto['dispositivo_id'];
        $this->observaciones = $datosProcediminto['observaciones'];
        $this->estado_proc = $datosProcediminto['estado_proc'];
    }

    public function registrosHolter($id)
    {
        $this->id = $id;
        $this->modalRegistros = true;
        $this->estadoModal = "Registrar Datos del Holter";

        $this->dataProcedimiento = (new RegistrosHolterIndex())->getProcedimientoId($id);

        if (!$this->dataProcedimiento) {
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'No se encontraron datos del procedimiento.');
            return;
        }
    }

    public function registrosHolterExcel($id)
    {
        $this->id = $id;
        $this->modalRegistrosExcel = true;
        $this->estadoModal = "Importar Datos del Holter";

        $this->dataProcedimiento = (new RegistrosHolterIndex())->getProcedimientoId($id);

        if (!$this->dataProcedimiento) {
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'No se encontraron datos del procedimiento.');
            return;
        }
    }
    public function crearRegistrosHolterExcel()
    {
        Log::info('Iniciando lectura del archivo...');
        if (!$this->csv_file) {
            Log::error('No se ha subido ningún archivo.');
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'No se ha subido ningún archivo.');
            return;
        }


        try {
            $filePath = $this->csv_file->getRealPath();
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);

                $columna1 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna2 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna3 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna4 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna5 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna6 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna7 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna8 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna9 = $cellIterator->current()->getValue();
                $cellIterator->next();
                $columna10 = $cellIterator->current()->getValue();

                // Guardar en la base de datos
                Registros::create([
                    'procedimiento_id' => $this->id,
                    'hora' => $columna2,
                    'fc_min' => $columna3,
                    'hora_fc_min' => $columna4,
                    'fc_max' => $columna5,
                    'hora_fc_max' => $columna6,
                    'fc_medio' => $columna7,
                    'total_latidos' => $columna8,
                    'vent_total' => $columna9,
                    'supr_total' => $columna10,
                ]);
            }
            // Eliminar el archivo temporal
            unlink($filePath);
        } catch (Exception $e) {
            Log::error('Error al leer el archivo: ' . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Error', text: 'Ocurrió un error al leer el archivo.');
        }
        $this->cerrarCaso = true;
        Log::info('Archivo importado correctamente...');
        $this->listarProcedimientos();
        $this->dispatch('ProcedimientoCreado', type: 'success', title: 'Registro exitoso', text: 'Archivo importado correctamente.');
    }
    public function crearRegistrosHolter()
    {
        $validatedData = $this->validate([
            'hora' => 'required|date_format:H:i',
            'fc_min' => 'required|numeric|min:30|max:200',
            'hora_fc_min' => 'required|date_format:H:i',
            'fc_max' => 'required|numeric|min:30|max:200',
            'hora_fc_max' => 'required|date_format:H:i',
            'fc_medio' => 'required|numeric|min:30|max:200',
            'total_latidos' => 'required|numeric|min:1000',
            'vent_total' => 'required|numeric|min:0',
            'supr_total' => 'required|numeric|min:0',
        ]);

        try {
            Registros::create([
                'procedimiento_id' => $this->id,
                'hora' => $this->hora,
                'fc_min' => $this->fc_min,
                'hora_fc_min' => $this->hora_fc_min,
                'fc_max' => $this->fc_max,
                'hora_fc_max' => $this->hora_fc_max,
                'fc_medio' => $this->fc_medio,
                'total_latidos' => $this->total_latidos,
                'vent_total' => $this->vent_total,
                'supr_total' => $this->supr_total,
            ]);
            $this->reset(['hora', 'fc_min', 'hora_fc_min', 'fc_max', 'hora_fc_max', 'fc_medio', 'total_latidos', 'vent_total', 'supr_total']);
            $this->listarProcedimientos();
            $this->dispatch('ProcedimientoCreado', type: 'success', title: 'Registro exitoso', text: 'Registro de Holter creado exitosamente.');
        } catch (\Exception $e) {
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }

    public function cerrarProcedimiento($id)
    {
        try {

            $proc = Procedimientos::findOrFail($id);
            $idDisp = $proc->dispositivo_id;
            $proc->estado_proc = "CERRADO";
            $proc->save();
            $this->actualizarDispositivos($idDisp, 'Operativo');
            $this->listarProcedimientos();
            $this->dispatch('ProcedimientoCreado', type: 'success', title: 'Registro exitoso', text: 'Procedimiento Cerrado Exitosamente.');
            $this->modalRegistrosExcel = false;
        } catch (Exception $e) {
            Log::error("Error al actualizar el procedimiento ID {$id}: " . $e->getMessage());
            $this->dispatch('ProcedimientoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }
}
