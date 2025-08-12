@extends('admin.layout.app')
@section('title','Adicionar documento')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>{!!'Add documento - processo: '. str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA' !!}</h3>
    </div>

    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{route('processo.docs', encrypt($processo->id))}}" class="btn btn-primary">Voltar</a>

        </div>
    </div>
</div>
<!------------------------------------------------ ROW 1-------------------------------------------- -->


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form method="post" name="add_docs" class="form-horizontal" id="add_docs" action="{{route('processo.inserir.auto', encrypt($processo->id) )}}" enctype="multipart/form-data">
                @csrf()
                <div class="x_content">

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="descricao">Descri&ccedil;&atilde;o <span class="text-danger">*</span>
                        </label>

                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <input type="text" class="form-control" name="descricao[]" id="descricao1" data-placeholder="Por favor, insere a descrição" required>

                            <span id="msgErroDescricao1" class='erro'></span>
                        </div>

                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary add_field_button tooltips" title="Adicionar documento"  data-placement="right" data-toggle="tooltip">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                        </div>                      
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="auto">Anexar documento <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <input type="file" class="form-control" name="autos[]" id="auto1" required accept="application/pdf">

                            <span id="msgErroAuto1" class="erro"></span>

                        </div>                        
                    </div>  <hr>

                    <div class="input_fields_wrap"></div>

                    <div class="row">

                        <div class="form-group pull-right">
                            <div class="col-md-12 col-sm-6 col-xs-12">

                                <a class="btn btn-danger" href="{{route('processo.docs', encrypt($processo->id))}}">Cancelar</a>
                                <button type="submit" class="btn btn-success"><i class="fa fa-save" id="show_loader"></i>&nbsp;Guardar
                                </button>
                            </div>

                        </div>
                        <br>

                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!------------------------------------------------------- End ROw ----------------------------------------             ----->




@endsection

@push('js')
<script src="{{asset('assets/js/processo/auto/inserir-anexo.js')}}"></script>

@endpush
