<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('verificar.telemovel',$cliente->id)}}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            
            <input type="hidden" name="cod_cliente" id="cod_cliente" value="{{$cliente->id}}">
                   
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Verificar telem&oacute;vel</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="telemovel">Telem&oacute;vel</label>
                            <input type="text" name="telemovel" value="{{ $cliente->telefone ?? '' }}" class="form-control" readonly>
                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="codigo_verificacao">C&oacute;digo de verifica&ccedil;&atilde;o <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_verificacao" class="form-control" id="codigo_verificacao" required>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                class="ik ik-x"></i>{{__('Close')}}
                        </button>
                        
                        <button type="button" class="btn btn-primary" id="new_code">
                                Solicitar novo c&oacute;digo
                        </button>
                        
                        <button type="submit" class="btn btn-success shadow">
                            <i class=" fa fa-save  ik ik-check-circle" id="cl"></i>Verificar
                        </button>
                    </div>

                </div>
        </form>
    </div>
</div>
</div>


<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="url_setcodigo"
       id="url_setcodigo"
       value="{{ route('set.codigo') }}">

<script src="{{asset('assets/js/cliente/verificar_telemovel.js')}}"></script>




