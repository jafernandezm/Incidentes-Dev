<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public $resultado;
    public function __construct($resultado)
    {
        $this->resultado = $resultado;
    }
    public function render()
    {
        return view('components.resultado.card');
    }
}
