<?php

namespace App\Livewire;

use Livewire\Component;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use App\Models\Dispositivos;

class EquiposIndex extends Component
{
    public $listadoDispositivos = [];
    public $dispositivoSeleccionado = false;
    public $modal = false;
    public $estadoModal;
    public $datosPaciente;
    public $id;
    public $modalDelete = false;
    public $dispositivoEliminar;
    public $search;

    #[Validate('required', as: 'numero de serie',  message: 'El :attribute es obligatorio')]
    public $numero_serie;
    #[Validate('required', as: 'modelo',  message: 'El :attribute es obligatorio')]
    public $modelo;
    #[Validate('required', as: 'fabricante',  message: 'El :attribute es obligatorio')]
    public $fabricante;
    #[Validate('required', as: 'estado',  message: 'El :attribute es obligatorio')]
    public $estado;

    public function mount()
    {
        $this->cargarDispositivos();
    }
    public function cargarDispositivos()
    {
        $this->listadoDispositivos = Dispositivos::all();
    }

    public function render()
    {
        return view('livewire.dispositivos', ['listadoDispositivos', $this->listadoDispositivos]);
    }

    public function buscarDispositivo()
    {
        Log::info('Valor de search:', ['search' => $this->search]);
        if ($this->search) {
            $this->listadoDispositivos = Dispositivos::where('numero_serie', 'like', '%' . $this->search . '%')
                ->orWhere('modelo', 'like', '%' . $this->search . '%')
                ->orWhere('fabricante', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->cargarDispositivos();
        }
    }
    public function editar($id)
    {
        $this->id = $id;
        $this->modal = true;
        $this->estadoModal = "Editar Datos del Dispositivo";
        $datosdispositivo = dispositivos::find($id);
        $this->modelo = strtoupper($datosdispositivo['modelo']);
        $this->fabricante = strtoupper($datosdispositivo['fabricante']);
        $this->numero_serie = strtoupper($datosdispositivo['numero_serie']);
        $this->estado = $datosdispositivo['estado'];
    }

    public function cerrar()
    {
        $this->reset(['modal', 'modalDelete']);
    }
    public function creacion()
    {
        $this->reset();
        $this->estadoModal = "Crear Nuevo Dispositivo";
        $this->modal = true;
    }

    public function crearDispositivos()
    {
        $this->validate();

        $dispositivo = new dispositivos();
        $datosdispositivo = [
            'id' => $this->id,
            'modelo' => strtoupper($this->modelo),
            'fabricante' => strtoupper($this->fabricante),
            'numero_serie' => strtoupper($this->numero_serie),
            'estado' => $this->estado,
        ];

        if ($dispositivo::updateOrCreate(
            ['id' => $datosdispositivo['id']],
            $datosdispositivo
        )) {
            $this->reset();
            $this->cargarDispositivos();
            $this->dispatch('DispositivoCreado', type: 'success', title: 'Registro exitoso', text: 'El dispositivo se ha guardado correctamente');
        }
    }

    public function confirmarEliminar($id)
    {
        $this->modalDelete = true;
        $this->dispositivoEliminar = dispositivos::find($id);
        $this->id = $id;
    }

    public function eliminar($id)
    {
        if (!$id == $this->id) return;
        try {
            if (dispositivos::destroy($this->id)) {
                $this->reset();
                $this->dispatch('DispositivoEliminado', type: 'success', title: 'Eliminado', text: 'El dispositivo se ha eliminado correctamente');
            }
        } catch (ValidationException $e) {
            $this->dispatch('DispositivoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Error de consulta en la base de datos: ' . $e->getMessage());
            $this->dispatch('DispositivoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        } catch (Exception $e) {
            Log::error('Error en el servidor: ' . $e->getMessage());
            $this->dispatch('DispositivoError', type: 'error', title: 'Ha ocurrido un error', text: $e->getMessage());
        }
    }
}
