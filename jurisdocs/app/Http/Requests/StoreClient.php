<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClient extends FormRequest
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

           'tipo_cliente' => 'required|exists:tipopessoa,id',
           'f_name'       => 'required_if:tipo_cliente,2|max:50',
           'l_name'       => 'required_if:tipo_cliente,2|max:50',
           'documento'    => 'required_if:tipo_cliente,2',
           'ndi'          => 'required_if:tipo_cliente,2|max:15|unique:documento',
           'estado_civil' => 'required_if:tipo_cliente,2',
           'nif'          => 'required|max:16|unique:cliente',
           'email'        => 'nullable|email|max:100|unique:users',
           'mobile'       => 'required|max:20',
           'address'      => 'required',
           'country'      => 'required',
           'mobile'       => 'required'
            
        ];
    }

    public function messages()
    {
        return [

//            'f_name.required' => 'Por favor, insere o primeiro nome.',
//            'l_name.required' => 'Por favor, insere o sobrenome.',
            'address.required' => 'Please enter address.',
            'country.required' => 'Por favor, seleccione o pa&iacute;s.',
            'mobile.required' => 'Por favor, insere o telefone.',
            
        ];
    }
}
