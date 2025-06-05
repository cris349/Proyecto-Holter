<?php

namespace App\Livewire;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Especialistas;
use App\Models\User;


class EspecialistaIndex extends Component
{
    public $listadoEspecialistas = [];
    public $modal = false;
    public $estadoModal;
    public $especialista;
    public $modalDelete = false;
    public $EspecialistaEliminar;
    public $id;
    public $updateModal = false;
    public $search;

    #[Validate('required', as: 'nombre', message: 'El :attribute es obligatorio')]
    public $nombres;

    #[Validate('required', as: 'apellidos', message: 'Los :attribute son obligatorios')]
    public $apellidos;

    #[Validate('required', as: 'correo', message: 'El :attribute es obligatorio ')]
    public $correo;

    #[Validate('required', as: 'contraseña', message: 'La :attribute es obligatoria y debe tener al menos 8 caracteres')]
    public $contrasena;

    #[Validate('required', as: 'identification', message: 'La :attribute es obligatoria')]
    public $identification;

    #[Validate('required', as: 'especialidad', message: 'La :attribute es obligatoria')]
    public $especialidad;

    #[Validate('required', as: 'Contacto', message: 'El :attribute es obligatorio')]
    public $contacto;

    #[Validate('required', as: 'estado_esp', message: 'El :attribute es obligatoria')]
    public $estado_esp;



    public function mount()
    {
        $this->cargarEspecialistas();
    }
    public function cargarEspecialistas()
    {
        $this->listadoEspecialistas = Especialistas::all();
    }

    public function render()
    {
        return view('livewire.especialistas', ['listadoEspecialistas' => $this->listadoEspecialistas]);
    }

    public function buscarEspecialista()
    {
        Log::info('Valor de search:', ['search' => $this->search]);
        if ($this->search) {
            $this->listadoEspecialistas = Especialistas::where('nombres', 'like', '%' . $this->search . '%')
                ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                ->orWhere('identification', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->cargarEspecialistas();
        }
    }

    public function editar($id)
    {
        $this->especialista = $id;
        $this->modal = true;
        $this->updateModal = true;
        $this->estadoModal = "Editar Datos del Especialista";
        $datosEspecialista = especialistas::findOrFail($id);

        $this->nombres = strtoupper($datosEspecialista['nombres']);
        $this->apellidos = strtoupper($datosEspecialista['apellidos']);
        $this->correo = $datosEspecialista['correo'];
        $this->contrasena = $datosEspecialista['contrasena'];
        $this->identification = $datosEspecialista['identification'];
        $this->contacto = $datosEspecialista['contacto'];
        $this->especialidad = $datosEspecialista['especialidad'];
        $this->estado_esp = $datosEspecialista['estado_esp'];
    }

    public function cerrar()
    {
        $this->updateModal = false;
        $this->reset(['modal', 'modalDelete']);
    }

    public function creacion()
    {
        $this->estadoModal = "Crear nuevo especialista";
        $this->modal = true;
        $this->updateModal = false;
    }

    public function crearEspecialistas()
    {
        if ($this->validate()) {
            try {
                $especialista = especialistas::updateOrCreate(
                    ['correo' => $this->correo],
                    [
                        'nombres' => strtoupper($this->nombres),
                        'apellidos' => strtoupper($this->apellidos),
                        'correo' => $this->correo,
                        'contrasena' => $this->contrasena,
                        'identification' => $this->identification,
                        'especialidad' => $this->especialidad,
                        'contacto' => $this->contacto,
                        'estado_esp' => $this->estado_esp,
                    ]
                );

                if (!$this->updateModal) {
                    $newUser = User::firstOrCreate(
                        ['email' => $this->correo],
                        [
                            'name' => strtoupper($this->nombres) . " " . strtoupper($this->apellidos),
                            'password' => Hash::make($this->contrasena),
                            'role' => 'user'
                        ]
                    );
                }

                $this->reset();
                $this->cargarEspecialistas();
                $this->dispatch('EspecialistaCrear', type: 'success', title: 'Registro exitoso', text: 'El especialista se ha guardado correctamente');
            } catch (ValidationException $e) {
                $this->dispatch('EspecialistaError', type: 'error', title: 'Error', text: $e->getMessage());
            } catch (QueryException $e) {
                Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
                $this->dispatch('EspecialistaError', type: 'error', title: 'Error', text: 'Error en la base de datos');
            } catch (Exception $e) {
                Log::error('Error en el servidor: ' . $e->getMessage());
                $this->dispatch('EspecialistaError', type: 'error', title: 'Error', text: 'Error en el servidor');
            }
        }
    }

    public function confirmarEliminar($id)
    {
        $this->modalDelete = true;
        $this->EspecialistaEliminar = especialistas::find($id);
        $this->id = $id;
    }

    public function eliminar($id)
    {
        if (!$id == $this->id) return;
        try {
            if (especialistas::destroy($this->id)) {
                $this->reset();
                $this->dispatch('EspecialistaEliminar', type: 'success', title: 'Eliminado', text: 'El especialista se ha eliminado correctamente');
            }
        } catch (ValidationException $e) {
            $this->dispatch('EspecialistaError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
            $this->dispatch('EspecialistaError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            $this->dispatch('EspecialistaError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }
}
