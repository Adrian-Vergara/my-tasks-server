<?php

namespace App\Http\Requests\User;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRole extends FormRequest
{
    private $id;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->id = $this->route('id');
        $this->merge([
            "id" => $this->id
        ]);
    }

    public function rules()
    {
        return array(
            'name' => array('nullable', 'max:70', Rule::unique('roles', 'name')->ignore($this->id)->whereNull('deleted_at'))
        );
    }

    public function messages()
    {
        return array(
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
