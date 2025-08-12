@extends('admin.layout.app')
@section('title','Registar processo')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>Registar Processo</h3>
    </div>

    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{route('processo.index')}}" class="btn btn-primary">{{__('Back')}}</a>

        </div>
    </div>
</div>
<!------------------------------------------------ ROW 1-------------------------------------------- -->


<form method="post" name="add_case" id="add_case" action="{{route('processo.store')}}">
    @csrf()

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detalhes do Processo</h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>{{__('Whoops!')}}</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="no_processo">N&ordm; do Processo</label>
                        <input type="text" name="no_processo" id="no_processo" class="form-control" value="{{old('no_processo')}}" autocomplete="off">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="areaprocessual">Natureza do processo<span class="text-danger">*</span></label>
                        <select name="areaprocessual" class="form-control" id="areaProcessual" required
                                data-url="{{ route('get.areaprocessual') }}"
                                data-clear="#tipo_processo,#tribunal,#seccao,#juiz,#qualidade,#estadoprocesso"
                                >

                            <option value="" selected disabled>Seleccionar</option>

                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="tipo_processo" class="tipo_processo">Forma do processo <span class="text-danger">*</span></label>
                        <select name="tipo_processo" class="form-control" id="tipo_processo"  required=""
                                data-url="{{ route('get.tipoprocesso') }}"

                                >
                            <option value="">Seleccionar</option>

                        </select>
                    </div>

                    <div class="row"></div>

                    
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group tipo_crime" style="display: none;">
                        <label for="tipo_crime">Tipo de crime<span class="text-danger">*</span></label>
                        <input type="text" name="tipo_crime" class="form-control" value="{{old('tipo_crime')}}" id="tipo_crime" autocomplete="off"

                               data-msg-required="Por favor, insere o tipo de crime."
                               >
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="valor_causa">Valor da causa</label>
                        <input type="text" name="valor_causa" class="form-control" value="{{old('valor_causa')}}" id="valor_causa" onkeypress='return isFloatsNumberKey(event)'>
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="orgao">&Oacute;rg&atilde;o <span class="text-danger">*</span></label>
                        <select name="orgao" class="form-control" id="orgao" required>
                            <option value="" selected disabled>Seleccionar</option>

                            <option value="Extrajudicial" {!! old('orgao') == 'Extrajudicial' ? 'selected' : '' !!}>EXTRAJUDICIAL</option>
                            <option value="Judicial" {!! old('orgao') == 'Judicial' ? 'selected' : '' !!}>JUDICIAL</option>
                            <option value="Judiciário" {!! old('orgao') == 'Judiciário' ? 'selected' : '' !!}>JUDICI&Aacute;RIO</option>

                        </select>

                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group orgaojudiciario" style="display: none;">
                        <label for="orgaojudiciario">&Oacute;rg&atilde;o Judici&aacute;rio <span class="text-danger">*</span></label>
                        <select name="orgaojudiciario" class="form-control" id="orgaojudiciario"

                                data-url="{{ route('get.orgao.judiciario') }}"

                                >
                            <option value="">Seleccionar</option>

                        </select>

                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group orgaoextrajudicial" style="display: none;">
                        <label for="orgaoextrajudicial">&Oacute;rg&atilde;o Extrajudicial <span class="text-danger">*</span></label>
                        <input type="text" name="orgaoextrajudicial" class="form-control" value="{{old('orgaoextrajudicial')}}" id="orgaoextrajudicial"
                               data-msg-required="Por favor, insere o órgão extrajudicial.">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group tribunal" style="display: none;">
                        <label for="tribunal">{{__('Court')}}</label>
                        <select name="tribunal" class="form-control" id="tribunal"
                                data-url="{{route('get.tribunal')}}"
                                data-clear="#seccao,#juiz"
                                >
                            <option value="">Seleccionar</option>
                        </select>

                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group seccao" style="display: none;">
                        <label for="seccao">Sec&ccedil;&atilde;o</label>
                        <select name="seccao" id="seccao" class="form-control" data-url ="{{route('get.seccao')}}"
                                data-clear="#juiz">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group juiz" style="display: none;">
                        <label for="juiz">Juiz do processo <span class="text-danger"></span></label>
                        <select name="juiz"  id="juiz"  class="form-control"
                                data-url= "{{route('get.juiz')}}"
                                >
                            <option value="">Seleccionar</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group mandatario_judicial" style="display:none">
                        <label for="mandatario_judicial">Mandat&aacute;rio Judicial</label>
                        <input type="text" name="mandatario_judicial"  id="mandatario_judicial"  class="form-control" value="{{old('mandatario_judicial')}}" autocomplete="off">

                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group escrivao" style="display:none">
                        <label for="escrivao">Escriv&atilde;o</label>
                        <input type="text" name="escrivao"  id="escrivao"  class="form-control" value="{{old('escrivao')}}" autocomplete="off">

                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group instrutor" style="display: none">
                        <label for="instrutor">Instrutor</label>
                        <input type="text" name="instrutor" id="instrutor" class="form-control" value="{{old('instrutor')}}" autocomplete="off">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group procurador" style="display: none">
                        <label for="procurador">Procurador</label>
                        <input type="text" name="procurador" id="procurador" class="form-control" value="{{old('procurador')}}" autocomplete="off">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="estado">Estado do processo <span class="text-danger">*</span></label>
                        <select name="estado" class="form-control" id="estadoprocesso" data-url="{{ route('get.estadoprocesso') }}">
                            <option value="">Seleccionar</option>

                        </select>
                    </div>
                    
                    <div class="row"></div>

                    <div class="col-md-9 col-sm-12 col-xs-12 form-group">
                        <label for="descricao">Descri&ccedil;&atilde;o Sum&aacute;ria</label>
                        <textarea id="descricao" name="descricao" class="form-control">{{old('descricao')}}</textarea>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{__('Client Detail')}}</h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="client_name">{{__('Client')}} <span class="text-danger">*</span></label>
                        <select class="form-control" name="client_name" id="client_name">
                            <option value="">{{__('Select client')}}</option>
                            @foreach($client_list as $list)
                            <option value="{{ $list->id}}">{{ str_pad($list->id, 5, '0', STR_PAD_LEFT). ' - '.$list->FullName}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12 form-group">
                        <label for="qualidade">Qualidade processual</label>

                        <select class="form-control" name="qualidade" id="qualidade"

                                data-url ="{{route('get.intervdesignacao')}}"
                                >
                            <option value="">Seleccionar</option>

                        </select>

                    </div>

                    <div class="row"></div>


                    <div class="repeater">
                        <div data-repeater-list="parties_detail">
                            <div data-repeater-item>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="discount_text "> <b class="position_name">Parte contr&aacute;ria
                                            </b></label>
                                        <input type="text" id="party_name" name="party_name" class="form-control">
                                    </div>

                                </div>


                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label class="discount_text "> <b class="qualidade_pc">Posi&ccedil;&atilde;o processual
                                            </b></label>

                                        <select name='qualidade_pc' id='qualidade_pc' class="form-control">
                                            <option value="">Seleccionar</option>
                                            @foreach($intervsdesignacao as $intervdesignacao)
                                            <option value="{{$intervdesignacao->id}}">{{$intervdesignacao->designacao}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="discount_text "><b class="position_advo">Advogado
                                            </b></label>
                                        <input type="text" id="party_advocate" name="party_advocate" class="form-control">
                                    </div>

                                </div>

                                <div class="col-md-1">

                                    <div class="form-group">

                                        <div class="case-margin-top-23"></div>
                                        <button type="button" data-repeater-delete type="button"
                                                class="btn btn-danger waves-effect waves-light"><i
                                                class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </div>

                                </div>

                                <br>
                            </div>
                        </div>
                        <button data-repeater-create type="button" value="Add New"
                                class="btn btn-success waves-effect waves-light btn btn-success-edit" type="button">
                            <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Adicionar
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!------------------------------------------------------- End ROw ----------------------------------------             ----->

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Advogado(s) do Processo</h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="fullname">Advogado(s) <span class="text-danger">*</span></label>
                        <select name="assigned_to[]" multiple class="form-control" id="assigned_to" required>
                            @foreach($users as $key=>$val)
                            <option value="{{$val->id}}">{{mb_strtoupper($val->nome.' '.$val->sobrenome)}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

        </div>


        <div class="form-group pull-right">
            <div class="col-md-12 col-sm-6 col-xs-12">


                <a class="btn btn-danger" href="{{route('processo.index')}}">{{__('Cancel')}}</a>
                <button type="submit" class="btn btn-success"><i class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                </button>
            </div>

        </div>
        <br>

    </div>
</form>

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="">

<input type="hidden" name="getCaseSubType"
       id="getCaseSubType"
       value="{{ url('getCaseSubType')}}">

<input type="hidden" name="getCourt"
       id="getCourt"
       value="{{ url('getCourt')}}">

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="common_check_exist"
       id="common_check_exist"
       value="{{ url('common_check_exist') }}">
@endsection

@push('js')
<script src="{{asset('assets/js/processo/case-add-validation.js')}}"></script>
<script src="{{asset('assets/admin/js/repeter/repeater.js') }}"></script>
@endpush
