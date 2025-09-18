@extends('admin.layout.app')
@push('style')
    <link href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet" />

    <style>
        .iti {
            width: 100%;
        }
    </style>
@endpush
@section('title', 'Agendar consulta')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Criar Agendamento de Consulta</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                <a href="{{ route('consulta.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">
                <div class="x_content">

                    <div class="row" id="client-type-selection">
                        <div class="col-md-2">
                            <input type="radio" id="test5" value="new" name="type" checked>
                            <label for="test5"><b>Novo Cliente</b></label>
                        </div>

                        <div class="col-md-4">
                            <input type="radio" id="test4" value="exists" name="type">
                            <label for="test4"><b>Cliente existente</b></label>
                        </div>
                    </div>

                    <div id="steps-indicator" class="steps-indicator" style="display: none;">
                        <div class="step-item step-active" id="step-1-indicator">
                            <div class="step-number">1</div>
                            <div class="step-title">Dados do Cliente</div>
                        </div>
                        <div class="step-item" id="step-2-indicator">
                            <div class="step-number">2</div>
                            <div class="step-title">Dados da Agenda</div>
                        </div>
                    </div>
                    <br>

                    <form id="add_appointment_existent" name="add_appointment" role="form" method="POST"
                        action="{{ route('agenda.store') }}" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row exists" id="existing-client-form" style="display: none;">
                            @include('admin.agendamento.consulta.form_cliente_existente')
                        </div>
                        <div class="form-group pull-right exists-buttons" style="display: none;">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                <br>
                                <a href="{{ route('agenda.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>
                                <button type="submit" name="btn_add_appointment" class="btn btn-success"
                                    id="btn_submit_existing" disabled>
                                    <i class="fa fa-save"></i>&nbsp;{{ __('Save') }}
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="type_agenda" id="type_agenda" value="consulta">
                        <input type="hidden" id="test4" value="exists" name="type">
                    </form>

                    <form id="add_appointment" name="add_appointment" role="form" method="POST"
                        action="{{ route('agenda.store') }}" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        <!-- Novo Cliente - Etapa 1: Dados do Cliente -->
                        <div class="row new" id="new-client-step1">

                            @include('admin.cliente.form')

                            <!-- Botão Next para Etapa 1 -->
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary" id="next-to-step2">
                                        Próximo <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="row new" id="new-client-step2" style="display: none;">

                            @include('admin.agendamento.consulta.form_cliente_novo')
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" id="back-to-step1">
                                        <i class="fa fa-arrow-left"></i> Voltar
                                    </button>
                                    <button type="submit" class="btn btn-success pull-right" id="btn_submit" disabled>
                                        <i class="fa fa-save" id="show_loader"></i>&nbsp;{{ __('Save') }}
                                    </button>
                                    <a href="{{ route('agenda.index') }}" class="btn btn-danger pull-right"
                                        style="margin-right: 10px;">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="type_agenda" id="type_agenda" value="consulta">
                        <input type="hidden" id="test5" value="new" name="type">
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

    <input type="hidden" name="date_format_datepiker" id="date_format_datepiker" value="{{ $date_format_datepiker }}">
    <input type="hidden" name="getMobileno" id="getMobileno" value="{{ route('getMobileno') }}">

    <style>
        label {
            margin-right: 20px;
            /* Espaço entre os labels */
        }

        .steps-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 20px;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 100%;
            width: 40px;
            height: 2px;
            background-color: #ddd;
            z-index: 1;
        }

        .step-item.step-active:not(:last-child)::after {
            background-color: #007bff;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ddd;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            z-index: 2;
            position: relative;
        }

        .step-item.step-active .step-number {
            background-color: #007bff;
            color: white;
        }

        .step-item.step-completed .step-number {
            background-color: #28a745;
            color: white;
        }

        .step-title {
            font-size: 12px;
            text-align: center;
            color: #666;
        }

        .step-item.step-active .step-title {
            color: #007bff;
            font-weight: bold;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newClientRadio = document.getElementById('test5');
            const existingClientRadio = document.getElementById('test4');
            const stepsIndicator = document.getElementById('steps-indicator');
            const existingClientForm = document.getElementById('existing-client-form');

            const newClientStep1 = document.getElementById('new-client-step1');
            const newClientStep2 = document.getElementById('new-client-step2');
            const nextToStep2Btn = document.getElementById('next-to-step2');
            const backToStep1Btn = document.getElementById('back-to-step1');
            const existingButtons = document.querySelector('.exists-buttons');
            const btnSubmit = document.getElementById('btn_submit');
            const btnSubmitExisting = document.getElementById('btn_submit_existing');
            const radioSim = document.getElementById('radio-sim');
            const radioNao = document.getElementById('radio-nao');
            const documentoInput = document.getElementById('documento-input');

            const selectArea = document.getElementById('areas_direito');
            const inputOutro = document.getElementById('vc_area_outro');

            // Indicadores de passos
            const step1Indicator = document.getElementById('step-1-indicator');
            const step2Indicator = document.getElementById('step-2-indicator');

            function getActiveContext() {
                if (newClientRadio.checked) {
                    return newClientStep2.style.display !== 'none' ? newClientStep2 : newClientStep1;
                } else {
                    return existingClientForm;
                }
            }



            // Função para encontrar elementos no contexto ativo
            function findActiveElements() {
                const context = getActiveContext();
                if (!context) return {};

                return {
                    plataformaSelect: context.querySelector('select[id*="vc_plataforma"]'),
                    linkInput: context.querySelector('input[id*="vc_link_acesso"]'),
                    checkbox: context.querySelector('input[type="checkbox"][id*="it_termo"]'),
                    dateInput: context.querySelector('input[type="text"][id*="data"]'),
                    timeInput: context.querySelector('select[id*="hora"]'),
                    selectArea: context.querySelector('select[id*="area"]'),
                    inputOutro: context.querySelector('div[id*="outro"], .outro-field'),
                    radioSim: context.querySelector(
                        'input[type="radio"][value*="Sim"], input[type="radio"][id*="sim"]'),
                    radioNao: context.querySelector(
                        'input[type="radio"][value*="Não"], input[type="radio"][value*="Nao"], input[type="radio"][id*="nao"]'
                    ),
                    documentoInput: context.querySelector('div[id*="documento"], .documento-field'),
                    context: context
                };
            }
            
            // Função para navegar para um passo específico
            function navigateToStep(stepNumber) {
                if (!newClientRadio.checked) return; // Só funciona para novos clientes

                if (stepNumber === 1) {
                    newClientStep2.style.display = 'none';
                    newClientStep1.style.display = 'block';

                    if (step1Indicator) step1Indicator.className = 'step-item step-active';
                    if (step2Indicator) step2Indicator.className = 'step-item';
                } else if (stepNumber === 2) {
                    newClientStep1.style.display = 'none';
                    newClientStep2.style.display = 'block';

                    if (step1Indicator) step1Indicator.className = 'step-item step-completed';
                    if (step2Indicator) step2Indicator.className = 'step-item step-active';
                }

                setTimeout(() => {
                    setupEventListeners();
                    initializeDateTimePickers();
                }, 50);
            }

            // Toggle entre tipos de cliente
            function toggleClientType() {
                if (newClientRadio.checked) {
                    stepsIndicator.style.display = 'flex';
                    existingClientForm.style.display = 'none';
                    newClientStep1.style.display = 'block';
                    newClientStep2.style.display = 'none';
                    existingButtons.style.display = 'none';

                    if (step1Indicator) step1Indicator.className = 'step-item step-active';
                    if (step2Indicator) step2Indicator.className = 'step-item';
                } else {
                    stepsIndicator.style.display = 'none';
                    existingClientForm.style.display = 'block';
                    newClientStep1.style.display = 'none';
                    newClientStep2.style.display = 'none';
                    existingButtons.style.display = 'block';
                }

                // Reconfigurar após mudança
                setTimeout(() => {
                    setupEventListeners();
                    initializeDateTimePickers();
                }, 50);
            }

            // Atualizar botões de submit
            function updateSubmitButtons() {
                const {
                    checkbox,
                    dateInput,
                    timeInput
                } = findActiveElements();
                if (checkbox) {

                    const isChecked = checkbox.checked;
                    const isDateFilled = dateInput && dateInput.value.trim() !== '';
                    const isTimeFilled = timeInput && timeInput.value.trim() !== '';

                    // Botão só fica habilitado se checkbox marcado E data/hora preenchidas
                    canSubmit = isChecked && isDateFilled && isTimeFilled;

                    if (btnSubmit) btnSubmit.disabled = !canSubmit;
                    if (btnSubmitExisting) btnSubmitExisting.disabled = !canSubmit;
                }
            }

            // Gerar link da plataforma
            function handlePlatformChange() {
                const {
                    plataformaSelect,
                    linkInput
                } = findActiveElements();

                if (!plataformaSelect) return;

                const plataforma = plataformaSelect.value;
                const linkDiv = linkInput ? linkInput.closest('.form-group') : null;
            }

            // Gerenciar campo "Outro" para área
            function handleAreaChange() {
                const {
                    selectArea,
                    inputOutro
                } = findActiveElements();

                if (!selectArea || !inputOutro) return;

                if (selectArea.value === 'Outro') {
                    inputOutro.style.display = 'block';
                    const input = inputOutro.querySelector('input');
                    if (input) input.setAttribute('required', true);
                } else {
                    inputOutro.style.display = 'none';
                    const input = inputOutro.querySelector('input');
                    if (input) {
                        input.removeAttribute('required');
                        input.value = '';
                    }
                }
            }

            // Gerenciar campo de documento
            function handleDocumentoChange() {
                const {
                    radioSim,
                    radioNao,
                    documentoInput
                } = findActiveElements();

                if (!documentoInput) return;

                if (radioSim && radioSim.checked) {
                    documentoInput.style.display = 'block';
                } else if (radioNao && radioNao.checked) {
                    documentoInput.style.display = 'none';
                }
            }

            // Configurar event listeners
            function setupEventListeners() {
                const {
                    plataformaSelect,
                    checkbox,
                    dateInput,
                    timeInput,
                    selectArea,
                    radioSim,
                    radioNao
                } = findActiveElements();

                // Remover listeners antigos do documento inteiro (para evitar conflitos)
                document.querySelectorAll('select[id*="vc_plataforma"]').forEach(select => {
                    select.removeEventListener('change', handlePlatformChange);
                });

                document.querySelectorAll('input[type="checkbox"][id*="it_termo"]').forEach(cb => {
                    cb.removeEventListener('change', updateSubmitButtons);
                });

                document.querySelectorAll('input[id*="data"]').forEach(el => el.removeEventListener('change',
                    updateSubmitButtons));

                document.querySelectorAll('input[id*="hora"]').forEach(el => el.removeEventListener('change',
                    updateSubmitButtons));

                // Remover listeners dos novos campos
                document.querySelectorAll('select[id*="area"]').forEach(select => {
                    select.removeEventListener('change', handleAreaChange);
                });

                document.querySelectorAll('input[type="radio"][value*="Sim"], input[type="radio"][id*="sim"]')
                    .forEach(radio => {
                        radio.removeEventListener('change', handleDocumentoChange);
                    });

                document.querySelectorAll(
                    'input[type="radio"][value*="Não"], input[type="radio"][value*="Nao"], input[type="radio"][id*="nao"]'
                ).forEach(radio => {
                    radio.removeEventListener('change', handleDocumentoChange);
                });

                // Adicionar listeners apenas aos elementos ativos
                if (plataformaSelect) {
                    plataformaSelect.addEventListener('change', handlePlatformChange);
                }

                if (checkbox) {
                    checkbox.addEventListener('change', updateSubmitButtons);
                }

                if (dateInput) {
                    dateInput.addEventListener('change', updateSubmitButtons);
                    // Event específico do datepicker
                    $(dateInput).on('changeDate', updateSubmitButtons);
                }

                if (timeInput) {
                    timeInput.addEventListener('change', updateSubmitButtons);
                    // Event específico do datetimepicker
                    $(timeInput).on('dp.change', updateSubmitButtons);
                }

                // Novos event listeners para área
                if (selectArea) {
                    selectArea.addEventListener('change', handleAreaChange);
                }

                // Novos event listeners para documento
                if (radioSim) {
                    radioSim.addEventListener('change', handleDocumentoChange);
                }

                if (radioNao) {
                    radioNao.addEventListener('change', handleDocumentoChange);
                }

                // Atualizar estado inicial
                updateSubmitButtons();
                handleAreaChange(); // Verificar estado inicial da área
                handleDocumentoChange(); // Verificar estado inicial do documento
            }

            // Event listeners para radio buttons
            if (newClientRadio) newClientRadio.addEventListener('change', toggleClientType);
            if (existingClientRadio) existingClientRadio.addEventListener('change', toggleClientType);

            // Event listeners para os indicadores de passos (NOVA FUNCIONALIDADE)
            if (step1Indicator) {
                step1Indicator.addEventListener('click', function() {
                    if (newClientRadio.checked) {
                        navigateToStep(1);
                    }
                });

                // Adicionar cursor pointer para indicar que é clicável
                step1Indicator.style.cursor = 'pointer';
            }

            if (step2Indicator) {
                step2Indicator.addEventListener('click', function() {
                    if (newClientRadio.checked) {
                        navigateToStep(2);
                    }
                });

                // Adicionar cursor pointer para indicar que é clicável
                step2Indicator.style.cursor = 'pointer';
            }

            // Navegação entre steps (botões existentes)
            if (nextToStep2Btn) {
                nextToStep2Btn.addEventListener('click', function() {
                    navigateToStep(2);
                });
            }

            if (backToStep1Btn) {
                backToStep1Btn.addEventListener('click', function() {
                    navigateToStep(1);
                });
            }

            // Inicializar
            toggleClientType();
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
    <script src="{{ asset('assets/admin/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/appointment-validation.js') }}"></script>
@endpush
