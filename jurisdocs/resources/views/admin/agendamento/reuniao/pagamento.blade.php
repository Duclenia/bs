@if (auth()->user()->user_type === 'SuperAdmin')
    <div class="col-md-6 form-group">
        <label for="custo">Custo da Reunião *</label>
        <input type="number" class="form-control" id="custo" name="custo" step="0.01" min="0"
            value="{{ old('custo') }}" placeholder="0.00">
    </div>
@endif

<div class="col-md-6">
    <div class="form-group">
        <label>Forma de Pagamento <span class="required">*</span></label>
        <select class="form-control" name="forma_pagamento" id="forma_pagamento" required>
            <option value="">Selecione...</option>
            <option value="dinheiro">Dinheiro</option>
            <option value="transferencia">Transferência Bancária</option>
            <option value="multicaixa">Multicaixa Express</option>
            <option value="cheque">Cheque</option>
        </select>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label>Observações sobre o Pagamento</label>
        <textarea class="form-control" name="observacoes_pagamento" id="observacoes_pagamento" rows="3"
            placeholder="Informações adicionais sobre o pagamento..."></textarea>
    </div>
</div>

<div class="col-md-12">
    <div class="contct-info">
        <div class="form-group">
            <label>Comprovativo (PDF)</label>
            <input type="file" class="form-control" id="comprovativo" name="comprovativo" accept=".pdf">
            <small class="text-muted">Apenas arquivos PDF são aceitos (máx. 10MB)</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="it_termo" id="it_termo" value="1"
                {{ old('it_termo') ? 'checked' : '' }}>
            <label class="form-check-label" for="it_termo">
                Autorização para tratamento de dados pessoais. Autorizo o tratamento dos meus dados
                para fins de
                agendamento e
                comunicação.
                <a href="#" data-toggle="modal" data-target="#termosModal" style="color: #3f6fb3">Ver
                    termos</a>
            </label>
        </div>
    </div>
</div>
