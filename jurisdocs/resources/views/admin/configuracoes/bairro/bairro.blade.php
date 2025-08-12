@extends('admin.layout.app')
@section('title','Bairro')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'bairro',
             'action'=>route("bairro.create"),
             'model_title'=>'Adicionar Bairro',
             'modal_id'=>'#addtag',
              'permission' => auth()->user()->can('add_bairro')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('bairro.list') }}" >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Bairro</th>
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
    <script src="{{asset('assets/js/configuracoes/bairro-datatable.js')}}"></script>

@endpush
