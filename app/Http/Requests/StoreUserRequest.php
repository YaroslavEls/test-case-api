<?php

namespace App\Http\Requests;

use App\Exceptions\ResponseException;
use App\Rules\UkrainianPhoneNumber;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'email' => ['required', 'email:rfc', 'unique:users,email'],
            'phone' => ['required', new UkrainianPhoneNumber, 'unique:users,phone'],
            'position_id' => ['required', 'exists:positions,id'],
            'photo' => ['required', 'image', 'mimes:jpeg,jpg', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'non-unique',
            'phone.unique' => 'non-unique',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if (in_array('non-unique', $errors->all())) {
            $message = 'User with this phone or email already exist';
            throw new ResponseException(409, $message);
        }

        $message = 'Validation failed';
        throw new ResponseException(422, $message, $errors);
    }
}
