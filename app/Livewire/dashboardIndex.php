<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardIndex extends Component
{
    public $listadoDispositivos = [];
    public $user;
    public function render()
    {
        $this->user = Auth::user();
        $role = $this->user->role;
        return view('livewire.dashboard')->with('role', $role);
    }

    public function mount()
    {
        Log::info("Dashboard cargado por usuario con rol: " . auth()->user()->role);
    }
}
