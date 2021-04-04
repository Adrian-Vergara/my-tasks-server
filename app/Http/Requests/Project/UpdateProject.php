<?php

namespace App\Http\Requests\Project;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProject extends FormRequest
{
    /*
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return Auth::check();
    }

    public function prepareForValidation()
    {
        $this->merge([
            "id" => $this->route('project')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => array('required', Rule::exists('projects', 'id')->whereNull('deleted_at')),
            'name' => array('nullable', 'max:150'),
            'description' => array('nullable', 'max:5000'),
            'end_date' => array('nullable', 'date'),
        ];
    }

    public function messages()
    {
        return array(
            'id.required' => Message::$empty_field,
            'id.exists' => Message::$error_exists_male,
            'name.max' => Message::$error_length_field,
            'description.max' => Message::$error_length_field,
            'end_date.date' => Message::$error_date,
        );
    }

    public function attributes()
    {
        return array(
            'id' => 'proyecto',
            'name' => 'proyecto',
            'description' => 'descripción',
            'end_date' => 'fecha de finalización'
        );
    }
}
