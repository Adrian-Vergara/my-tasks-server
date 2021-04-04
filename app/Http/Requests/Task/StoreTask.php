<?php

namespace App\Http\Requests\Task;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTask extends FormRequest
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
            "user_id" => Auth::id(),
            "project_id" => $this->route('id'),
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
            'name' => array('required', 'max:150'),
            'description' => array('nullable', 'max:5000'),
            'execution_date' => array('required', 'date'),
        ];
    }

    public function messages()
    {
        return array(
            'name.required' => Message::$empty_field,
            'name.max' => Message::$error_length_field,

            'description.max' => Message::$error_length_field,

            'execution_date.required' => Message::$empty_field,
            'execution_date.date' => Message::$error_date
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'tarea',
            'description' => 'descripción',
            'execution_date' => 'fecha de ejecución'
        );
    }
}
