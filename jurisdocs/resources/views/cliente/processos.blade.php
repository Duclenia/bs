@extends('admin.layout.app')
@section('title',__('Client View'))
@section('content')
    <div class="page-title">
        
        <div class="pull-right">
            <h4> Total de processos : {{$totalCourtCase ?? ''}} </h4>
        </div>

    </div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">

                    <table id="client_case_listDatatable" class="table"
                           data-url="{{ url('cliente/client_case_list') }}">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th data-orderable="false">Detalhes do processo</th>
                            <th data-orderable="false">Detalhes do Tribunal</th>
                            <th data-orderable="false">Parte contr&aacute;ria</th>
                            <th data-orderable="false">Estado</th>
                            <th data-orderable="false">Ac&ccedil;&atilde;o</th>
                        </tr>
                        </thead>

                    </table>
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

@endsection

@push('js')
<script src="{{asset('assets/cliente/case-client-datatable.js')}}"></script>

@endpush
