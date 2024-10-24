<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Escaneo;

class ResultadoEscaneo extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'data',
        'detalle',
    ];


    // public function escaneo()
    // {
    //     //un escaneo tiene muchos resultados
    //     return $this->belongsTo(Escaneos::class, 'id');
    // }

    public function escaneo()
    {
        // Relación uno a muchos
        return $this->belongsTo(Escaneo::class, 'escaneo_id');
    }
}
