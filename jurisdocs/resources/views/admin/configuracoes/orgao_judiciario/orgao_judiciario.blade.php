@extends('admin.layout.app')
@section('title','Órgão judiciário')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Órgão judiciário',
             'action'=>route("orgao-judiciario.create"),
             'model_title'=>'Órgão judiciário',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('add_orgao_judiciario')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('orgao.judiciario.list') }}">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Designa&ccedil;&atilde;o</th>
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

    <script src="{{asset('assets/js/configuracoes/orgao-judiciario-datatable.js')}}"></script>
@endpush
