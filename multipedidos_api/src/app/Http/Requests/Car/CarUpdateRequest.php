<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarUpdateRequest extends FormRequest
{
    /**
     *
     * Adiciona parâmetros da rota na validação.
     *
     * @return array
     */
    public function all($keys = null)
    {
        return array_replace_recursive(
            parent::all(),
            $this->route()->parameters()
        );
    }

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
        $carId = $this->route('id') ?? 0;

        return [
            'id' => 'required|integer|' . Rule::exists('cars', 'id')->withoutTrashed(),
            'model' => 'required|string',
            'brand' => 'required|string',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages() : array
    {
        return [
            'id.required' => 'O campo id é obrigatório',
            'id.integer' => 'O campo id deve ser um número inteiro',
            'id.exists' => 'O valor informado não consta em nosso banco de dados',
            'model.required' => 'O Modelo é obrigatório!',
            'brand.required' => 'A Marca é obrigatória!',
            'year.required' => 'O Ano é obrigatório!',
            'year.integer' => 'O Ano deve ser um número inteiro!',
            'color.required' => 'A Cor é obrigatória!',
        ];
    }
}
