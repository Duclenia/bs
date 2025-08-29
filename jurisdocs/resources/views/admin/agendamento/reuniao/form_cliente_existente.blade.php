<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @if (!empty($client_list) && count($client_list) > 0)
                <label class="discount_text">Seleccionar cliente
                    <er class="rest">*</er>
                </label>
                <select class="form-control selct2-width-100" name="exists_client" id="exists_client"
                    onchange="getMobileno(this.value);">
                    <option value="">Seleccionar cliente</option>
                    @foreach ($client_list as $list)
                        <option value="{{ $list->id }}">
                            {{ str_pad($list->id, 5, '0', STR_PAD_LEFT) . ' - ' . $list->full_name }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="col-md-6 form-group">
        <label for="mobile">Contacto telefónico (opcional)</label>
        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}"
            autocomplete="off">
    </div>

    <div class="col-md-6 form-group">
        <label for="email">Endereço de e-mail <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
            autocomplete="off">
    </div>
    <div class="row">
        <div class="col-md-12 form-group">
            <label for="vc_entidade">Entidade / Organização (opcional)</label>
            <input type="text" class="form-control" id="vc_entidade" name="vc_entidade"
                value="{{ old('vc_entidade') }}">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="vc_motivo">Motivo da reunião <span class="text-danger">*</span></label>
        <textarea class="form-control" id="vc_motivo" name="vc_motivo" rows="3" required>{{ old('vc_motivo') }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        <label for="date">{{ __('Data Preferencial') }} <span class="text-danger">*</span></label>
        <input type="text" id="data" class="form-control" name="date" value="{{ old('date') }}" required>

    </div>

    <div class="col-md-4 form-group">
        <label for="time">{{ __('Horario Preferencial') }} <span class="text-danger">*</span></label>
        <select class="form-control" name="time" id="hora" required></select>
    {{--     <input type="time" class="form-control" id="hora" name="time" value="{{ old('time') }}" required>
    --}} </div>
    <div class="col-md-4 form-group">
        <label for="vc_plataforma">Plataforma preferida <span class="text-danger">*</span></label>
        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
            <option value="">-- Selecionar --</option>
            <option value="Google Meet" {{ old('vc_plataforma') == 'Google Meet' ? 'selected' : '' }}>Google
                Meet
            </option>
            <option value="zoom" {{ old('vc_plataforma') == 'Zoom' ? 'selected' : '' }}>Zoom
            </option>

            <option value="Chamada Telefónica" {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                Chamada Telefónica</option>
            <option value="Presencial" {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>
                Presencial</option>
        </select>
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
