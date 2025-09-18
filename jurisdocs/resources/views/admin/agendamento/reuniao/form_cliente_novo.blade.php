<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @if (!empty($advogado_list) && count($advogado_list) > 0)
                <label class="discount_text">Seleccionar o Advogado
                    <er class="rest">*</er>
                </label>
                <select class="form-control selct2-width-100" name="advogado_id" id="select_advogado_exist">
                    <option value="">Seleccionar Advogado</option>
                    @foreach ($advogado_list as $list)
                        <option value="{{ $list->id }}">
                            {{ str_pad($list->id, 5, '0', STR_PAD_LEFT) . ' - ' . $list->nome . ' ' . $list->sobrenome }}
                        </option>
                    @endforeach
                </select>
            @endif
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
        <label for="data2">{{ __('Data Preferencial') }} <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="data2" name="date" value="{{ old('date') }}" required>
    </div>

    <div class="col-md-4 form-group">
        <label for="hora2">{{ __('Horario Preferencial') }} <span class="text-danger">*</span></label>
        <select class="form-control" name="time" id="hora2" required></select>
    </div>
    <div class="col-md-4 form-group">
        <label for="vc_plataforma">Plataforma preferida <span class="text-danger">*</span></label>
        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
            <option value="">-- Selecionar --</option>

            <option value="zoom" {{ old('vc_plataforma') == 'Zoom' ? 'selected' : '' }}>Zoom</option>

            <option value="Chamada Telefónica" {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                Chamada Telefónica</option>
            <option value="Presencial" {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
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

