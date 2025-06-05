<?php

namespace App\Http\Controllers;

use App\Models\especialistas;
use Illuminate\Http\Request;

class EspecialistaController extends Controller
{
    // Muestra la lista de especialistas
    public function index()
    {
        $especialistas = especialistas::all(); // Obtiene todos los especialistas
        return view('especialistas.index', compact('especialistas')); // Retorna la vista con los datos
    }

    // Almacena un nuevo especialista
    public function store(Request $request)
    {
        // Valida los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'identification_no' => 'required|string|unique:especialistas,identification_no|max:255',
            'specialty' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'contact_info' => 'required|string|max:255',
        ]);

        // Crea el especialista
        especialistas::create($request->all());

        // Redirige a la vista con un mensaje de éxito
        return redirect()->route('especialistas.index')->with('message', 'Especialista creado exitosamente.');
    }
}
