<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordChangeRequest extends FormRequest
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
            'current_password' => 'required|current_password',
            'new_password' => ['required', Password::min(8)->max(24)->letters()->numbers()],
            'new_password_confirm' => 'nullable|required_with:new_password|same:new_password',
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => '元パスワード',
            'new_password' => '新しいパスワード',
            'new_password_confirm' => '新しいパスワード',
        ];
    }
}
