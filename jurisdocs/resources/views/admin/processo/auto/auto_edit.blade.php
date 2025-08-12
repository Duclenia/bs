<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('auto.update',$auto->id)}}" method="POST" id="tagForm" name="tagForm" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="{{$auto->id ?? ''}}">
            @csrf()
            @method('patch')
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar Auto</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">
                       
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="descricao">Descri&ccedil;&atilde;o <span class="text-danger">*</span></label>
                            <input type="text" value="{{ $auto->descricao ?? '' }}"
                                   class="form-control" id="descricao" name="descricao" required autocomplete="off">
                        </div>
                        
                      
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="auto">Anexar documento </label>
                            <input type="file" class="form-control" name="auto" id="auto" accept="application/pdf">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="ik ik-x"></i>Fechar
                    </button>
                    <button type="submit" class="btn btn-success shadow"><i class=" fa fa-save  ik ik-check-circle"
                                                                            id="cl">
                        </i> Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">


<script src="{{asset('assets/js/processo/auto/auto_validation_edit.js')}}"></script>


