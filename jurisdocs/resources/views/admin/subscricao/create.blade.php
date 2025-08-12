<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{ route('subscricao.store') }}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Adicionar Subscri&ccedil;&atilde;o</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="plano">Plano <span class="text-danger">*</span></label>
                            <select name="plano" class="form-control" id="plano" required>
                                <option value="" selected disabled>{{__('Select')}}</option>
                                @foreach($planos as $plano)
                                <option value="{{$plano->id}}">{{$plano->nome}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="periodicidade">Periodicidade <span class="text-danger">*</span></label>
                            <select name="periodicidade" class="form-control" id="periodicidade" required>
                                <option value="" selected disabled>{{__('Select')}}</option>
                                <option value="A">Anual</option>
                                <option value="M">Mensal</option>
                                <option value="T">Trimestral</option>
                            </select>
                        </div>

                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="data_inicio">Data de in&iacute;cio <span class="text-danger">*</span></label>
                            <input type="text" name="data_inicio" class="form-control" id="data_inicio" autocomplete="off" readonly required>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="ik ik-x"></i>{{__('Close')}}
                    </button>
                    <button type="submit" class="btn btn-success shadow"><i class=" fa fa-save ik ik-check-circle"
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

<script src="{{asset('assets/js/subscricao/create.js')}}"></script> 





