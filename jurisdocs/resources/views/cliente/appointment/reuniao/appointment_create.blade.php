@extends('admin.layout.app')
@section('title', 'Agendar')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Criar Agenda para Reunião</h3>
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
                                        <option value="Google Meet"
                                            {{ old('vc_plataforma') == 'Google Meet' ? 'selected' : '' }}>Google
                                            Meet
                                        </option>
                                        <option value="Zoom" {{ old('vc_plataforma') == 'Zoom' ? 'selected' : '' }}>Zoom
                                        </option>

                                        <option value="Chamada Telefónica"
                                            {{ old('vc_plataforma') == 'Chamada Telefónica' ? 'selected' : '' }}>
                                            Chamada Telefónica</option>
                                        <option value="Presencial"
                                            {{ old('vc_plataforma') == 'Presencial' ? 'selected' : '' }}>
                                            Presencial</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group" id="div_link_acesso" style="display: none;">
                                    <label for="vc_link_acesso">Link de Acesso</label>
                                    <input type="text" class="form-control" id="vc_link_acesso" name="vc_link_acesso"
                                        readonly>
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
                                        <input class="form-check-input" type="checkbox" name="it_termo" id="it_termo"
                                            value="1" {{ old('it_termo') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="it_termo">
                                            Autorização para tratamento de dados pessoais. Autorizo o tratamento dos meus
                                            dados
                                            para fins de
                                            agendamento e
                                            comunicação.
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
                <input type="hidden" name="type_agenda" id="type_agenda" value="reuniao">
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
            const plataformaSelect = document.getElementById('vc_plataforma');
            const linkInput = document.getElementById('vc_link_acesso');
            const linkDiv = document.getElementById('div_link_acesso');

            const checkbox = document.getElementById('it_termo');
            const btnSubmit = document.getElementById('btn_submit');
            const btnSubmitExisting = document.getElementById('btn_submit_existing');
            checkbox.addEventListener('change', function() {
                btnSubmit.disabled = !this.checked;
                btnSubmitExisting.disabled = !this.checked;
            });

            plataformaSelect.addEventListener('change', function() {
                const plataforma = this.value;

                // Simula a geração de link
                if (plataforma === "Google Meet" || plataforma === "Zoom" || plataforma === "Teams") {
                    const linkSimulado =
                        `https://meet.fake/${plataforma.toLowerCase().replace(/\s/g, '')}/${Math.random().toString(36).substring(2, 10)}`;
                    linkInput.value = linkSimulado;
                    linkDiv.style.display = "block";
                } else {
                    linkInput.value = "";
                    linkDiv.style.display = "none";
                }
            });
        })
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


            // Carregar datas bloqueadas
            $.get('/bs/admin/blocked-dates', function(response) {

                blockedDays = response.blocked_days;
                minDate = response.min_date;

                flatpickr("#data, #data2", {
                    dateFormat: "Y-m-d",
                    minDate: minDate,
                    disable: [
                        function(date) {
                            return blockedDays.includes(date.getDay());
                        }
                    ],
                    locale: flatpickr.l10ns.pt,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Disparar evento change para buscar horários
                        $(instance.element).trigger('change');
                    }
                });
            });
            $(document).on('change', '#data, #data2', function() {
                let date = $(this).val();
                let horaSelect = $(this).attr('id') === 'data' ? '#hora' : '#hora2';
                if (date) {
                    $.ajax({
                        url: `/bs/admin/available-times/${date}`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Resposta:', response);

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
                        error: function(xhr, status, error) {
                            console.error('Erro ao buscar horários:', error);
                            $(horaSelect).html(
                                '<option value="">Erro ao carregar horários</option>');
                        }
                    });
                } else {
                    $(horaSelect).html('<option value="">Selecionar horário</option>');
                }
            });
        });
    </script>
    <script src="{{ asset('assets/cliente/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/cliente/appointment/appointment-validation.js') }}"></script>
@endpush
