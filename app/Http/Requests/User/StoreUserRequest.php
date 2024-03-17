<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationResponse;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    use FailedValidationResponse;
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
            'name' => ['required'],
            'email'=> ['required','email','unique:users,email'],
            'password' => ['required','confirmed'],
            'image' => [
                'image',
                'required',
                'mimes:png,jpg,gif',
                'max:2764',
                Rule::dimensions()->maxWidth(3840)->maxHeight(2160),
            ]
        ];
    }


}
