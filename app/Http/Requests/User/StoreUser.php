<?php

namespace App\Http\Requests\User;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
            'name' => array('required', 'max:60'),
            'last_name' => array('nullable', 'max:60'),
            'phone' => array('nullable', 'max:14'),
            'address' => array('nullable', 'max:60'),
            'email' => array('required', 'max:80', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')),
            'password' => array('required', 'max:20', 'min:6'),
            'role_id' => array('required', 'max:10', Rule::exists('roles', 'id')->whereNull('deleted_at')),
        ];
    }

    public function messages()
    {
        return array(
            'name.required' => Message::$empty_field,
            'name.max' => Message::$error_length_field,

            'last_name.max' => Message::$error_length_field,
            'phone.max' => Message::$error_length_field,
            'address.max' => Message::$error_length_field,

            'email.required' => Message::$empty_field,
            'email.max' => Message::$error_length_field,
            'email.email' => Message::$error_email,
            'email.unique' => Message::$error_unique_male,

            'password.required' => Message::$empty_field,
            'password.max' => Message::$error_length_field,
            'password.min' => Message::$error_length_field_min,

            'role_id.required' => Message::$empty_field,
            'role_id.max' => Message::$error_length_field,
            'role_id.exists' => Message::$error_exists_male,
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'nombre',
            'last_name' => 'apellido',
            'phone' => 'número de teléfono',
            'address' => 'dirección',
            'email' => 'e-mail',
            'password' => 'contraseña',
            'role_id' => 'rol'
        );
    }
}
