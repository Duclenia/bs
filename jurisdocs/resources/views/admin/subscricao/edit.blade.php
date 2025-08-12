<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('subscricao.update',$subscricao->id)}}" method="POST" id="tagForm" name="tagForm">
            
            @csrf()
            @method('patch')
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar Subscri&ccedil;&atilde;o</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="plano">Plano <span class="text-danger">*</span></label>
                            <select name="plano" class="form-control" id="plano" required>
                                @foreach($planos as $plano)
                                <option value="{{$plano->id}}" @if($plano->id == $subscricao->plano_id) selected @endif >{{$plano->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                       
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="periodicidade">Periodicidade <span class="text-danger">*</span></label>
                            <select name="periodicidade" class="form-control" id="periodicidade" required>
                                
                                <option value="A" @if($subscricao->periodicidade == 'A') selected @endif >Anual</option>
                                <option value="M" @if($subscricao->periodicidade == 'M') selected @endif>Mensal</option>
                                <option value="T" @if($subscricao->periodicidade == 'T') selected @endif >Trimestral</option>
                                
                            </select>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="data_inicio">Data de in&iacute;cio <span class="text-danger">*</span></label>
                            <input type="text" name="data_inicio" value="{{ date($date_format_laravel, strtotime($subscricao->data_inicio)) }}" readonly class="form-control" id="data_inicio" required>
                               
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

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">

<script src="{{asset('assets/js/subscricao/edit.js')}}"></script>



