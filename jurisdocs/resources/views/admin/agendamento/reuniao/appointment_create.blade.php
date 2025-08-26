@extends('admin.layout.app')
@push('style')
    <link href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet" />
    <style>
        .iti {
            width: 100%;
        }
    </style>
@endpush
@section('title', 'Agendar reunião')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Criar Agendamento de Reunião</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                <a href="{{ route('reuniao.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
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
                                    @include('admin.agendamento.reuniao.form_cliente_existente')
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
                                <input type="hidden" name="type_agenda" id="type_agenda" value="reuniao">
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

                                    @include('admin.agendamento.reuniao.form_cliente_novo')
                                    <br>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-default" id="back-to-step1">
                                                <i class="fa fa-arrow-left"></i> Voltar
                                            </button>
                                            <button type="submit" class="btn btn-success pull-right" id="btn_submit"
                                                disabled>
                                                <i class="fa fa-save" id="show_loader"></i>&nbsp;{{ __('Save') }}
                                            </button>
                                            <a href="{{ route('agenda.index') }}" class="btn btn-danger pull-right"
                                                style="margin-right: 10px;">
                                                {{ __('Cancel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="type_agenda" id="type_agenda" value="reuniao">
                                <input type="hidden" id="test5" value="new" name="type">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Termos -->
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
        .steps-indicator {
            display: flex;
            justify-content: center;

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
                    dateInput: context.querySelector('input[type="date"][id*="data"]'),
                    timeInput: context.querySelector('input[type="time"][id*="hora"]'),
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

                    // Reset indicators to initial state
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

                if (plataforma === "Google Meet" || plataforma === "Zoom" || plataforma === "Teams") {
                    const linkSimulado =
                        `https://meet.fake/${plataforma.toLowerCase().replace(/\s/g, '')}/${Math.random().toString(36).substring(2, 10)}`;
                    if (linkInput) linkInput.value = linkSimulado;
                    if (linkDiv) linkDiv.style.display = "block";
                } else {
                    if (linkInput) linkInput.value = "";
                    if (linkDiv) linkDiv.style.display = "none";
                }
            }



            // Configurar event listeners
            function setupEventListeners() {
                const {
                    plataformaSelect,
                    checkbox,
                    dateInput,
                    timeInput,

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

    <script>
        const dataInput_2 = document.getElementById("data2");
        const horaInput_2 = document.getElementById("hora2");
        const dataInput = document.getElementById("data");
        const horaInput = document.getElementById("hora");

        const hoje_2 = new Date().toISOString().split("T")[0];
        dataInput_2.min = hoje_2;

        dataInput_2.addEventListener("change", () => {
            const agora = new Date();
            const horaAtual_2 = String(agora.getHours()).padStart(2, "0");
            const minutoAtual_2 = String(agora.getMinutes()).padStart(2, "0");
            const horaFormatada_2 = `${horaAtual_2}:${minutoAtual_2}`;

            if (dataInput_2.value === hoje) {
                horaInput_2.min = horaFormatada_2;
            } else {
                horaInput_2.min = "00:00";
            }
        });

        const hoje = new Date().toISOString().split("T")[0];
        dataInput.min = hoje;
        dataInput.addEventListener("change", () => {

            const agora = new Date();
            const horaAtual = String(agora.getHours()).padStart(2, "0");
            const minutoAtual = String(agora.getMinutes()).padStart(2, "0");
            const horaFormatada = `${horaAtual}:${minutoAtual}`;

            if (dataInput.value === hoje) {
                horaInput.min = horaFormatada;
            } else {
                horaInput.min = "00:00";
            }
        });
    </script>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/appointment-validation.js') }}"></script>
@endpush
