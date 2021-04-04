<?php

namespace App\Http\Requests\Auth;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;

class AuthUser extends FormRequest
{
    /*
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => array('required', 'max:80', 'email'),
            'password' => array('required', 'max:20')
        ];
    }

    public function messages()
    {
        return array(
            'email.required' => Message::$empty_field,
            'email.max' => Message::$error_length_field,
            'email.email' => Message::$error_email,
            'password.required' => Message::$empty_field,
            'password.max' => Message::$error_length_field,
        );
    }

    public function attributes()
    {
        return array(
            'email' => 'e-mail',
            'password' => 'contraseña'
        );
    }
}
