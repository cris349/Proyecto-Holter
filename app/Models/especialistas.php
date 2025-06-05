<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialistas extends Model
{
    use HasFactory;

    protected $table = "especialistas";
    protected $primaryKey = "id";

    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'contrasena',
        'identification',
        'especialidad',
        'contacto',
        'estado_esp'
    ];

    public function procedimientos()
    {
        return $this->hasMany(Procedimientos::class, 'especialista_id');
    }
}
