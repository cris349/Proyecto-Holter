<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;

    protected $table = 'pacientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombres',
        'apellidos',
        'identificacion',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'celular',
        'estado_pcte',
        'user_id'
    ];

    public function procedimientos()
    {
        return $this->hasMany(Procedimientos::class, 'paciente_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
