<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'celular',
        'direccion',
        'ci',
        //'especialidad'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
