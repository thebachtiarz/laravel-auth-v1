<?php

namespace TheBachtiarz\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TheBachtiarz\Auth\Interfaces\Validator\AuthValidatorInterface;

class UpdatePasswordAuthRequest extends FormRequest
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
        return array_merge(
            AuthValidatorInterface::AUTH_PASSWORD_OLD_RULES,
            AuthValidatorInterface::AUTH_PASSWORD_RULES
        );
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(
            AuthValidatorInterface::AUTH_PASSWORD_OLD_MESSAGES,
            AuthValidatorInterface::AUTH_PASSWORD_MESSAGES
        );
    }
}
