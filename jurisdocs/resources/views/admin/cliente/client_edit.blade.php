@extends('admin.layout.app')
@section('title',__('Client Edit'))

@push('style')
 <link href="{{asset('assets/plugins/intl-tel-input/css/intlTelInput.css')}}" rel="stylesheet"/>
 
 <style>
    .iti { width: 100%; }
</style>

@endpush
@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>{{__('Client Edit')}}</h3>
    </div>

    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{route('clients.index')}}" class="btn btn-primary">{{__('Back')}}</a>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        @include('component.error')
        <div class="x_panel">
            <form id="edit_client_form" name="edit_client_form" role="form" method="POST"
                  action="{{route('clients.update',$client->id)}}">
                <input type="hidden" id="id" value="{{ $client->id}}" name="id">
                @if($client->documento)
                <input type="hidden" id="cod_doc" value="{{$client->documento->id}}">
                @endif
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PATCH">
                <div class="x_content">

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="tipo_cliente">Tipo de cliente <span class="text-danger">*</span></label><br>

                        <select name="tipo_cliente" class="form-control" id="tipo_cliente" required="">
                            @foreach($tipospessoas as $tipopessoa)
                            <option value="{{ $tipopessoa->id }}" {!! old('tipo_cliente', $client->tipo ?? null) == $tipopessoa->id ? 'selected' : '' !!}> {{ $tipopessoa->designacao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group f_name" style="display: none">
                        <label for="f_name">{{__('First Name')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="f_name" name="f_name" maxlength="50" autocomplete="off"
                               value="{{ old('f_name', $client->nome ?? null)}}" data-msg-required="Por favor, insere o nome do cliente.">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group l_name" style="display: none">
                        <label for="l_name">{{__('Last Name')}} <span class="text-danger">*</span></label>
                        <input type="text" name="l_name" class="form-control text-uppercase" id="l_name" maxlength="50" autocomplete="off"
                               value="{{ old('l_name', $client->sobrenome ?? null)}}" data-msg-required="Por favor, insere o sobrenome do cliente.">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group instituicao" style="display: none">
                        <label for="instituicao">Institui&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <input type="text" name="instituicao" class="form-control" id="instituicao" maxlength="100"
                               value="{{ old('instituicao', $client->instituicao ?? null)}}" data-msg-required="Por favor, insere a instituição.">
                    </div>

       
                    @if($client->documento)
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group documento" style="display: none">
                        <label for="documento">Documento de Identifica&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <select name="documento" class="form-control" id="documento" data-msg-required="Por favor, seleccione o tipo de documento de identificação." required>
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach($tiposdocumentos as $tipodocumento)
                            <option value="{{$tipodocumento->id}}" 
                                    {{old('documento', $client->documento->tipo ?? null) == $tipodocumento->id   ? 'Selected' : '' }}>
                                {{$tipodocumento->designacao}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group ndi" style="display: none">
                        <label for="ndi"> N&ordm do documento de Identifica&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <input type="text" name="ndi" value="{{old('ndi', $client->documento->ndi ?? null)}}" class="form-control text-uppercase" id="ndi"
                               data-msg-required="Por favor, insere o nº do documento de identificação." required
                               > 
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group ddvdoc">
                        <label for="ddvdoc">Data de Validade do documento de identifica&ccedil;&atilde;o</label>
                        <input type="text" name="ddvdoc" value="{{date($date_format_laravel,strtotime($client->documento->data_validade))}}" class="form-control" id="ddvdoc" autocomplete="off"

                               data-inputmask-alias="{{$date_format_datepiker}}"

                               data-mask

                               >
                    </div>
                    @endif

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group estado_civil">
                        <label for="estado_civil">{{__('Marital status')}} <span class="text-danger">*</span></label>
                        <select name="estado_civil" class="form-control" id="estado_civil" data-msg-required="Por favor, seleccione o estado civil.">
                            <option value="" selected disabled> Seleccionar estado civil</option>
                            <option value="C" {{ old('estado_civil', $client->estado_civil ?? null) == 'C' ? 'selected' : '' }}> {{__('Married')}}</option>
                            <option value="D" {{ old('estado_civil', $client->estado_civil ?? null) == 'D' ? "selected" : "" }}> {{__('Divorced')}}</option>
                            <option value="S" {{ old('estado_civil', $client->estado_civil ?? null)  == 'S' ? "selected" : "" }}> SOLTEIRO(A)</option>
                            <option value="V" {{ old('estado_civil', $client->estado_civil ?? null)  == 'V' ? "selected" : "" }}> VI&Uacute;VO(A)</option>

                        </select>
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group regime_casamento" style="display: none;">
                        <label for="regime_casamento">Regime do casamento </label>
                        <select name="regime_casamento" class="form-control" id="estado_civil">
                            <option value="" selected disabled> Seleccionar regime do casamento</option>
                            <option value="CB" {{old('regime_casamento', $client->regime_casamento ?? null) == 'CB' ? 'selected': ''}}> Comunh&atilde;o de bens adquiridos</option>
                            <option value="SB" {{old('regime_casamento', $client->regime_casamento ?? null) == 'SB' ? 'selected': ''}}> Separa&ccedil;&atilde;o de bens</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="nif" id="lb_nif">N&ordm; de Identifica&ccedil;&atilde;o Fiscal</label>
                        <input type="text" name="nif" class="form-control text-uppercase" id="nif" value="{{old('nif', $client->nif ?? null)}}" autocomplete="off"
                               data-msg-required="Por favor, insere o Nº de Identificação Fiscal."
                               >
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" value="{{old('email', $client->utilizador->email ?? null)}}" class="form-control email" autocomplete="off"
                               id="email">
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="mobile">Telem&oacute;vel <span class="text-danger">*</span></label>
                        <input type="tel" name="mobile" class="form-control" id="mobile"
                               value="{{ old('mobile', $client->telefone ?? null)}}">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="alternate_no">Telefone</label>
                        <input type="tel" name="alternate_no" value="{{ old('alternate_no', $client->alternate_no ?? null)}}"
                               class="form-control" id="alternate_no">
                    </div>


                    <div class="country col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="country">{{__('Country')}} <span class="text-danger">*</span></label>
                        <select class="form-control select-change country-select2 selct2-width-100 " required=""
                                name="country" id="country"
                                data-url="{{ route('get.country') }}"
                                data-clear="#city_id,#state"
                                >
                            <option value=""> Seleccionar pa&iacute;s</option>
                            @if ($client->pais)
                            <option value="{{ $client->pais->id }}"
                                    selected>{{ $client->pais->nome }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group provincia" style="display: none">
                        <label for="state">{{__('Province')}}</label>
                        <select id="state" name="state"

                                data-url="{{ route('get.state') }}"
                                data-target="#country"
                                data-clear="#city_id"
                                class="form-control state-select2 select-change">
                            <option value=""> Seleccionar prov&iacute;ncia</option>
                            @if ($client->provincia)
                            <option value="{{ $client->provincia->id }}"
                                    selected>{{ $client->provincia->nome }}</option>
                            @endif

                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group municipio" style="display: none">
                        <label for="city_id">Munic&iacute;pio</label>
                        <select id="city_id" name="city_id" required=""
                                data-url="{{ route('get.city') }}"
                                data-target="#state"

                                class="form-control city-select2">
                            <option value=""> Seleccionar cidade</option>
                            @if($client->municipio)
                            <option value="{{ $client->municipio->id }}"
                                    selected>{{ $client->municipio->nome }}</option>
                            @endif

                        </select>
                    </div>


                    <div class="col-md-8 col-sm-12 col-xs-12 form-group">
                        <label for="address">{{__('Address')}} <span class="text-danger">*</span></label>
                        <input type="text" name="address" placeholder="" value="{{old('address',$client->endereco ?? null)}}"
                               class="form-control" id="address" required="">
                    </div>


                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <br>
                        <input type="checkbox" value="Yes" name="change_court_chk" id="change_court_chk"> Adicionar mais pessoas
                        <br/>

                    </div>
                    <div id="change_court_div" class="hidden">

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="type">{{__('Client')}} <span class="text-danger">*</span></label><br>
                                <br>
                                <input type="radio" name="type" id="test6"
                                       value="single" {{ (!empty($client->client_type) && $client->client_type =='single') ? "checked" : "" }} />
                                &nbsp;&nbsp;&Uacute;nico Advogado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="type" id="test7"
                                       value="multiple" {{ (!empty($client->client_type) && $client->client_type =='multiple') ? "checked" : "" }} />&nbsp;&nbsp;V&aacute;rios
                                Advogados
                            </div>
                        </div>
                        <div class="repeater one">
                            <div data-repeater-list="group-a">
                                @if(!empty($client_parties_invoive) && count($client_parties_invoive)>0 && $client->client_type =='single')
                                @foreach($client_parties_invoive as $key=> $value)
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="firstname">{{__('First Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="firstname" name="firstname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o primeiro nome."
                                                   class="form-control"
                                                   value="{{ $value->party_firstname }}">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="middlename">{{__('Middle Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="middlename" name="middlename"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do meio."
                                                   class="form-control"
                                                   value="{{ $value->party_middlename }}">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="lastname">{{__('Last Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="lastname" name="lastname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o sobrenome."
                                                   class="form-control"
                                                   value="{{ $value->party_lastname }}">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="mobile_client" name="mobile_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                   data-rule-number="true"
                                                   data-msg-number="please enter digit 0-9."
                                                   data-rule-minlength="9"
                                                   data-msg-minlength="mobile must be 9 digit."
                                                   data-rule-maxlength="9"
                                                   data-msg-maxlength="mobile must be 9 digit."
                                                   class="form-control" value="{{ $value->party_mobile }}"
                                                   maxlength="9">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="address_client">{{__('Address')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="address_client" id="address_client" 
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere a morada."
                                                   class="form-control" value="{{ $value->party_address }}">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <br>
                                            <button type="button" data-repeater-delete type="button"
                                                    class="btn btn-danger"><i class="fa fa-trash-o"
                                                                      aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="firstname">{{__('First Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="firstname" id="firstname" 
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o primeiro nome."
                                                   class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="middlename">{{__('Middle Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="middlename" id="middlename" 
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do meio."
                                                   class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="lastname">{{__('Last Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="lastname" id="lastname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o sobrenome."
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_client" id="mobile_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                   data-rule-number="true"
                                                   data-msg-number="please enter digit 0-9."
                                                   data-rule-minlength="9"
                                                   data-msg-minlength="mobile must be 9 digit."
                                                   data-rule-maxlength="10"
                                                   data-msg-maxlength="mobile must be 9 digit."
                                                   class="form-control" maxlength="9">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="address_client">{{__('Address')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="address_client" id="address_client" 
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere a morada."
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <br>
                                            <button type="button" data-repeater-delete type="button"
                                                    class="btn btn-danger"><i class="fa fa-trash-o"
                                                                      aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>

                                @endif
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <br>
                                <button data-repeater-create type="button" value="Add New"
                                        class="btn btn-success waves-effect waves-light btn btn-success-edit"
                                        type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div class="repeater two">
                            <div data-repeater-list="group-b">
                                @if(!empty($client_parties_invoive) && count($client_parties_invoive)>0 && $client->client_type =='multiple')
                                @foreach($client_parties_invoive as $key=> $value)
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="firstname">{{__('First Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="firstname" id="firstname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o primeiro nome."
                                                   class="form-control"
                                                   value="{{ $value->party_firstname }}">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="middlename">{{__('Middle Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="middlename" id="middlename"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do meio."
                                                   class="form-control"
                                                   value="{{ $value->party_middlename }}">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="lastname">{{__('Last Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="lastname" id="lastname" 
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o sobrenome."
                                                   class="form-control"
                                                   value="{{ $value->party_lastname }}">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="mobile_client" id="mobile_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                   data-rule-number="true"
                                                   data-msg-number="please enter digit 0-9."
                                                   data-rule-minlength="9"
                                                   data-msg-minlength="mobile must be 9 digit."
                                                   data-rule-maxlength="9"
                                                   data-msg-maxlength="mobile must be 9 digit."
                                                   class="form-control" value="{{ $value->party_mobile }}"
                                                   maxlength="9">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="address_client">{{__('Address')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="address_client" id="address_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere a morada."
                                                   class="form-control" value="{{ $value->party_address }}">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="advocate_name">Nome do Advogado <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="advocate_name" id="advocate_name"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do advgado."
                                                   class="form-control"
                                                   value="{{ $value->party_advocate }}">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <br>
                                            <button type="button" data-repeater-delete type="button"
                                                    class="btn btn-danger waves-effect waves-light"><i
                                                    class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                                @endforeach
                                @else
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="fullname">{{__('First Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" id="firstname" name="firstname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o primeiro nome." class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="fullname">{{__('Middle Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" id="middlename" name="middlename"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do meio." class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="fullname">{{__('Last Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="lastname" name="lastname"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o sobrenome." class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" id="mobile_client" name="mobile_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                   data-rule-number="true"
                                                   data-msg-number="{{__('please enter digit 0-9.')}}"
                                                   data-rule-minlength="9"
                                                   data-msg-minlength="mobile must be 9 digit."
                                                   data-rule-maxlength="9"
                                                   data-msg-maxlength="mobile must be 9 digit."
                                                   class="form-control" maxlength="9">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="fullname">{{__('Address')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="address_client" name="address_client"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere a morada."
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="fullname">Nome do Advogado. <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="advocate_name" name="advocate_name"
                                                   data-rule-required="true"
                                                   data-msg-required="Por favor, insere o nome do advogado."
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <br>
                                            <button type="button" data-repeater-delete type="button"
                                                    class="btn btn-danger waves-effect waves-light"><i
                                                    class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </div>

                                    </div>

                                </div>

                                @endif
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <br>
                                <button data-repeater-create type="button" value="Add New"
                                        class="btn btn-success waves-effect waves-light btn btn-success-edit"
                                        type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pull-right">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <a href="{{ route('clients.index')  }}" class="btn btn-danger">{{__('Cancel')}}</a>
                            <button type="submit" name="btn_add_client" class="btn btn-success"><i class="fa fa-save"
                                                                             id="show_loader"></i>&nbsp;{{__('Save')}}
                            </button>
                        </div>
                    </div>


                </div>
            </form>
        </div>

    </div>
</div>

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">

<input type="hidden" id="utils" value="{{asset('assets/plugins/intl-tel-input/js/utils.js')}}">

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="common_check_exist"
       id="common_check_exist"
       value="{{ url('common_check_exist') }}">

<input type="hidden" id="language" value="{{app()->getLocale()}}">

@endsection
@push('js')
<script src="{{asset('assets/admin/js/selectjs.js')}}"></script>
<script src="{{asset('assets/admin/vendors/repeter/repeater.js')}}"></script>
<script src="{{asset('assets/admin/vendors/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.'.app()->getLocale().'.min.js')}}"></script>

<script src="{{asset('assets/plugins/intl-tel-input/js/intlTelInput.js')}}"></script>
<script src="{{asset('assets/plugins/intl-tel-input/js/utils.js')}}"></script>

<script src="{{asset('assets/js/cliente/edit-client-validation.js')}}"></script>
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


@if(old('tipo_cliente', $client->tipo ?? null) != '2')
<script>
    
    $('.f_name').hide();
    $('#f_name').prop('required', false).val('');

    $('.l_name').hide();
    $('#l_name').prop('required', false).val('');

    $('.instituicao').show();
    $('#instituicao').prop('required', true);

    $('.estado_civil').hide();
    
    $('.regime_casamento').hide();

    $('.documento').hide();
    $('#documento').prop('required', false);

    $('.ndi').hide();
    $('#ndi').prop('required', false);

    $('.ddvdoc').hide();
    
    $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
    $('#nif').prop('required', true);  
</script>
@else

<script>
    
    $('.f_name').show();
    $('#f_name').prop('required', true);

    $('.l_name').show();
    $('#l_name').prop('required', true);

    $('.instituicao').hide();
    $('#instituicao').prop('required', false).val('');

    $('.estado_civil').show();

    $('.documento').show();
    $('#documento').prop('required', true);

    $('.ndi').show();
    $('#ndi').prop('required', true);

    $('.ddvdoc').show();
    
    @if($client->estado_civil =='C')
      $('.regime_casamento').show();
    @else
      $('.regime_casamento').hide();
    @endif
    
    $('#lb_nif').html('Nº de Identificação Fiscal');
    $('#nif').prop('required', false); 

</script>

@endif


@if($client->documento)
  @if($client->documento->tipo == 1 || $client->documento->tipo == 2)
   <script>
       $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
       $('#nif').prop('required', true); 
   </script>
  @else
   <script>
       $('#lb_nif').html('Nº de Identificação Fiscal');
       $('#nif').prop('required', false); 
   </script>
  @endif
@endif

@if(!empty($client->client_type) && $client->client_type =='single')
<script>
    'use strict';
    $('.two').hide();
</script>
@endif
@if(!empty($client->client_type) && $client->client_type =='multiple')
<script>
    'use strict';
    $('.one').css('display', 'none');
</script>
@endif

@if(!empty($client->pais) && $client->pais->id == '6')
<script>
    $('.provincia').show();
    $('.municipio').show();
</script>
@endif

@if(!empty($client_parties_invoive) && count($client_parties_invoive)>0  || !empty($client_parties_invoive) && count($client_parties_invoive)>0 )
<script>
    'use strict';
    $('#change_court_div').removeClass('hidden');
    $('#change_court_chk').prop('checked', true);
</script>
@endif

@endpush
