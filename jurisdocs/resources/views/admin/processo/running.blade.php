@extends('admin.layout.app')
@section('title','Processo')

@section('content')

<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Processos</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                @can('case_add')
                <a id="btn_novo_processo" href="{{ route('processo.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                    Novo Processo</a>
                @endcan

            </div>
        </div>
    </div>


    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">

                    <div class="row">

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="areaprocessual">Natureza do processo <span class="text-danger"></span></label>
                            <select name="areaprocessual" id="areaprocessual" class="form-control" required>

                                <option value="" selected disabled>Seleccionar</option>
                                @foreach($areasprocessuais as $areaprocessual)
                                <option value="{{$areaprocessual->id}}">{{$areaprocessual->designacao}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">

                            <div class="case-margin-top-23"></div>

                            <button type="submit" id="search" class="btn btn-success"><i
                                    class="fa fa-search"></i> Pesquisar
                            </button>
                        </div>


                    </div>

                </div>
            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">

                    <table id="case_list" class="table">
                        <thead>
                            <tr>
                                <th width=" 3%">No</th>
                                <th width="20%">Cliente & Detalhes do processo</th>
                                <th width="35%">Detalhes do Tribunal</th>
                                <th width="20%">Parte contr&aacute;ria</th>
                                <th width="9%">Estado</th>
                                <th width="3%">Ac&ccedil;&atilde;o</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>


    </div>
</div>

<div class="modal fade" id="modal-case-priority" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal">

        </div>
    </div>
</div>

<div class="modal fade" id="modal-change-court" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal_transfer">

        </div>
    </div>
</div>

<div class="modal fade" id="modal-next-date" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal_next_date">

        </div>
    </div>
</div>

<input type="hidden" name="get_case_important_modal"
       id="get_case_important_modal"
       value="{{url('admin/getCaseImportantModal')}}">

<input type="hidden" name="get_case_next_modal"
       id="get_case_next_modal"
       value="{{url('admin/getNextDateModal')}}">

<input type="hidden" name="get_case_cort_modal"
       id="get_case_cort_modal"
       value="{{url('admin/getChangeCourtModal')}}">

<input type="hidden" name="case_url"
       id="case_url"
       value="{{ url('admin/allCaseList') }}">

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="">

@endsection

@push('js')
<script src="{{asset('assets/js/processo/case-datatable.js')}}"></script>
@endpush
