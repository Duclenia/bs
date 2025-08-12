@extends('admin.layout.app')
@section('title','Subscrições')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Subscrições',
             'action'=>route("subscricao.create"),
             'model_title'=>'Adicionar Subscrição',
             'modal_id'=>'#addtag',
              'permission' => auth()->user()->can('add_subscricao')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('subscricao.list') }}" >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Plano</th>
                                <th>In&iacute;cio</th>
                                <th>Termino</th>
                                <th>Periodicidade</th>
                                <th>Total de processos</th>
                                <th>Proc. registados</th>
                                <th>Estado</th>
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
<script src="{{asset('assets/js/subscricao/subscricao-datatable.js')}}"></script>

@endpush
