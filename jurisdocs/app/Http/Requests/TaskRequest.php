<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
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

            'task_subject' => 'required',
            'start_date' => 'required',
            'hora_inicio' => 'required',
            'project_status_id' => 'required',
            'priority' => 'required',
            'related' => 'required'
            

        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
