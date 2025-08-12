@extends('admin.layout.app')
@section('title','Tipo de processo')
@section('content')
    <div class="">
        @component('component.modal_heading',
             [
             'page_title' => 'Tipo de Processo',
             'action'=>route("case-type.create"),
             'model_title'=>'Adicionar tipo de processo',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('case_type_add')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('cash.type.list') }}"
                               >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tipo de Processo</th>
                                <th width="5%" data-orderable="false">Estado</th>
                                <th width="2%" data-orderable="false" class="text-center">Ac&ccedil;&atilde;o</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="load-modal"></div>
@endsection

@push('js')
    <script src="{{asset('assets/js/configuracoes/case-type-datatable.js')}}"></script>

@endpush
