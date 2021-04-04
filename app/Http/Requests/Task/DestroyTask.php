<?php

namespace App\Http\Requests\Task;

use App\Entities\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DestroyTask extends FormRequest
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
            "id" => $this->route('task')
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
            'id' => array('required', Rule::exists('tasks', 'id')->whereNull('deleted_at')),
        ];
    }

    public function messages()
    {
        return array(
            'id.required' => Message::$empty_field,
            'id.exists' => Message::$error_exists_destroy_female,
        );
    }

    public function attributes()
    {
        return array(
            'id' => 'tarea'
        );
    }
}
