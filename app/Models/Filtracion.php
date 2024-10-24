<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Escaneo;
class Filtracion extends Model
{
    use HasFactory;

  
    protected $fillable = ['consulta', 'tipo', 'filtracion', 'informacion', 'data', 'escaneo_id'];
    public function escaneo()
    {
        return $this->belongsTo(Escaneo::class, 'id');
    }
}
