<?php

namespace App\Http\Requests\Project;

use App\Entities\Message;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProject extends FormRequest
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
            "date_now" => Carbon::now()->toDateString(),
            "user_id" => Auth::id()
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
            'description' => array('required', 'max:5000'),
            'start_date' => array('required', 'date', 'after_or_equal:date_now'),
            'end_date' => array('required', 'date', 'after:start_date'),
        ];
    }

    public function messages()
    {
        return array(
            'name.required' => Message::$empty_field,
            'name.max' => Message::$error_length_field,

            'description.required' => Message::$empty_field,
            'description.max' => Message::$error_length_field,

            'start_date.required' => Message::$empty_field,
            'start_date.date' => Message::$error_date,
            'start_date.after_or_equal' => "La :attribute debe ser mayor o igual a la fecha actual.",

            'end_date.required' => Message::$empty_field,
            'end_date.date' => Message::$error_date,
            'end_date.after' => "La :attribute debe ser mayor a la fecha de inicio.",
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'proyecto',
            'description' => 'descripción',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de finalización'
        );
    }
}
