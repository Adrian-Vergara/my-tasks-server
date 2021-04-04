<?php

namespace App\Http\Requests\User;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRole extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return array(
            'name' => array('required', 'max:70', Rule::unique('roles', 'name')->whereNull('deleted_at'))
        );
    }

    public function messages()
    {
        return array(
            'name.required' => Message::$empty_field,
            'name.max' => Message::$error_length_field,
            'name.unique' => Message::$error_unique_male,
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'rol'
        );
    }
}
