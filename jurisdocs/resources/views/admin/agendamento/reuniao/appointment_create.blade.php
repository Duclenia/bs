@extends('admin.layout.app')
@push('style')
    <link href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet" />
    <style>
        .iti {
            width: 100%;
        }

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

        /* Estilo para campos de data com dias bloqueados */
        input[type="date"].has-blocked-days {
            border: 2px solid #ffc107 !important;
            background-color: #fff3cd;
        }

        input[type="date"].has-blocked-days::after {
            content: "⚠️ Alguns dias não estão disponíveis";
            position: absolute;
            font-size: 10px;
            color: #856404;
            top: -15px;
            left: 0;
        }

        .date-field-container {
            position: relative;
        }

        .date-warning {
            font-size: 11px;
            color: #856404;
            margin-top: 2px;
            display: none;
        }

        input[type="date"].has-blocked-days+.date-warning {
            display: block;
        }

        /* Estilos para o formulário de pagamento */
        #new-client-step3 {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        #new-client-step3 h4 {
            color: #495057;
            margin-bottom: 20px;
        }

        .payment-form-group {
            margin-bottom: 20px;
        }

        .payment-summary {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }

        .payment-method-details {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ffeaa7;
            margin-top: 10px;
        }

        .required {
            color: #dc3545;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Esconder elementos por padrão */
        .new,
        .exists {
            display: none !important;
        }

        .new.show,
        .exists.show {
            display: block !important;
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
                                <div class="step-item" id="step-3-indicator">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Pagamento</div>
                                </div>
                            </div>

                            <div id="steps-indicator-existing" class="steps-indicator" style="display: none;">
                                <div class="step-item step-active" id="step-1-existing-indicator">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Dados da Agenda</div>
                                </div>
                                <div class="step-item" id="step-2-existing-indicator">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Pagamento</div>
                                </div>
                            </div>
                            <br>

                            <form id="add_appointment_existent" name="add_appointment" role="form" method="POST"
                                action="{{ route('agenda.store') }}" enctype="multipart/form-data" autocomplete="off">
                                {{ csrf_field() }}

                                <!-- Cliente Existente - Etapa 1: Dados da Agenda -->
                                <div class="row exists" id="existing-client-step1" style="display: none;">
                                    @include('admin.agendamento.reuniao.form_cliente_existente')

                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-primary" id="existing-next-to-step2">
                                                Próximo <i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cliente Existente - Etapa 2: Pagamento -->
                                <div class="row exists" id="existing-client-step2" style="display: none;">
                                    @include('admin.agendamento.reuniao.pagamento')

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-default" id="existing-back-to-step1">
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
                                <input type="hidden" id="test4" value="exists" name="type">
                            </form>

                            <form id="add_appointment" name="add_appointment" role="form" method="POST"
                                action="{{ route('agenda.store') }}" enctype="multipart/form-data" autocomplete="off">
                                {{ csrf_field() }}
                                <!-- Novo Cliente - Etapa 1: Dados do Cliente -->
                                <div class="row new" id="new-client-step1">

                                    @include('admin.cliente.form')

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
                                            <button type="button" class="btn btn-primary pull-right" id="next-to-step3">
                                                Próximo <i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Novo Cliente - Etapa 3: Pagamento -->
                                <div class="row new" id="new-client-step3" style="display: none;">
                                    @include('admin.agendamento.reuniao.pagamento')

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-default" id="back-to-step2">
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newClientRadio = document.getElementById('test5');
            const existingClientRadio = document.getElementById('test4');
            const stepsIndicator = document.getElementById('steps-indicator');
            const stepsIndicatorExisting = document.getElementById('steps-indicator-existing');
            const existingClientStep1 = document.getElementById('existing-client-step1');
            const existingClientStep2 = document.getElementById('existing-client-step2');

            const newClientStep1 = document.getElementById('new-client-step1');
            const newClientStep2 = document.getElementById('new-client-step2');
            const newClientStep3 = document.getElementById('new-client-step3');
            const nextToStep2Btn = document.getElementById('next-to-step2');
            const nextToStep3Btn = document.getElementById('next-to-step3');
            const backToStep1Btn = document.getElementById('back-to-step1');
            const backToStep2Btn = document.getElementById('back-to-step2');
            const existingNextToStep2Btn = document.getElementById('existing-next-to-step2');
            const existingBackToStep1Btn = document.getElementById('existing-back-to-step1');
            const existingButtons = document.querySelector('.exists-buttons');

            // Indicadores para cliente existente
            const step1ExistingIndicator = document.getElementById('step-1-existing-indicator');
            const step2ExistingIndicator = document.getElementById('step-2-existing-indicator');
            const btnSubmit = document.getElementById('btn_submit');
            const btnSubmitExisting = document.getElementById('btn_submit_existing');

            // Indicadores de passos
            const step1Indicator = document.getElementById('step-1-indicator');
            const step2Indicator = document.getElementById('step-2-indicator');
            const step3Indicator = document.getElementById('step-3-indicator');

            // Elementos do pagamento
            const formaPagamento = document.getElementById('forma_pagamento');
            const paymentDetails = document.getElementById('payment-details');
            const confirmarPagamento = document.getElementById('confirmar_pagamento');
            const valorConsulta = document.getElementById('valor_consulta');

            function getActiveContext() {
                if (newClientRadio.checked) {
                    if (newClientStep3.style.display !== 'none') return newClientStep3;
                    if (newClientStep2.style.display !== 'none') return newClientStep2;
                    return newClientStep1;
                } else {
                    if (existingClientStep2.style.display !== 'none') return existingClientStep2;
                    return existingClientStep1;
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
                    valorConsulta: context.querySelector('#custo'),
                    formaPagamento: context.querySelector('#forma_pagamento'),
                    confirmarPagamento: context.querySelector('#confirmar_pagamento'),
                    referenciaInput: context.querySelector('#referencia_pagamento'),
                    comprovativoInput: context.querySelector('#comprovativo'),
                    context: context
                };
            }

            function navigateToStep(stepNumber) {
                if (!newClientRadio.checked) return; // Só funciona para novos clientes

                // Esconder todos os steps
                newClientStep1.classList.remove('show');
                newClientStep2.classList.remove('show');
                newClientStep3.classList.remove('show');

                // Reset indicators
                if (step1Indicator) step1Indicator.className = 'step-item';
                if (step2Indicator) step2Indicator.className = 'step-item';
                if (step3Indicator) step3Indicator.className = 'step-item';

                if (stepNumber === 1) {
                    newClientStep1.classList.add('show');
                    if (step1Indicator) step1Indicator.className = 'step-item step-active';
                } else if (stepNumber === 2) {
                    newClientStep2.classList.add('show');
                    if (step1Indicator) step1Indicator.className = 'step-item step-completed';
                    if (step2Indicator) step2Indicator.className = 'step-item step-active';
                } else if (stepNumber === 3) {
                    newClientStep3.classList.add('show');
                    if (step1Indicator) step1Indicator.className = 'step-item step-completed';
                    if (step2Indicator) step2Indicator.className = 'step-item step-completed';
                    if (step3Indicator) step3Indicator.className = 'step-item step-active';
                }

                setTimeout(() => {
                    setupEventListeners();
                    setupPaymentListeners();
                    initializeDateTimePickers();
                }, 50);
            }

            // Toggle entre tipos de cliente
            function toggleClientType() {
                // Esconder todos os elementos primeiro
                document.querySelectorAll('.new, .exists').forEach(el => {
                    el.classList.remove('show');
                });

                if (newClientRadio.checked) {
                    stepsIndicator.style.display = 'flex';
                    stepsIndicatorExisting.style.display = 'none';
                    newClientStep1.classList.add('show');

                    // Reset indicators to initial state
                    if (step1Indicator) step1Indicator.className = 'step-item step-active';
                    if (step2Indicator) step2Indicator.className = 'step-item';
                    if (step3Indicator) step3Indicator.className = 'step-item';
                } else {
                    stepsIndicator.style.display = 'none';
                    stepsIndicatorExisting.style.display = 'flex';
                    existingClientStep1.classList.add('show');

                    // Reset indicators for existing client
                    if (step1ExistingIndicator) step1ExistingIndicator.className = 'step-item step-active';
                    if (step2ExistingIndicator) step2ExistingIndicator.className = 'step-item';
                }

                // Reconfigurar após mudança
                setTimeout(() => {
                    setupEventListeners();
                    setupPaymentListeners();
                    initializeDateTimePickers();
                }, 50);
            }

            // Função para navegar entre passos do cliente existente
            function navigateExistingToStep(stepNumber) {
                if (newClientRadio.checked) return; // Só funciona para clientes existentes

                // Esconder todos os steps
                existingClientStep1.classList.remove('show');
                existingClientStep2.classList.remove('show');

                // Reset indicators
                if (step1ExistingIndicator) step1ExistingIndicator.className = 'step-item';
                if (step2ExistingIndicator) step2ExistingIndicator.className = 'step-item';

                if (stepNumber === 1) {
                    existingClientStep1.classList.add('show');
                    if (step1ExistingIndicator) step1ExistingIndicator.className = 'step-item step-active';
                } else if (stepNumber === 2) {
                    existingClientStep2.classList.add('show');
                    if (step1ExistingIndicator) step1ExistingIndicator.className = 'step-item step-completed';
                    if (step2ExistingIndicator) step2ExistingIndicator.className = 'step-item step-active';
                }

                setTimeout(() => {
                    setupEventListeners();
                    setupPaymentListeners();
                    initializeDateTimePickers();
                }, 50);
            }
            // Função para configurar listeners do pagamento
            function setupPaymentListeners() {
                if (formaPagamento) {
                    formaPagamento.addEventListener('change', function() {
                        if (this.value && this.value !== 'dinheiro') {
                            paymentDetails.style.display = 'block';
                            document.getElementById('referencia_pagamento').required = true;
                        } else {
                            paymentDetails.style.display = 'none';
                            document.getElementById('referencia_pagamento').required = false;
                        }
                        updateSubmitButtons();
                    });
                }


                if (valorConsulta) {
                    valorConsulta.addEventListener('input', updateSubmitButtons);
                }
            }

            // Atualizar botões de submit
            function updateSubmitButtons() {
                let canSubmit = false;

                // Verificar se estamos no passo de pagamento
                const isPaymentStep = (newClientRadio.checked && newClientStep3.classList.contains('show')) ||
                    (!newClientRadio.checked && existingClientStep2.classList.contains('show'));

                if (isPaymentStep) {

                    const {
                        checkbox,
                        dateInput,
                        comprovativoInput,
                        timeInput
                    } = findActiveElements();

                    if (checkbox) {
                        const isChecked = checkbox.checked;
                        const isDateFilled = dateInput && dateInput.value.trim() !== '';
                        const isTimeFilled = timeInput && timeInput.value.trim() !== '';
                        canSubmit = isChecked && isDateFilled && isTimeFilled;
                    }
                }
                console.log('canSubmit before payment check:', canSubmit);
                /*    if (btnSubmit) btnSubmit.disabled = !canSubmit; */
                /*       if (btnSubmitExisting) btnSubmitExisting.disabled = !canSubmit; */
            }

            // Gerar link da plataforma
            function handlePlatformChange() {
                const {
                    plataformaSelect,
                    linkInput
                } = findActiveElements();

                if (!plataformaSelect) return;

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

                document.querySelectorAll('input[id*="comprovativo"]').forEach(el => el.removeEventListener(
                    'change',
                    updateSubmitButtons));

                // Remover listeners de pagamento
                document.querySelectorAll('#valor_consulta').forEach(el => el.removeEventListener('input',
                    updateSubmitButtons));
                document.querySelectorAll('#forma_pagamento').forEach(el => el.removeEventListener('change',
                    updateSubmitButtons));

                document.querySelectorAll('#referencia_pagamento').forEach(el => el.removeEventListener('input',
                    updateSubmitButtons));

                // Adicionar listeners apenas aos elementos ativos
                const {
                    valorConsulta,
                    formaPagamento,
                    confirmarPagamento,
                    referenciaInput
                } = findActiveElements();

                if (plataformaSelect) {
                    plataformaSelect.addEventListener('change', handlePlatformChange);
                }

                if (checkbox) {
                    checkbox.addEventListener('change', updateSubmitButtons);
                }

                if (dateInput) {
                    dateInput.addEventListener('change', updateSubmitButtons);
                    $(dateInput).on('changeDate', updateSubmitButtons);
                }

                if (timeInput) {
                    timeInput.addEventListener('change', updateSubmitButtons);
                    $(timeInput).on('dp.change', updateSubmitButtons);
                }

                // Listeners para pagamento
                if (valorConsulta) {
                    valorConsulta.addEventListener('input', updateSubmitButtons);
                }

                if (formaPagamento) {
                    formaPagamento.addEventListener('change', updateSubmitButtons);
                }

                if (confirmarPagamento) {
                    confirmarPagamento.addEventListener('change', updateSubmitButtons);
                }

                if (referenciaInput) {
                    referenciaInput.addEventListener('input', updateSubmitButtons);
                }

                // Atualizar estado inicial
                updateSubmitButtons();
                handleAreaChange(); // Verificar estado inicial da área
                handleDocumentoChange(); // Verificar estado inicial do documento
            }

            // Event listeners para radio buttons
            if (newClientRadio) newClientRadio.addEventListener('change', toggleClientType);
            if (existingClientRadio) existingClientRadio.addEventListener('change', toggleClientType);

            // Event listeners para os indicadores de passos
            if (step1Indicator) {
                step1Indicator.addEventListener('click', function() {
                    if (newClientRadio.checked) {
                        navigateToStep(1);
                    }
                });
                step1Indicator.style.cursor = 'pointer';
            }

            if (step2Indicator) {
                step2Indicator.addEventListener('click', function() {
                    if (newClientRadio.checked) {
                        navigateToStep(2);
                    }
                });
                step2Indicator.style.cursor = 'pointer';
            }

            if (step3Indicator) {
                step3Indicator.addEventListener('click', function() {
                    if (newClientRadio.checked) {
                        navigateToStep(3);
                    }
                });
                step3Indicator.style.cursor = 'pointer';
            }

            // Navegação entre steps
            if (nextToStep2Btn) {
                nextToStep2Btn.addEventListener('click', function() {
                    navigateToStep(2);
                });
            }

            if (nextToStep3Btn) {
                nextToStep3Btn.addEventListener('click', function() {
                    navigateToStep(3);
                });
            }

            if (backToStep1Btn) {
                backToStep1Btn.addEventListener('click', function() {
                    navigateToStep(1);
                });
            }

            if (backToStep2Btn) {
                backToStep2Btn.addEventListener('click', function() {
                    navigateToStep(2);
                });
            }

            // Navegação para cliente existente
            if (existingNextToStep2Btn) {
                existingNextToStep2Btn.addEventListener('click', function() {
                    navigateExistingToStep(2);
                });
            }

            if (existingBackToStep1Btn) {
                existingBackToStep1Btn.addEventListener('click', function() {
                    navigateExistingToStep(1);
                });
            }
            if (step1ExistingIndicator) {
                step1ExistingIndicator.addEventListener('click', function() {
                    navigateExistingToStep(1);
                });
            }
            if (step2ExistingIndicator) {
                step2ExistingIndicator.addEventListener('click', function() {
                    navigateExistingToStep(2);
                });
            }

            toggleClientType();
        });
    </script>


@endsection

@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script src="{{ asset('assets/js/appointment/get-days-advogado.js') }}"></script>
    <script src="{{ asset('assets/admin/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/appointment-validation.js') }}"></script>
@endpush
