@extends('admin.layout.app')
@section('title','Tipo de Tribunal')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Tipo de Tribunal',
             'action'=>route("court-type.create"),
             'model_title'=>'Adicionar tipo de Tribunal',
             'modal_id'=>'#addtag',
              'permission' => $adminHasPermition->can(['court_type_add'])
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('court.type.list') }}"
                               >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tipo de Tribunal</th>
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

    <script src="{{asset('assets/js/configuracoes/cort-type-datatable.js')}}"></script>

@endpush
