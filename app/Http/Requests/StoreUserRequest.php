<?php

namespace App\Http\Requests;

use App\Domain\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:255',
            'confirm_password' => 'required|same:password',
            'birthday' => 'required|date',
            'phone' => 'required|string|max:12|min:11',
            'gender' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'identity_card' => 'required|string|max:12|unique:profiles',
            'role' => [Rule::enum(UserRoles::class), 'nullable', 'string', 'max:255']
        ];
    }
}
