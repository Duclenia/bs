@extends('admin.layout.app')
@section('title','Tipo de crime')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Tipo de crime',
             'action'=>route("crime.create"),
             'model_title'=>'Novo tipo de crime',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('adicionar_tipo_crime')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('crime.list') }}">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tipo de crime</th>
                                <th>Artigo</th>
                                <th>Enquadramento</th>
                                <th>Sub-enquadramento</th>
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

    <script src="{{asset('assets/js/configuracoes/crime-datatable.js')}}"></script>
@endpush
