@extends('admin.layout.app')
@section('title','Planos')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Planos',
             'action'=>route("plano.create"),
             'model_title'=>'Adicionar Plano',
             'modal_id'=>'#addtag',
              'permission' => auth()->user()->can('add_plano')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('plano.list') }}" >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Plano</th>
                                <th>Valor mensal</th>
                                <th>Total de processos</th>
                                <th>Utilizadores</th>
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
<script src="{{asset('assets/js/plano/plano-datatable.js')}}"></script>

@endpush
