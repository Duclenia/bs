@extends('admin.layout.app')
@section('title', 'horario')
@section('content')
    <div class="">

        {{--   @component('component.modal_heading', [
    'page_title' => 'horario',
    'action' => route('horario.create'),
    'model_title' => 'Adicionar horario',
    'modal_id' => '#addtag',
    'permission' => auth()->user()->can('add_horario'),
])
            Status
        @endcomponent --}}
        @component('component.heading', [
            'page_title' => __('horario'),
            'action' => route('horario.create'),
            'text' => __('Adicionar horario'),
            'permission' => auth()->user()->can('add_horario'),
        ])
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="horarioDataTable" class="table" data-url="{{ route('horario.list') }}">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Dia da semana</th>
                                    <th>Dia de Trabalho </th>
                                    <th>Espediente</th>
                                    <th>Intervalo de atendimento</th>
                                    <th>Pausas do Trabalho</th>
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
    <script src="{{ asset('assets/js/configuracoes/horario-datatable.js') }}"></script>
@endpush
