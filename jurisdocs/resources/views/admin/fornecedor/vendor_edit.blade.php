@extends('admin.layout.app')
@section('title','Editar fornecedor')
@section('content')
@component('component.heading' , [
'page_title' => 'Editar fornecedor',
'action' => route('fornecedor.index') ,
'text' => 'Voltar'
])
@endcomponent

<div class="row">
    <form id="add_vendor" name="add_vendor" role="form" method="POST"
          action="{{route('fornecedor.update',$fornecedor->id)}}" enctype="multipart/form-data">
        <input type="hidden" id="id" value="{{ $fornecedor->id ?? ''}}" name="id">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PATCH">

        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">

                <div class="x_content">

                    <div class="row">

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="tipo_fornecedor">Tipo de fornecedor <span class="text-danger">*</span></label>

                            <select name="tipo_fornecedor" class="form-control" id="tipo_fornecedor" required="">

                                <option value="F" {!! old('tipo_fornecedor',$fornecedor->tipo ?? null) == 'F' ? 'selected' : '' !!}>Firma</option>
                                <option value="P" {!! old('tipo_fornecedor',$fornecedor->tipo ?? null) == 'P' ? 'selected' : '' !!}>Particular</option>
                            </select>

                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group company_name" style="display: none">
                            <label for="company_name">Nome da empresa <span class="text-danger">*</span></span></label>
                            <input type="text" class="form-control" name="company_name" autocomplete="off"
                                   id="company_name" value="{{ old('company_name', $fornecedor->company_name ?? null) }}"
                                   data-msg-required="Por favor, insere o nome da empresa"
                                   >
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group f_name" style="display: none">
                            <label for="f_name">Primeiro nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="f_name" name="f_name"
                                   value="{{ old('f_name', $fornecedor->nome ?? null)}}" autocomplete="off"
                                   data-msg-required="Por favor, insere o primeiro nome"
                                   >
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group l_name" style="display: none">
                            <label for="l_name">Sobrenome <span class="text-danger">*</span></label>
                            <input type="text" name="l_name" class="form-control" id="l_name" autocomplete="off" 
                                   value="{{ old('l_name', $fornecedor->sobrenome ?? null)}}"
                                   data-msg-required="Por favor, insere o sobrenome"

                                   >
                        </div>


                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="nif">N&ordm; de Identifica&ccedil;&atilde;o Fiscal <span class="text-danger">*</span></label>
                            <input type="text" name="nif" class="form-control text-uppercase" id="nif" required
                                   value="{{old('nif', $fornecedor->nif ?? null)}}" autocomplete="off"
                                   data-msg-required="Por favor, insere o nº de identificação fiscal"
                                   >
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="email">Email <span class="text-danger"></span></label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="off"
                                   value="{{ old('email', $fornecedor->email ?? null)}}">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="mobile">Telefone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                   data-rule-required="true"
                                   data-msg-required="Por favor, insere o nº de telefone" data-rule-maxlength="11"
                                   value="{{ old('mobile', $fornecedor->telefone ?? null)}}" maxlength="11">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="alternate_no">Telefone Alternativo<span class="text-danger"></span></label>
                            <input type="text" class="form-control" id="alternate_no"
                                   name="alternate_no" value="{{old('alternate_no', $fornecedor->alternate_no ?? null)}}">
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
                                            <label for="provincia">Prov&iacute;ncia <span class="text-danger">*</span></label>
                                            <select id="provincia" name="provincia" data-clear="#municipio_id" class="form-control select-change" data-rule-required="true"
                                                    data-msg-required=" Por favor, seleccione a província"
                                                    data-url = "{{route('get.state')}}"

                                                    >
                                                <option value=""> Seleccionar</option>
                                                @if($fornecedor->endereco->municipio->provincia)
                                                @foreach($provincias as $provincia)
                                                <option value="{{$provincia->id}}"
                                                        @if($provincia->id == $fornecedor->endereco->municipio->provincia->id)
                                                        selected @endif>{{$provincia->nome}}</option>
                                                @endforeach

                                                @endif


                                            </select>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="municipio_id">Munic&iacute;pio <span class="text-danger">*</span></label>
                                            <select id="municipio_id" name="municipio_id"
                                                    class="form-control" data-rule-required="true"
                                                    data-msg-required=" Por favor, seleccione o município"
                                                    data-url= "{{route('get.city')}}"
                                                    >
                                                <option value=""> Seleccionar</option>
                                                @if($fornecedor->endereco->municipio)
                                                <option value="{{$fornecedor->endereco->municipio->id}}"
                                                        selected>{{$fornecedor->endereco->municipio->nome}}</option>
                                                @endif

                                            </select>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="bairro">Bairro </label>
                                            <select id="bairro_id" name="bairro_id" class="form-control"
                                                    data-url="{{route('get.bairro')}}">
                                                
                                                <option value="{{ $fornecedor->endereco->bairro->id ?? ''}}"> {{ $fornecedor->endereco->bairro->nome ?? ''}}</option>

                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="rua">Rua </label>
                                            <input type="text" name="rua" value="{{old('rua', $fornecedor->endereco->rua ?? null)}}" class="form-control" id="rua" autocomplete="off">
                                        </div>

                                        <div class="col-md-2 col-sm-12 col-xs-12 form-group numero">
                                            <label for="numero">N&uacute;mero</label>
                                            <input type="text" name="numero" value="{{old('numero', $fornecedor->endereco->numero ?? null)}}" class="form-control" id="numero" autocomplete="off">
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
@endsection

@push('js')
<script src="{{asset('assets/admin/vendor/vendor.js') }}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>

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

<script>

    @if (!empty($fornecedor->tipo))

    @if ($fornecedor->tipo != 'P')

    $('.company_name').show();
    $('.f_name').hide();
    $('.l_name').hide();

    @ else

    $('.f_name').show();
    $('.l_name').show();

    $('.company_name').hide();
    $('#company_name').prop('required', false);

    @endif;

    @endif

</script>

@endpush
