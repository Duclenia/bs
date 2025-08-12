<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cliente;

class UpdateClientRequest extends FormRequest
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
        $id = $this->segment(3);
        
        $cliente = Cliente::with(['documento','utilizador'])->find($id);
        
        $rules = [
            
           'tipo_cliente' => 'required|exists:tipopessoa,id',
           'f_name'       => 'required_if:tipo_cliente,2|max:50',
           'l_name'       => 'required_if:tipo_cliente,2|max:50',
           'documento'    => 'required_if:tipo_cliente,2',
           'ndi'          => 'required_if:tipo_cliente,2|max:15',
           'estado_civil' => 'required_if:tipo_cliente,2',
           'nif'          => 'required|max:16|unique:cliente,nif,'.$id.',id',
           'mobile'       => 'required|max:20',
           'address'      => 'required',
           'country'      => 'required',
           'mobile'       => 'required'
        ];
        
        
        if($cliente->utilizador)
            $rules = ['email' => 'nullable|email|max:100|unique:users,email,'.$cliente->utilizador->id.',id'];
        else
            $rules = ['email' => 'nullable|email|max:100|unique:users'];
        
        
        return $rules;
    }
}
