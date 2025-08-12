@extends('admin.layout.app')
@section('title','Bairro')
@section('content')
<div class="">
    @component('component.heading' , [
       'page_title' => 'Escala de Atendimento'
        ])
    @endcomponent
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">

                <div class="x_content">

                    <div class="col-xs-3 form-group">
                        <label for="advogado">Advogado(a) <span class="text-danger">*</span></label><br>
                        <select name="advogado" class="form-control" id="advogado" required>
                            <option value="">Seleccionar</option>
                            @foreach($advogados as $advogado)
                            <option value="{{$advogado->id}}">{{$advogado->nome. ' '.$advogado->nome_meio. ' '.$advogado->sobrenome}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xs-9">

                        <table id="tagDataTable" class="table" data-url="#" >
                            <thead>
                                <tr>
                                    <th>Segunda-Feira</th>
                                    <th>Ter&ccedil;a-Feira</th>
                                    <th>Quarta-Feira</th>
                                    <th>Quinta-Feira</th>
                                    <th>Sexta-Feira</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="load-modal"></div>
@endsection

@push('js')
<script src="{{asset('assets/js/escala_atendimento/escala.js')}}"></script>
@endpush
