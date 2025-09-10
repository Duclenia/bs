@extends('admin.layout.app')
@section('title', 'Agendar')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Criar Agenda para Consulta</h3>
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
                        action="{{ route('cliente.agenda.store') }}" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="x_content">
                                <div class="col-md-12 form-group">
                                    <label for="mobile">Nome</label>
                                    <input type="text" class="form-control" id="exists_client" name="instituicao"
                                        value="{{ old('exists_client', Auth::user()->cliente->nome . ' ' . Auth::user()->cliente->sobrenome) }}"
                                        autocomplete="off" readonly>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="mobile">Contacto telefónico (opcional)</label>
                                    <input type="number" class="form-control" id="mobile" name="mobile"
                                        value="{{ old('mobile', Auth::user()->cliente->telefone) }}" autocomplete="off"
                                        readonly>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="email">Endereço de e-mail <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::User()->email) }}" autocomplete="off" readonly>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            @if (!empty($advogado_list) && count($advogado_list) > 0)
                                                <label class="discount_text">Seleccionar o Advogado
                                                    <er class="rest">*</er>
                                                </label>
                                                <select class="form-control selct2-width-100" name="advogado_id"
                                                    id="select_advogado">
                                                    <option value="">Seleccionar Advogado</option>
                                                    @foreach ($advogado_list as $list)
                                                        <option value="{{ $list->id }}"
                                                            {{ isset($advogado_selecionado) && $advogado_selecionado == $list->id ? 'selected' : '' }}>
                                                            {{ str_pad($list->id, 5, '0', STR_PAD_LEFT) . ' - ' . $list->nome . ' ' . $list->sobrenome }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="tipo_consulta">Tipo de Consulta <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="tipo_consulta" name="vc_tipo" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Primeira consulta"
                                                {{ old('tipo_consulta') == 'Primeira consulta' ? 'selected' : '' }}>
                                                Primeira consulta</option>
                                            <option value="Consulta de seguimento"
                                                {{ old('tipo_consulta') == 'Consulta de seguimento' ? 'selected' : '' }}>
                                                Consulta de seguimento
                                            </option>
                                            <option value="Consulta urgente"
                                                {{ old('tipo_consulta') == 'Consulta urgente' ? 'selected' : '' }}>
                                                Consulta urgente</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="">Área do Direito <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="areas_direito" name="vc_area" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Família e Sucessões">Família e Sucessões</option>
                                            <option value="Direito Penal">Direito Penal</option>
                                            <option value="Penal Económico / Tributário">Penal Económico / Tributário
                                            </option>
                                            <option value="Direito Civil">Direito Civil</option>
                                            <option value="Direito do Trabalho e Segurança Social">Direito do Trabalho e
                                                Segurança Social</option>
                                            <option value="Direito Societário / Comercial">Direito Societário / Comercial
                                            </option>
                                            <option value="Direito Fiscal / Aduaneiro">Direito Fiscal / Aduaneiro</option>
                                            <option value="Propriedade Intelectual">Propriedade Intelectual</option>
                                            <option value="Responsabilidade Financeira e Direito Financeiro">
                                                Responsabilidade Financeira e Direito
                                                Financeiro</option>
                                            <option value="Direito do Contencioso Administrativo">Direito do Contencioso
                                                Administrativo</option>
                                            <option value="Contencioso Fiscal e Aduaneiro">Contencioso Fiscal e Aduaneiro
                                            </option>
                                            <option value="Direito Financeiro, Mercados Imobiliários e Valores Mobiliários">
                                                Direito Financeiro,
                                                Mercados Imobiliários e Valores Mobiliários</option>
                                            <option value="Mediação e Arbitragem">Mediação e Arbitragem</option>
                                            <option value="Assessoria Jurídica Preventiva">Assessoria Jurídica Preventiva
                                            </option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group" id="vc_area_outro" style="display: none;">
                                        <label for="outra_area_direito">Especifique a outra área do Direito:</label>
                                        <input type="text" class="form-control" name="vc_area_outro">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="vc_motivo">Nota síntese / Principal preocupação <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="vc_motivo" name="vc_nota" rows="4" required>{{ old('vc_motivo') }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="data2">{{ __('Data Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data" name="date"
                                            value="{{ old('date') }}" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="hora2">{{ __('Horario Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="time" id="hora" required></select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="vc_plataforma">Plataforma preferida <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
                                            <option value="">-- Selecionar --</option>

                                            <option value="zoom" {{ old('vc_plataforma') == 'Zoom' ? 'selected' : '' }}>
                                                Zoom</option>

                                            <option value="Chamada Telefónica"
                                                {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                                                Chamada Telefónica</option>
                                            <option value="Presencial"
                                                {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>Presencial
                                            </option>
                                        </select>
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
                                    <a href="{{ route('agenda.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-success" id="btn_submit" disabled>
                                        <i class="fa fa-save" id="show_loader"></i>&nbsp;{{ __('Save') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="date_format_datepiker" id="date_format_datepiker"
                            value="{{ $date_format_datepiker }}">
                        <input type="hidden" name="type_agenda" id="type_agenda" value="consulta">
                        <input type="hidden" name="getMobileno" id="getMobileno" value="{{ route('getMobileno') }}">

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="termosModal" tabindex="-1" role="dialog" aria-labelledby="termosModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termosModalLabel">Termos de Consentimento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <h6>1. Introdução</h6>
                    <p>Esta Política de Privacidade descreve como coletamos, usamos e protegemos as informações fornecidas
                        por você ao utilizar nossos serviços. Ao aceitar, você concorda com todos os termos aqui descritos.
                    </p>

                    <h6>2. Coleta de Dados</h6>
                    <p>Coletamos informações pessoais como nome, e-mail, telefone e outros dados relevantes para a execução
                        de nossos serviços. Esses dados são fornecidos diretamente por você através de formulários, ou de
                        forma automática, através de cookies e tecnologias semelhantes.</p>

                    <h6>3. Uso das Informações</h6>
                    <p>As informações coletadas serão utilizadas para:
                    <ul>
                        <li>Realizar agendamentos e prestar serviços solicitados;</li>
                        <li>Entrar em contato para confirmar, alterar ou cancelar compromissos;</li>
                        <li>Enviar comunicados importantes relacionados aos serviços;</li>
                        <li>Cumprir obrigações legais e regulatórias.</li>
                    </ul>
                    </p>

                    <h6>4. Compartilhamento de Dados</h6>
                    <p>Não compartilhamos suas informações pessoais com terceiros, exceto:
                    <ul>
                        <li>Quando houver consentimento explícito;</li>
                        <li>Por exigência legal, judicial ou regulatória;</li>
                        <li>Para execução de serviços contratados por você, com parceiros de confiança.</li>
                    </ul>
                    </p>

                    <h6>5. Armazenamento e Segurança</h6>
                    <p>Os dados são armazenados de forma segura e protegidos contra acesso não autorizado. Utilizamos
                        criptografia e protocolos de segurança para preservar a integridade e confidencialidade das
                        informações.</p>

                    <h6>6. Direitos do Usuário</h6>
                    <p>Você tem direito a:
                    <ul>
                        <li>Acessar, corrigir ou excluir seus dados pessoais;</li>
                        <li>Revogar seu consentimento a qualquer momento;</li>
                        <li>Solicitar informações sobre o tratamento dos seus dados.</li>
                    </ul>
                    </p>

                    <h6>7. Alterações nesta Política</h6>
                    <p>Podemos atualizar esta Política de Privacidade periodicamente. Recomendamos que consulte esta página
                        regularmente para se manter informado sobre quaisquer alterações.</p>

                    <h6>8. Contato</h6>
                    <p>Para qualquer dúvida ou solicitação relacionada a esta Política, entre em contato pelo e-mail:
                        <a href="mailto:contato@empresa.com">contato@empresa.com</a>.
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="{{ asset('upload/documento.pdf') }}" download class="btn btn-link">Baixar documento</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const checkbox = document.getElementById('it_termo');
            const btnSubmit = document.getElementById('btn_submit');
            const radioSim = document.getElementById('radio-sim');
            const radioNao = document.getElementById('radio-nao');
            const documentoInput = document.getElementById('documento-input');
            const btnSubmitExisting = document.getElementById('btn_submit_existing');
            const area = document.getElementById('areas_direito');
            const inputArea = document.getElementById('vc_area_outro');
            checkbox.addEventListener('change', function() {
                btnSubmit.disabled = !this.checked;
                btnSubmitExisting.disabled = !this.checked;
            });

            area.addEventListener('change', function() {
                if (area.value === 'outro') {
                    inputArea.style.display = 'block';
                } else {
                    inputArea.style.display = 'none';
                }
            });

            radioSim.addEventListener('change', function() {
                if (this.checked) {
                    documentoInput.style.display = 'block';
                }
            });

            radioNao.addEventListener('change', function() {
                if (this.checked) {
                    documentoInput.style.display = 'none';
                }
            });



        });
    </script>

@endsection

@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <script>
        $(document).ready(function() {
            let blockedDays = [];
            let minDate = '';
            let flatpickrInstances = {};

            function initializeFlatpickr(advogadoId = null) {
                Object.values(flatpickrInstances).forEach(instance => {
                    if (instance && instance.destroy) instance.destroy();
                });
                flatpickrInstances = {};

                const url = advogadoId ? `/bs/admin/blocked-dates/${advogadoId}` : '/bs/admin/blocked-dates';

                $.get(url, function(response) {
                    blockedDays = response.blocked_days;
                    minDate = response.min_date;

                    ['#data', '#data2'].forEach(selector => {
                        const element = document.querySelector(selector);
                        if (element) {
                            flatpickrInstances[selector] = flatpickr(selector, {
                                dateFormat: "Y-m-d",
                                minDate: minDate,
                                disable: [function(date) {
                                    return blockedDays.includes(date.getDay());
                                }],
                                locale: flatpickr.l10ns.pt,
                                onChange: function(selectedDates, dateStr, instance) {
                                    $(instance.element).trigger('change');
                                }
                            });
                        }
                    });
                });
            }

            $(document).on('change', '#select_advogado, #select_advogado_exist', function() {
                const advogadoId = $(this).val();
                $('#data, #data2').val('');
                $('#hora, #hora2').html('<option value="">Selecionar horário</option>');
                initializeFlatpickr(advogadoId);
            });

            $(document).on('change', '#data, #data2', function() {
                const date = $(this).val();
                const horaSelect = $(this).attr('id') === 'data' ? '#hora' : '#hora2';
                const advogadoSelect = $(this).closest('form').find(
                    '#select_advogado, #select_advogado_exist');
                const advogadoId = advogadoSelect.val();

                if (date && advogadoId) {
                    $.ajax({
                        url: `/bs/admin/available-times-advogado/${advogadoId}/${date}`,
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Selecionar horário</option>';
                            if (response.available_times && response.available_times.length >
                                0) {
                                response.available_times.forEach(time => {
                                    options +=
                                        `<option value="${time}">${time}</option>`;
                                });
                            } else {
                                options +=
                                '<option value="">Nenhum horário disponível</option>';
                            }
                            $(horaSelect).html(options);
                        },
                        error: function() {
                            $(horaSelect).html(
                                '<option value="">Erro ao carregar horários</option>');
                        }
                    });
                } else {
                    $(horaSelect).html('<option value="">Selecionar horário</option>');
                }
            });

            initializeFlatpickr();
        });
    </script>

    <script src="{{ asset('assets/cliente/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/cliente/appointment/appointment-validation.js') }}"></script>
@endpush
