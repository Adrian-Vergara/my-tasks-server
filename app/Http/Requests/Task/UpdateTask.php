<?php

namespace App\Http\Requests\Task;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTask extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => array('nullable', 'max:150'),
            'description' => array('nullable', 'max:5000'),
            'execution_date' => array('nullable', 'date'),
        ];
    }

    public function messages()
    {
        return array(
            'name.max' => Message::$error_length_field,
            'description.max' => Message::$error_length_field,
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
