@extends('admin.layout.app')
@section('title','Juiz')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Juiz',
             'action'=>route("juiz.create"),
             'model_title'=>'Adicionar juiz',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('judge_add')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('judge.list') }}">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Juiz</th>
                                <th>Tribunal</th>
                                <th>Sec&ccedil;&atilde;o</th>
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
    <script src="{{asset('assets/js/configuracoes/judge-datatable.js')}}"></script>
@endpush
