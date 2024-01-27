<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        return [
            'model' => 'required|string',
            'brand' => 'required|string',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages() : array
    {
        return [
            'model.required' => 'O Modelo é obrigatório!',
            'brand.required' => 'A Marca é obrigatória!',
            'year.required' => 'O Ano é obrigatório!',
            'year.integer' => 'O Ano deve ser um número inteiro!',
            'year.digits' => 'O Ano deve ter 4 dígitos!',
            'year.min' => 'O Ano deve ser maior ou igual a 1900!',
            'year.max' => 'O Ano deve ser menor ou igual a ' . (date('Y') + 1) . '!',
            'color.required' => 'A Cor é obrigatória!',
        ];
    }
}
