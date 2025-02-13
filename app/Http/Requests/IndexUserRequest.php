<?php

namespace App\Http\Requests;

use App\Exceptions\ResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'count' => ['integer', 'min:1']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $message = 'Validation failed';
        throw new ResponseException(422, $message, $errors);
    }
}
