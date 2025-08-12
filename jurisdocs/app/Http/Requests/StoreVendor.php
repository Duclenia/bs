<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendor extends FormRequest
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
            //
            'mobile' => 'required',
            'provincia' => 'required|numeric',
            'municipio_id' => 'required|numeric',
            'nif' => 'required|unique:fornecedor'
        ];
    }
    public function messages()
    {
        return [

            'mobile.required'     => 'Por favor, insere o telefone.',
            'provincia.required'  => 'Por favor, seleccione a província.',
            'municipio_id.required' => 'Por favor, seleccione o município.',
            'nif.required' => 'Por favor, insere o nº de identificação fiscal'

        ];
    }
}
