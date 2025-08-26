<div class="row">
    <div class="col-md-12 form-group">
        <label for="vc_motivo">Motivo da reunião <span class="text-danger">*</span></label>
        <textarea class="form-control" id="vc_motivo" name="vc_motivo" rows="3" required>{{ old('vc_motivo') }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        <label for="data2">{{ __('Data Preferencial') }} <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="data2" name="date" value="{{ old('date') }}" required>
    </div>

    <div class="col-md-4 form-group">
        <label for="hora2">{{ __('Horario Preferencial') }} <span class="text-danger">*</span></label>
        <input type="time" class="form-control" id="hora2" name="time" value="{{ old('time') }}" required>
    </div>
    <div class="col-md-4 form-group">
        <label for="vc_plataforma">Plataforma preferida <span class="text-danger">*</span></label>
        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
            <option value="">-- Selecionar --</option>
            <option value="Google Meet" {{ old('vc_plataforma') == 'Google Meet' ? 'selected' : '' }}>Google Meet
            </option>
            <option value="Zoom" {{ old('vc_plataforma') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
           
            <option value="Chamada Telefónica" {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                Chamada Telefónica</option>
            <option value="Presencial" {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
        </select>
    </div>
    <div class="col-md-4 form-group" id="div_link_acesso" style="display: none;">
        <label for="vc_link_acesso">Link de Acesso</label>
        <input type="text" class="form-control" id="vc_link_acesso" name="vc_link_acesso" readonly>
    </div>


</div>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="vc_nota">Nota adicional (opcional)</label>
        <textarea class="form-control" id="vc_nota" name="vc_nota" rows="3">{{ old('vc_nota') }}</textarea>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="it_termo" id="it_termo" value="1"
                {{ old('it_termo') ? 'checked' : '' }}>
            <label class="form-check-label" for="it_termo">
                Autorização para tratamento de dados pessoais. Autorizo o tratamento dos meus dados para fins de
                agendamento e
                comunicação.
                <a href="#" data-toggle="modal" data-target="#termosModal" style="color: #3f6fb3">Ver termos</a>
            </label>
        </div>
    </div>
</div>
