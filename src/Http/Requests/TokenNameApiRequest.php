<?php

namespace TheBachtiarz\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TheBachtiarz\Auth\Interfaces\Validator\AuthValidatorInterface;

class TokenNameApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return AuthValidatorInterface::AUTH_TOKEN_NAME_RULES;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return AuthValidatorInterface::AUTH_TOKEN_NAME_MESSAGES;
    }
}
