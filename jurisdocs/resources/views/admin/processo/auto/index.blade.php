@extends('admin.layout.app')
@section('title','Documentos')
@section('content')

@component('component.heading' , [
'page_title' => 'Documentos - processo: '. str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA',
'action' => route('processo.add.docs', encrypt($processo->id)) ,
'text' => 'Adicionar documento',
'permission' => '1'
])
@endcomponent
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <table id="tb_autos" class="table" data-url="{{ route('auto.list', $processo->id) }}">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Descri&ccedil;&atilde;o</th>
                        <th width="40%">Data da cria&ccedil;&atilde;o</th>
                        <th data-orderable="false">Ac&ccedil;&atilde;o</th>
                    </tr>
                </thead>
                
            </table>
        </div>

    </div>
</div>

<div id="load-modal"></div>

@endsection

@push('js')

<script src="{{asset('assets/js/configuracoes/auto-datatable.js')}}"></script>

@endpush
