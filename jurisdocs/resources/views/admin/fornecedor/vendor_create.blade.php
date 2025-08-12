@extends('admin.layout.app')
@section('title','Adicionar fornecedor')

@section('content')
@component('component.heading' , [

'page_title' => 'Adicionar Fornecedor',
'action' => route('fornecedor.index') ,
'text' => 'Voltar'
])
@endcomponent

<div class="row">
    <form id="add_vendor" name="add_vendor" role="form" method="POST" action="{{route('fornecedor.store')}}"
          enctype="multipart/form-data">
        @csrf()

        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">

                <div class="x_content">

                    <div class="row">

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="tipo_fornecedor">Tipo de fornecedor <span class="text-danger">*</span></label>

                            <select name="tipo_fornecedor" class="form-control" id="tipo_fornecedor" required>
                                <option value="" selected disabled>Seleccionar</option>
                                <option value="F" {!! old('tipo_fornecedor') == 'F' ? 'selected' : '' !!}>Firma</option>
                                <option value="P" {!! old('tipo_fornecedor') == 'P' ? 'selected' : '' !!}>Particular</option>
                            </select>

                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group company_name">
                            <label for="company_name">Nome da empresa <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" value="{{old('company_name')}}" autocomplete="off"
                                   id="company_name">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group f_name" style="display: none">
                            <label for="f_name">Primeiro Nome <span class="text-danger">*</span></label>
                            <input type="text" name="f_name" class="form-control" value="{{old('f_name')}}" id="f_name" autocomplete="off"
                                   data-msg-required="Por favor, insere o primeiro nome"
                              >
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group l_name" style="display: none">
                            <label for="l_name">Sobrenome <span class="text-danger">*</span></label>
                            <input type="text" name="l_name" class="form-control" value="{{old('l_name')}}" id="l_name" autocomplete="off"
                                   data-msg-required="Por favor, insere o sobrenome">
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="nif">N&ordm; de Identifica&ccedil;&atilde;o Fiscal <span class="text-danger">*</span></label>
                            <input type="text" name="nif" class="form-control text-uppercase" value="{{old('nif')}}" id="nif" required
                                   
                                   data-msg-required="Por favor, insere o nº de identificação fiscal"
                             >
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="email">E-mail <span class="text-danger"></span></label>
                            <input type="email" name="email" class="form-control" value="{{old('email')}}" id="email" autocomplete="off">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="mobile">Telefone <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" value="{{old('mobile')}}" id="mobile" autocomplete="off">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="alternate_no">Telefone Alternativo<span class="text-danger"></span></label>
                            <input type="text" name="alternate_no" class="form-control" value="{{old('alternate_no')}}" id="alternate_no">
                        </div>
                        
                    </div>
                    
                    <div class="row">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Endere&ccedil;o</h2>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="x_content">

                                        <div class="row">

                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="state">Prov&iacute;ncia <span class="text-danger">*</span></label>
                                                <select id="provincia" name="provincia" data-clear="#municipio_id" class="form-control" data-rule-required="true"
                                                        data-msg-required=" Por favor, seleccione a província"
                                                        data-url = "{{route('get.state')}}"
                                                        >
                                                    <option value=""> Seleccionar</option>


                                                </select>
                                            </div>

                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="municipio_id">Munic&iacute;pio <span class="text-danger">*</span></label>
                                                <select id="municipio_id" name="municipio_id"
                                                        class="form-control" data-rule-required="true"
                                                        data-msg-required=" Por favor, seleccione o município"
                                                        data-url= "{{route('get.city')}}"
                                                        
                                                        >
                                                    <option value=""> Seleccionar cidade</option>

                                                </select>
                                            </div>

                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="bairro">Bairro </label>
                                                <select id="bairro_id" name="bairro_id" class="form-control"
                                                         data-url="{{route('get.bairro')}}"
                                                     >
                                                    <option value=""> Seleccionar</option>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="rua">Rua </label>
                                                <input type="text" name="rua" class="form-control" value="{{old('rua')}}" id="rua" autocomplete="off">
                                            </div>

                                            <div class="col-md-2 col-sm-12 col-xs-12 form-group numero">
                                                <label for="numero">N&uacute;mero</label>
                                                <input type="text" name="numero" class="form-control" value="{{old('numero')}}" id="numero" autocomplete="off">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <br>
                                    <a href="{{ route('fornecedor.index') }}" class="btn btn-danger">{{__('Cancel')}}</a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>

            </div>

        </div>
    </form>
</div>

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="common_check_exist"
       id="common_check_exist"
       value="{{ url('common_check_exist') }}">

@endsection

@push('js')
<script src="{{asset('assets/admin/vendor/vendor.js') }}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/deleteable/jquery.inputmask/dist/inputmask/inputmask.js')}}"></script>
<script src="{{asset('assets/admin/vendors/deleteable/jquery.inputmask/dist/inputmask/inputmask.extensions.js')}}"></script>

<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>

<script>

$(function () {

    //Money Euro
    $('[data-mask]').inputmask();
    // inputmask
    $(":input[data-inputmask-mask]").inputmask();
    $(":input[data-inputmask-alias]").inputmask();
    $(":input[data-inputmask-regex]").inputmask("Regex");
});

</script>

@endpush
