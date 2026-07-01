<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];

        if (!config('captcha.disable')) {
            $rules['captcha'] = ['required', 'captcha'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'captcha.required' => 'Captcha wajib diisi.',
            'captcha.captcha' => 'Kode captcha salah.',
        ];
    }
}