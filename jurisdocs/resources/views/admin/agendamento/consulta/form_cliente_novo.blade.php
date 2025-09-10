<div class="row">
    <div class="col-md-6 form-group">
        <label for="tipo_consulta">Tipo de Consulta <span class="text-danger">*</span></label>
        <select class="form-control" id="tipo_consulta" name="vc_tipo" required>
            <option value="">-- Selecionar --</option>
            <option value="Primeira consulta" {{ old('tipo_consulta') == 'Primeira consulta' ? 'selected' : '' }}>
                Primeira consulta</option>
            <option value="Consulta de seguimento"
                {{ old('tipo_consulta') == 'Consulta de seguimento' ? 'selected' : '' }}>Consulta de seguimento
            </option>
            <option value="Consulta urgente" {{ old('tipo_consulta') == 'Consulta urgente' ? 'selected' : '' }}>
                Consulta urgente</option>
        </select>
    </div>

    <div class="col-md-6 form-group">
        <label for="">Área do Direito <span class="text-danger">*</span></label>
        <select class="form-control select2" id="areas_direito" name="vc_area" required>
            <option value="">-- Selecionar --</option>
            <option value="Família e Sucessões">Família e Sucessões</option>
            <option value="Direito Penal">Direito Penal</option>
            <option value="Penal Económico / Tributário">Penal Económico / Tributário</option>
            <option value="Direito Civil">Direito Civil</option>
            <option value="Direito do Trabalho e Segurança Social">Direito do Trabalho e Segurança Social</option>
            <option value="Direito Societário / Comercial">Direito Societário / Comercial</option>
            <option value="Direito Fiscal / Aduaneiro">Direito Fiscal / Aduaneiro</option>
            <option value="Propriedade Intelectual">Propriedade Intelectual</option>
            <option value="Responsabilidade Financeira e Direito Financeiro">Responsabilidade Financeira e Direito
                Financeiro</option>
            <option value="Direito do Contencioso Administrativo">Direito do Contencioso Administrativo</option>
            <option value="Contencioso Fiscal e Aduaneiro">Contencioso Fiscal e Aduaneiro</option>
            <option value="Direito Financeiro, Mercados Imobiliários e Valores Mobiliários">Direito Financeiro,
                Mercados Imobiliários e Valores Mobiliários</option>
            <option value="Mediação e Arbitragem">Mediação e Arbitragem</option>
            <option value="Assessoria Jurídica Preventiva">Assessoria Jurídica Preventiva</option>
            <option value="Outro">Outro</option>
        </select>
    </div>

    <div class="col-md-6 form-group" id="vc_area_outro" style="display: none;">
        <label for="outra_area_direito">Especifique a outra área do Direito:</label>
        <input type="text" class="form-control" name="vc_area_outro">
    </div>

</div>
<div class="row">
    <div class="col-md-6">
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

    @if (auth()->user()->user_type === 'SuperAdmin')
        <div class="col-md-6 form-group">
            <label for="custo">Custo da Consulta *</label>
            <input type="number" class="form-control" id="custo" name="custo" step="0.01" min="0"
                value="{{ old('custo') }}" placeholder="0.00">
        </div>
    @endif

</div>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="vc_motivo">Nota síntese / Principal preocupação <span class="text-danger">*</span></label>
        <textarea class="form-control" id="vc_motivo" name="vc_nota" rows="4" required>{{ old('vc_motivo') }}</textarea>
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

            <option value="zoom" {{ old('vc_plataforma') == 'zoom' ? 'selected' : '' }}>Zoom</option>

            <option value="Chamada Telefónica" {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                Chamada Telefónica</option>
            <option value="Presencial" {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>Presencial
            </option>
        </select>
    </div>



</div>


<br>
<div class="row">
    <div class="col-md-6 form-group">
        <label> Deseja enviar documentos antes da consulta?</label><br>
        <label><input type="radio" name="it_envDocs" value="1" class="form-control" id="radio-sim">
            Sim</label>
        <label><input type="radio" name="it_envDocs" value="0" class="form-control" id="radio-nao">
            Não</label>
    </div>

    <div class="col-md-6 form-group" id="documento-input" style="display: none;">
        <label for="documento">Selecione o Documento:</label>
        <input type="file" name="vc_doc" class="form-control file">
    </div>
</div>

<div class="row">
    <div class="col-md-12 form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="it_termo" id="it_termo" value="1" required
                {{ old('it_termo') ? 'checked' : '' }}>
            <label class="form-check-label" for="it_termo">
                Autorizo o tratamento dos meus dados e aceito os termos da política
                de privacidade.
                <a href="#" data-toggle="modal" data-target="#termosModal" style="color: #3f6fb3">Ver
                    termos</a>
            </label>
        </div>
    </div>
</div>
