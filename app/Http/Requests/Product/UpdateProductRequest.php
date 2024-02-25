<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationResponse;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => ['required','unique:products,name'],
            'price' => ['required'],
            'category_id' => 'required',
            'images' => ['array','present','min:2'],
            'images.*' => [
                'image',
                'required',
                'mimes:png,jpg,gif',
                'max:2764',
                Rule::dimensions()->maxWidth(3840)->maxHeight(2160),
            ]
        ];
    }
}
