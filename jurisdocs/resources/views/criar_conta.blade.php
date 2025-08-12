@extends('layouts.cliente.app')

@push('css')
<link href="{{asset('assets/css/install.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
<link href="{{asset('assets/admin/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<section class="login_content">

    <form id="form_criarConta" role="form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="float: none;">Registar-se </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                        @endif
                        <span class="section pull-left">Dados pessoais</span>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="tipo_cliente">Tipo de cliente <span class="text-danger">*</span></label>
                            <select name="tipo_cliente" class="form-control" id="tipo_cliente" required="">
                                <option value="" selected disabled> Seleccionar</option>
                                @foreach($tiposPessoas as $tipoPessoa)
                                <option value="{{ $tipoPessoa->id }}"> {{ $tipoPessoa->designacao }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group f_name" style="display: none;">
                            <label for="f_name">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="f_name" class="form-control text-uppercase" id="f_name" autocomplete="off" maxlength="50"
                                   data-msg-required="Por favor, insere o nome">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group l_name" style="display: none;">
                            <label for="l_name">Sobrenome <span class="text-danger">*</span></label>
                            <input type="text" name="l_name" class="form-control text-uppercase" id="l_name"  autocomplete="off" maxlength="50"
                                   data-msg-required="Por favor, insere o sobrenome">
                        </div>
                        
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group instituicao" style="display: none">
                            <label for="instituicao">Institui&ccedil;&atilde;o <span class="text-danger">*</span></label>
                            <input type="text" name="instituicao" class="form-control text-uppercase" id="instituicao" autocomplete="off"
                                   data-msg-required="Por favor, insere o nome da instituicao">
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="nif">N&ordm; de Identifica&ccedil;&atilde;o Fiscal <span class="text-danger">*</span></label>
                        <input type="text" name="nif" class="form-control text-uppercase" id="nif" autocomplete="off" required>
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group documento" style="display: none">
                        <label for="documento">Documento de Identifica&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <select name="documento" class="form-control" id="documento" required
                                
                                data-msg-required="Por favor, seleccione o documento de identificação"
                                >
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach($tiposdocumentos as $tipodocumento)
                            <option value="{{$tipodocumento->id}}">{{$tipodocumento->designacao}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group ndi" style="display: none">
                        <label for="ndi"> N&ordm do doc. de Identifica&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <input type="text" name="ndi" class="form-control text-uppercase" id="ndi" required
                             data-msg-required="Por favor, insere o nº do documento de identificação"
                           > 
                    </div>


                        <div class="col-md-4 col-sm-12 col-xs-12 form-group estado_civil" style="display: none;">
                            <label for="estado_civil">Estado civil <span class="text-danger">*</span></label>
                            <select name="estado_civil" class="form-control" id="estado_civil" data-msg-required="Por favor, seleccione o estado civil.">
                                <option value="" selected disabled> Seleccionar</option>
                                <option value="C"> Casado(a)</option>
                                <option value="D"> Divorciado(a)</option>
                                <option value="S"> Solteiro(a)</option>
                                <option value="V"> Vi&uacute;vo(a)</option>

                            </select>
                        </div>

                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="mobile">Telem&oacute;vel <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" required autocomplete="off">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="alternate_no">Telefone</label>
                            <input type="text" class="form-control" id="alternate_no"
                                   name="alternate_no">
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group">
                            <label for="address">Morada <span class="text-danger">*</span></label>
                            <input type="text" name="address" placeholder="" class="form-control" id="address" required="">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="pais">Pa&iacute;s <span class="text-danger">*</span></label>
                            <select name="pais" class="form-control country-select2" id="pais">
                                <option value="" selected disabled> Seleccionar</option>
                                @foreach($paises as $pais)
                                <option value="{{$pais->id}}">{{$pais->nome}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="row"></div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group provincia" style="display:none">
                            <label for="provincia"> Prov&iacute;ncia</label>
                            <select name="provincia" id="provincia" style="width:100%"
                                    data-url = "{{route('get.provincia')}}"
                                    data-target="#pais"
                                    data-clear="#municipio_id"
                                    
                                    class="form-control state-select2 provincia">
                                <option value=""> Seleccionar</option>

                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group municipio" style="display:none">
                            <label for="municipio_id">Munic&iacute;pio</label>
                            <select name="municipio_id" id="municipio_id"  class="form-control" style="width:100%"
                                         data-url= "{{route('get.municipio')}}"
                                         data-target="#provincia"
                               >
                                <option value=""> Seleccionar</option>

                            </select>
                        </div>


                        <span class="section pull-left">Dados da Conta</span>

                        <div class="col-md-5 col-sm-12 col-xs-12 form-group">
                            <label for="email">E-mail <span class="text-danger">*</span> </label>

                            <input type="email" id="email" name="email" required="required" class="form-control email">

                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                            <label for="password">Palavra-passe <span class="text-danger">*</span> </label>

                            <input type="password" id="password" name="password" required="required"
                                   class="form-control" min="8">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="cnm_password">Confirmar Palavra-passe <span class="text-danger">*</span></label>

                            <input type="password" id="cnm_password" name="cnm_password" required="required"
                                   class="form-control">

                        </div>

                        <div class="form-group" align="center">
                            <div class="col-md-12 col-sm-6 col-xs-12">

                                <input type="hidden" name="route-exist-check"
                                       id="route-exist-check"
                                       value="{{ url('admin/check_client_email_exits') }}">
                                
                                <input type="hidden" name="token-value"
                                       id="token-value"
                                       value="{{csrf_token()}}">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                                </button>

                                <a class="reset_pass pull-left"  href="{{ route('login') }}">{{ __('Login') }}</a>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="separator">
                            <!--  <p class="change_link">New to site?
                               <a href="#signup" class="to_register"> Create Account </a>
                               </p> -->
                            <div class="clearfix"></div>
                            <br/>
                            <div style="text-align: center;">
                                <p>©2021 JurisDocs - Gest&atilde;o de Escrit&oacute;rio de Advogados. Desenvolvido por Mwango Click</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="check_user_email_exits"
       id="check_user_email_exits"
       value="{{ url('cliente/check_user_email_exits') }}">

<input type="hidden" name="check_nif_exits"
       id="check_nif_exits" value="{{url('cliente/check_nif_exits')}}">

<input type="hidden" name="check_ndi_exits"
       id="check_ndi_exits" value="{{url('cliente/check_ndi_exits')}}">

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">

@endsection

@push('js')
<script src="{{asset('assets/admin/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js')}}"></script>
<script src="{{asset('assets/js/criar_conta.js')}}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('assets/admin/vendors/select2/dist/js/i18n/pt.js')}}"></script>

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