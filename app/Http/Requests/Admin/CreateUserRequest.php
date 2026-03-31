<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,moderator,user',
            'department' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Introduce un correo válido.',
        'email.unique' => 'Ese correo ya está registrado.',
        'role.required' => 'El rol es obligatorio.',
        'role.in' => 'Rol no válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];
    }
}