<?php

namespace TheBachtiarz\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TheBachtiarz\Auth\Interfaces\Validator\AuthValidatorInterface;

class UpdatePasswordGuestRequest extends FormRequest
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
        return tbauthconfig('user_auth_identity_method') === 'email'
            ? AuthValidatorInterface::AUTH_LOGIN_EMAIL_RULES
            : AuthValidatorInterface::AUTH_LOGIN_USERNAME_RULES;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return tbauthconfig('user_auth_identity_method') === 'email'
            ? AuthValidatorInterface::AUTH_LOGIN_EMAIL_MESSAGES
            : AuthValidatorInterface::AUTH_LOGIN_USERNAME_MESSAGES;
    }
}
