<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="clientPaymentreceivemodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Adicionar Pagamento</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label for="fullname">Valor <span class="text-danger">*</span></label>
                        <input type="text" placeholder="" class="form-control">
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label for="fullname">Forma de pagamento <span class="text-danger">*</span></label>
                        <select class="form-control">
                            <option>Deposito</option>
                            <option>Transferência</option>

                        </select>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label for="fullname">N&uacute;mero de refer&ecirc;ncia <span
                                class="text-danger"></span></label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Comprovativo (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="comprovativo" name="comprovativo" accept=".pdf"
                            required>
                        <small class="text-muted">Apenas arquivos PDF são aceitos (máx. 10MB)</small>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label for="fullname">{{ __('Note') }} <span class="text-danger"></span></label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('Save') }}</button>
                </div>

            </div>
        </div>
    </div>
</div>
