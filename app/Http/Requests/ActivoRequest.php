<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'protocol' => 'required|in:http,https',
            'url' => ['required', 'string', function ($attribute, $value, $fail) {
                if (strpos($value, 'https://') === 0) {
                    $fail('La URL no puede comenzar con https://');
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'protocol.required' => 'El campo de protocolo es obligatorio.',
            'protocol.in' => 'El protocolo seleccionado no es válido.',
            'url.required' => 'El campo de URL es obligatorio.',
            'url.string' => 'La URL debe ser una cadena de texto válida.',
        ];
    }
}
