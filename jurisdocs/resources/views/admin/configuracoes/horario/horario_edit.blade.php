<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('horario.update',$horario->id)}}" method="POST" id="tagForm" name="tagForm">
            <input type="hidden" id="id" name="id" value="{{$horario->id ?? ''}}">
            @csrf()
            @method('patch')
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar horario</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="horario">horario <span class="text-danger">*</span></label>
                            <input type="text" value="{{ $horario->nome ?? '' }}"
                                   class="form-control" id="horario" name="horario" required autocomplete="off">
                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="municipio">Munic&iacute;pio <span class="text-danger">*</span></label>
                            <select name="municipio[]" multiple class="form-control" id="municipio" required>
                                @foreach($municipios as $municipio)
                                <option value="{{$municipio->id}}" @if($horario->municipios->contains($municipio)) selected @endif >{{$municipio->nome}}</option>
                                @endforeach
                            </select>
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

<input type="hidden" name="common_check_exist"
       id="common_check_exist"
       value="{{ route('horario_check_exist') }}">

<script src="{{asset('assets/js/configuracoes/horario_validation_edit.js')}}"></script>


