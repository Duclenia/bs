<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('comentario.update',$comentario->id)}}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            @method('patch')
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar coment&aacute;rio</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">
                       
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="processo">Processo </label>
                            <input type="text" value="{{ str_pad($comentario->processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA'  ?? '' }}" readonly
                                   class="form-control" id="processo" name="processo" required>
                        </div>
                        
                      
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="comentario">Coment&aacute;rio <span class="text-danger">*</span></label>
                            <textarea name="comentario" class="form-control" id="comentario" required>
                                {{$comentario->conteudo}}
                            </textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="ik ik-x"></i>{{__('Close')}}
                    </button>
                    <button type="submit" class="btn btn-success shadow"><i class=" fa fa-save  ik ik-check-circle"
                                                                            id="cl">
                        </i> {{__('Save')}}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<script src="{{asset('assets/js/processo/comentario/comentario-validation.js')}}"></script>


