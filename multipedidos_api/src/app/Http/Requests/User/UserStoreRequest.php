<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
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
            'name.required' => 'O campo nome é obrigatório',
            'name.string' => 'O campo nome deve ser uma string',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'O email informado já está cadastrado',
            'password.required' => 'O campo senha é obrigatório',
            'password.string' => 'O campo senha deve ser uma string',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
            'password.confirmed' => 'O campo senha não confere com a confirmação',
        ];
    }
}
