<?php

namespace App\Http\Requests\User;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePassword extends FormRequest
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
            'old_password' => array('required', 'max:20', 'min:6'),
            'new_password' => array('required', 'max:20', 'min:6'),
        ];
    }

    public function messages()
    {
        return array(
            'old_password.required' => Message::$empty_field,
            'old_password.max' => Message::$error_length_field,
            'old_password.min' => Message::$error_length_field_min,

            'new_password.required' => Message::$empty_field,
            'new_password.max' => Message::$error_length_field,
            'new_password.min' => Message::$error_length_field_min,
        );
    }

    public function attributes()
    {
        return array(
            'old_password' => 'contraseña actual',
            'new_password' => 'nueva contraseña'
        );
    }
}
