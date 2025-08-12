<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointment extends FormRequest
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
              'type' => 'required',
              'exists_client' => 'required_if:type,exists|exists:cliente,id',
              'new_client' => 'required_if:type,new',

              'mobile' => 'required',
              'date' => 'required',
              'time' => 'required',
        ];
    }
      public function messages()
    {
        return [
              //'exists_client.required' => 'Please select client.',
              //'new_client.required' => 'Please enter client name',

              'mobile.required' => 'Por favor, insere o nº de telefone.',
              'date.required' => 'Por favor, insere a data.',
              'time.required' => 'Por favor, insere a hora',

        ];
    }
}
