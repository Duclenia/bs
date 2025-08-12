<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessoRequest extends FormRequest
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
            'no_processo' => 'nullable|max:30|unique:processo',
            'areaprocessual' => 'required|exists:areaprocessual,id',
            'tipo_processo' => 'required|exists:tipoprocesso,id',
            'tipo_crime' => 'required_if:areaprocessual,3|max:100',
            'valor_causa' => 'nullable|numeric',
            'orgao' => 'required',
            'orgaoextrajudicial' => 'required_if:orgao,Extrajudicial',
            'tribunal' => 'required_if:orgao,Judicial',
            'seccao' => 'required_if:orgao,Judicial',
            'client_name' => 'required|exists:cliente,id',
            'qualidade' => 'nullable'
        ];
    }
}
