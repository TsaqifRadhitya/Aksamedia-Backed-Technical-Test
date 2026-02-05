<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginRequest",
    title: "Login Request",
    description: "Body request untuk login",
    required: ["username", "password"],
    properties: [
        new OA\Property(
            property: "username",
            type: "string",
            example: "admin",
            description: "Username pengguna"
        ),
        new OA\Property(
            property: "password",
            type: "string",
            format: "password",
            minLength: 8,
            example: "password123",
            description: "Password pengguna"
        )
    ]
)]
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required'],
            'password' => ['required','min:8']
        ];
    }
}
