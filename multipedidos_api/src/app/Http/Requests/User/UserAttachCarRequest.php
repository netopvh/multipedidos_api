<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAttachCarRequest extends FormRequest
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
        $userId = $this->route('id') ?? 0;

        return [
            'id' => 'required|integer|exists:users,id',
            'car_id' => 'required|integer|' . Rule::exists('cars', 'id')->withoutTrashed()
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
            'id.exists' => 'O valor informado para o campo id não existe na base de dados',
            'car_id.required' => 'O campo carro é obrigatório',
            'car_id.integer' => 'O campo carro deve ser um número inteiro',
            'car_id.exists' => 'O valor informado para o campo carro não existe na base de dados'
        ];
    }
}
