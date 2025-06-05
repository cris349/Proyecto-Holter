<?php

namespace App\Livewire;

use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Exception;
use App\Models\Pacientes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PacientesIndex extends Component
{

    #[Validate('required', as: 'nombree',  message: 'El :attribute es obligatorio')]
    public $nombres;
    #[Validate('required', as: 'apellidos',  message: 'Los :attribute son obligatorio')]
    public $apellidos;
    #[Validate('required', as: 'número identificacion',  message: 'El :attribute es obligatorio')]
    public $identificacion;
    #[Validate('required', as: 'celular',  message: 'El :attribute es obligatorio')]
    public $celular;
    #[Validate('required', as: 'sexo',  message: 'El :attribute es obligatorio')]
    public $sexo;
    #[Validate('required', as: 'dirección',  message: 'La :attribute es obligatorio')]
    public $direccion;
    #[Validate('required', as: 'fecha de nacimiento',  message: 'La :attribute es obligatorio')]
    public $fecha_nacimiento;
    #[Validate('required', as: 'estado_pcte',  message: 'El :attribute es obligatorio')]
    public $estado_pcte;


    public $listadoPacientes = [];
    public $modal = false;
    public $estadoModal;
    public $datosPaciente;
    public $id;
    public $modalDelete = false;
    public $pacienteEliminar;
    public $search;
    public $modalUpdate = false;

    public function mount()
    {
        $this->cargarPacientes();
    }
    public function cargarPacientes()
    {
        $this->listadoPacientes = Pacientes::all();
    }

    public function render()
    {
        return view('livewire.pacientes', ['listadoPacientes', $this->listadoPacientes]);
    }

    public function buscarPaciente()
    {
        //Log::info('Valor de search:', ['search' => $this->search]);
        if ($this->search) {
            $this->listadoPacientes = Pacientes::where('nombres', 'like', '%' . $this->search . '%')
                ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                ->orWhere('identificacion', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->cargarPacientes();
        }
    }
    public function editar($id)
    {
        $this->id = $id;
        $this->modal = true;
        $this->modalUpdate = true;
        $this->estadoModal = "Editar Datos del Paciente";
        $datosPaciente = pacientes::find($id);
        $this->nombres = strtoupper($datosPaciente['nombres']);
        $this->apellidos = strtoupper($datosPaciente['apellidos']);
        $this->identificacion = $datosPaciente['identificacion'];
        $this->celular = $datosPaciente['celular'];
        $this->sexo = $datosPaciente['genero'];
        $this->direccion = $datosPaciente['direccion'];
        $this->fecha_nacimiento  = $datosPaciente['fecha_nacimiento'];
        $this->estado_pcte  = $datosPaciente['estado_pcte'];
    }

    public function cerrar()
    {
        $this->modal = false;
        $this->modalUpdate = false;
        $this->modalDelete = false;
    }
    public function creacion()
    {
        $this->estadoModal = "Crear Nuevo Paciente";
        $this->modalUpdate = false;
        $this->modal = true;
    }

    public function calcularEdad($id)
    {
        $fechaNacimiento = Carbon::parse($id);
        return $fechaNacimiento->age;
    }


    public function crearPaciente()
    {
        if ($this->validate()) {
            try {

                if (!$this->modalUpdate) {
                    $newUser = User::firstOrCreate(
                        ['email' => $this->identificacion . '@holtersapp.com'],
                        [
                            'name' => strtoupper($this->nombres) . " " . strtoupper($this->apellidos),
                            'password' => Hash::make($this->identificacion),
                            'role' => 'cliente'
                        ]
                    );
                    $idUser = $newUser->id;
                }

                $paciente = new pacientes;
                $datosPaciente['id'] = $this->id;
                $datosPaciente['nombres'] = strtoupper($this->nombres);
                $datosPaciente['apellidos'] = strtoupper($this->apellidos);
                $datosPaciente['identificacion'] = $this->identificacion;
                $datosPaciente['celular'] = $this->celular;
                $datosPaciente['genero'] = $this->sexo;
                $datosPaciente['direccion'] = $this->direccion;
                $datosPaciente['fecha_nacimiento'] = $this->fecha_nacimiento;
                $datosPaciente['estado_pcte'] = $this->estado_pcte;
                if (!$this->modalUpdate) {
                    $datosPaciente['user_id'] = $idUser;
                }
                if ($paciente::updateOrCreate(
                    [
                        'id' => $datosPaciente['id']
                    ],
                    $datosPaciente
                )) {
                    $this->reset();
                    $this->cargarPacientes();
                    $this->dispatch('PacienteCreado', type: 'success', title: 'Registro exitoso', text: 'El paciente se ha guardado correctamente');
                }
            } catch (ValidationException $e) {
                $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
            } catch (QueryException $e) {
                Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
                $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
            } catch (Exception $e) {
                Log::error('Error en el servidor: ' . $e->getMessage());
                $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
            }
        }
    }

    public function confirmarEliminar($id)
    {
        $this->modalDelete = true;
        $this->pacienteEliminar = pacientes::find($id);
        $this->id = $id;
    }

    public function eliminar($id)
    {
        if (!$id == $this->id) return;
        try {
            if (pacientes::destroy($this->id)) {
                $this->reset();
                $this->dispatch('PacienteEliminado', type: 'success', title: 'Eliminado', text: 'El paciente se ha eliminado correctamente');
            }
        } catch (ValidationException $e) {
            $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
            $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            $this->dispatch('PacienteError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }
}
