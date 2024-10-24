<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResultadoEscaneo;
use App\Models\Filtracion;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Escaneo extends Model
{
    use HasFactory;
    use HasUuids;
    protected $fillable = ['url','tipo','fecha','resultado'
    ,'detalles'];
    public function resultados()
    {   
        // Relación uno a muchos
        return $this->hasMany(ResultadoEscaneo::class, 'escaneo_id');
    }

    //crear la relacioneentre datos filtrados y escaneos
    public function resultado_filtrado(){
        return $this->hasMany(Filtracion::class, 'escaneo_id');
    }
    //   // Generar automáticamente un UUID para 'id' al crear el modelo
    //   protected static function booted()
    //   {
    //       static::creating(function ($model) {
    //           if (empty($model->id)) {
    //               $model->id = (string) Str::uuid();
    //           }
    //       });
    //   }
}
