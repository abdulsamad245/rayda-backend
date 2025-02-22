<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "RegisterRequest",
    required: ["name", "email", "password", "password_confirmation"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 255, description: "User's name"),
        new OA\Property(property: "email", type: "string", format: "email", description: "User's email"),
        new OA\Property(property: "password", type: "string", minLength: 8, description: "Strong password"),
        new OA\Property(property: "password_confirmation", type: "string", description: "Password confirmation"),
    ]
)]
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
                'confirmed',
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
        ];
    }
}
