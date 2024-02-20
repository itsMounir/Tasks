<?php

namespace App\Traits ;

use Illuminate\Http\Exceptions\HttpResponseException;
use illuminate\Contracts\Validation\Validator;

trait FailedValidationResponse
{
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'message' => 'validation failed',
            'errors' => $validator->errors(),
        ]));
    }
}
