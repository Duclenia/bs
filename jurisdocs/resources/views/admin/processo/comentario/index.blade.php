@extends('admin.layout.app')
@section('title','Comentários')
@section('content')

<div class="form-group pull-right top_search">
    <a href="{{route('processo.index')}}" class="btn btn-primary">Voltar</a>
</div>

@component('component.modal_heading',
[
'page_title' => 'Comentários',
'action'=> route('processo.create.coment', encrypt($processo->id)),
'model_title'=> 'Adicionar comentário',
'modal_id'=>'#addtag',
'permission' => '1'
] )
Status
@endcomponent

<div class="row">

    Processo: {{str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA'}}

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <table id="tb_comentarios" class="table" data-url="{{ route('get.comentarios', $processo->id) }}">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Descri&ccedil;&atilde;o</th>
                        <th width="40%">Data da cria&ccedil;&atilde;o</th>
                        <th width="40%">Autor</th>
                        <th>Ac&ccedil;&atilde;o</th>
                    </tr>
                </thead>

            </table>
        </div>

    </div>
</div>

<div id="load-modal"></div>

<input type="hidden" id="cod_processo" value="{{$processo->id}}">

@endsection

@push('js')

<script src="{{asset('assets/js/processo/comentario/comentario-datatable.js')}}"></script>

@endpush
