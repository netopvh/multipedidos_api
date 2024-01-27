<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePasswordRequest extends FormRequest
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
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|unique:users,email,' . $userId . ',id'
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
            'password.required' => 'O campo senha é obrigatório',
            'password.string' => 'O campo senha deve ser uma string',
            'password.min' => 'O campo senha deve ter no mínimo 8 caracteres',
            'password.confirmed' => 'O campo senha não confere com a confirmação',
            'email.required' => 'O campo e-mail é obrigatório',
            'email.email' => 'O campo e-mail deve ser um e-mail válido',
            'email.unique' => 'O e-mail informado já está cadastrado',
        ];
    }
}
