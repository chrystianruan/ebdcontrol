<?php

namespace App\Http\Requests;

use App\Models\Formation;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Foundation\Http\FormRequest;

class PresencaIndividualRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'codigo' => ['required'],
            'latitude' => ['required'],
        ];
    }

    public function messages() : array {
        return [
            'codigo.required' => 'Código é obrigatório.',
            'latitude.required' => 'Localização é obrigatória.',
        ];
    }
}
