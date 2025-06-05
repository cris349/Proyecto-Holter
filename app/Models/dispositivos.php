<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivos extends Model
{
    use HasFactory;
    protected $table = "dispositivos";
    protected $primaryKey = "id";
    protected $fillable = [
        'modelo',
        'fabricante',
        'numero_serie',
        'estado',
    ];

    public function procedimientos()
    {
        return $this->hasMany(Procedimientos::class, 'dispositivo_id');
    }
}
