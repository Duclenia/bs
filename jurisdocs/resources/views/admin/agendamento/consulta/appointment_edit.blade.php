@extends('admin.layout.app')
@section('title', __('Appointment Edit'))

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Editar Agendamento</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                <a href="{{ route('agenda.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">
                <div class="x_content">
                    <form id="add_appointment" name="add_appointment" role="form" method="POST"
                        action="{{ route('agenda.update', $appointment->id) }}">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="x_content">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif


                                <div class="row">
                                    <div class="form-group col-md-6">

                                        <input type="radio" id="test4" value="exists" name="type"
                                            @if ($appointment->type) checked @endif>

                                        <b> Cliente existente </b>
                                    </div>
                                </div>
                                <br>
                                <div class="row exists">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            @if (count($client_list) > 0)
                                                <label class="discount_text">Seleccionar cliente
                                                    <er class="rest">*</er>
                                                </label>
                                                <select class="form-control selct2-width-100" name="exists_client"
                                                    id="exists_client" onchange="getMobileno(this.value);">
                                                    <option value="">Seleccionar cliente</option>
                                                    @foreach ($client_list as $list)
                                                        <option value="{{ $list->id }}"
                                                            @if (!empty($appointment->cliente_id) && $appointment->cliente_id == $list->id) selected @endif>
                                                            {{ str_pad($list->id, 5, '0', STR_PAD_LEFT) . ' - ' . $list->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                        </div>
                                    </div>
                                </div>


                                <div class="row new">
                                    <div class="col-md-12 form-group">
                                        <label for="newclint_name">Nome do cliente <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="new_client" name="new_client"
                                            autocomplete="off" value="{{ old('new_client', $appointment->nome ?? null) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="tipo_consulta">Tipo de Consulta <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="tipo_consulta" name="vc_tipo" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Primeira consulta"
                                                {{ old('vc_tipo', $appointment->vc_tipo ?? null) == 'Primeira consulta' ? 'selected' : '' }}>
                                                Primeira consulta</option>
                                            <option value="Consulta de seguimento"
                                                {{ old('vc_tipo', $appointment->vc_tipo ?? null) == 'Consulta de seguimento' ? 'selected' : '' }}>
                                                Consulta de seguimento
                                            </option>
                                            <option value="Consulta urgente"
                                                {{ old('vc_tipo', $appointment->vc_tipo ?? null) == 'Consulta urgente' ? 'selected' : '' }}>
                                                Consulta urgente</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="">Área do Direito <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="areas_direito" name="vc_area" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Família e Sucessões"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Família e Sucessões' ? 'selected' : '' }}>
                                                Família e Sucessões</option>
                                            <option value="Direito Penal"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito Penal' ? 'selected' : '' }}>
                                                Direito Penal</option>
                                            <option value="Penal Económico / Tributário"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Penal Económico / Tributário' ? 'selected' : '' }}>
                                                Penal Económico / Tributário</option>
                                            <option value="Direito Civil"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito Civil' ? 'selected' : '' }}>
                                                Direito Civil</option>
                                            <option value="Direito do Trabalho e Segurança Social"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito do Trabalho e Segurança Social' ? 'selected' : '' }}>
                                                Direito do Trabalho e Segurança Social</option>
                                            <option value="Direito Societário / Comercial"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito Societário / Comercial' ? 'selected' : '' }}>
                                                Direito Societário / Comercial</option>
                                            <option value="Direito Fiscal / Aduaneiro"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito Fiscal / Aduaneiro' ? 'selected' : '' }}>
                                                Direito Fiscal / Aduaneiro</option>
                                            <option value="Propriedade Intelectual"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Propriedade Intelectual' ? 'selected' : '' }}>
                                                Propriedade Intelectual</option>
                                            <option value="Responsabilidade Financeira e Direito Financeiro"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Responsabilidade Financeira e Direito Financeiro' ? 'selected' : '' }}>
                                                Responsabilidade Financeira e Direito
                                                Financeiro</option>
                                            <option value="Direito do Contencioso Administrativo"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito do Contencioso Administrativo' ? 'selected' : '' }}>
                                                Direito do Contencioso Administrativo</option>
                                            <option value="Contencioso Fiscal e Aduaneiro"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Contencioso Fiscal e Aduaneiro' ? 'selected' : '' }}>
                                                Contencioso Fiscal e Aduaneiro</option>
                                            <option value="Direito Financeiro, Mercados Imobiliários e Valores Mobiliários"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Direito Financeiro, Mercados Imobiliários e Valores Mobiliários' ? 'selected' : '' }}>
                                                Direito Financeiro,
                                                Mercados Imobiliários e Valores Mobiliários</option>
                                            <option value="Mediação e Arbitragem"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Mediação e Arbitragem' ? 'selected' : '' }}>
                                                Mediação e Arbitragem</option>
                                            <option value="Assessoria Jurídica Preventiva"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Assessoria Jurídica Preventiva' ? 'selected' : '' }}>
                                                Assessoria Jurídica Preventiva</option>
                                            <option value="Outro"
                                                {{ old('vc_area', $appointment->vc_area ?? null) == 'Outro' ? 'selected' : '' }}>
                                                Outro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group" id="vc_area_outro" style="display: none;">
                                        <label for="outra_area_direito">Especifique a outra área do Direito:</label>
                                        <input type="text" class="form-control" name="vc_area_outro"
                                            value="{{ old('vc_area_outro', $appointment->vc_area_outro ?? null) }}">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="vc_motivo">Nota síntese / Principal preocupação <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="vc_motivo" name="vc_nota" rows="4" required>{{ old('vc_nota', $appointment->vc_nota ?? null) }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="date">Data preferencial <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data" name="date"
                                            value="{{ old('data', $appointment->data ?? null) }}" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="time">Hora preferencial <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="hora" name="time"
                                            value="{{ old('time', $appointment->hora ?? null) }}" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="vc_plataforma">Plataforma preferida <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Google Meet"
                                                {{ old('vc_pataforma', $appointment->vc_pataforma ?? null) == 'Google Meet' ? 'selected' : '' }}>
                                                Google Meet
                                            </option>
                                            <option value="Zoom"
                                                {{ old('vc_pataforma', $appointment->vc_pataforma ?? null) == 'Zoom' ? 'selected' : '' }}>
                                                Zoom</option>
                                            <option value="Teams"
                                                {{ old('vc_pataforma', $appointment->vc_pataforma ?? null) == 'Teams' ? 'selected' : '' }}>
                                                Teams</option>
                                            <option value="Chamada Telefónica"
                                                {{ old('vc_pataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                                                Chamada Telefónica</option>
                                            <option value="Presencial"
                                                {{ old('vc_pataforma') == 'Presencial' ? 'selected' : '' }}>Presencial
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" id="div_link_acesso">
                                        <label for="vc_link_acesso">Link de Acesso</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('link_reuniao', $appointment->link_reuniao ?? null) }}"
                                            id="vc_link_acesso" name="vc_link_acesso" readonly>
                                    </div>



                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label> Deseja enviar documentos antes da consulta?</label><br>
                                        <label><input type="radio" name="it_envDocs" value="1"
                                                class="form-control" id="radio-sim">
                                            Sim</label>
                                        <label><input type="radio" name="it_envDocs" value="0"
                                                class="form-control" id="radio-nao">
                                            Não</label>
                                    </div>

                                    <div class="col-md-6 form-group" id="documento-input" style="display: none;">
                                        <label for="documento">Selecione o Documento:</label>
                                        <input type="file" name="documento" class="form-control file">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="it_termo"
                                                id="it_termo" value="1" required
                                                {{ old('it_termo') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="it_termo">
                                                Autorizo o tratamento dos meus dados e aceito os termos da política
                                                de privacidade.
                                                <a href="#" data-toggle="modal" data-target="#termosModal"
                                                    style="color: #3f6fb3">Ver
                                                    termos</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <br>
                                    <a href="{{ route('consulta.index') }}"
                                        class="btn btn-danger">{{ __('Cancel') }}</a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save" id="show_loader"></i>&nbsp;{{ __('Save') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="date_format_datepiker" id="date_format_datepiker" value="{{ $date_format_datepiker }}">

    <input type="hidden" name="getMobileno" id="getMobileno" value="{{ route('getMobileno') }}">

    <input type="hidden" name="type_chk" id="type_chk" value="{{ $appointment->type }}">

@endsection

@push('js')
    <script src="{{ asset('assets/admin/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/appointment-validation_edit.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
@endpush
