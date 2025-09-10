@extends('admin.layout.app')
@section('title', 'Agenda')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/jquery-confirm-master/css/jquery-confirm.css') }}">
@endpush
@section('content')
    <div class="">

        @component('component.heading', [
            'page_title' => 'Agendamento de Reunião',
            'action' => route('reuniao.create'),
            'text' => 'Agendar',
            'permission' => auth()->user()->can('appointment_add'),
        ])
        @endcomponent

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                    <div class="x_title">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="date_from">De:</label>

                                <input type="text" class="form-control dateFrom" id="date_from" autocomplete="off"
                                    readonly="">

                            </div>

                            <div class="col-md-3 form-group">
                                <label for="date_to">At&eacute; :</label>

                                <input type="text" class="form-control dateTo" id="date_to" autocomplete="off"
                                    readonly="">
                            </div>

                            <ul class="nav navbar-left panel_toolbox">

                                <br>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-danger appointment-margin" type="button" id="btn_clear"
                                    name="btn_clear">{{ __('Clear') }}
                                </button>
                                <button type="submit" id="search" class="btn btn-success appointment-margin"><i
                                        class="fa fa-search"></i>&nbsp;{{ __('Search') }}
                                </button>
                            </ul>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <table id="Appointmentdatatable" class="table appointment_table"
                            data-url="{{ route('appointmentReuniao.list') }}">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="20%">Entidade Reunião</th>
                                    <th width="10%">Telefone</th>
                                    <th width="10%">{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th data-orderable="false">Estado</th>
                                    <th data-orderable="false">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="forwardModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Encaminhar para Outro Advogado</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forwardForm">
                        <input type="hidden" id="consultaId" name="agendamento_id">

                        <div class="form-group">
                            <label>Selecionar Advogado <span class="text-danger">*</span></label>
                            <select class="form-control" id="novoAdvogado" name="novo_advogado_id" required>
                                <option value="">Selecionar Advogado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nova Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="novaData" name="nova_data" required>
                        </div>

                        <div class="form-group">
                            <label>Novo Horário <span class="text-danger">*</span></label>
                            <select class="form-control" id="novoHorario" name="novo_horario" required>
                                <option value="">Selecionar horário</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Motivo do Encaminhamento</label>
                            <textarea class="form-control" name="motivo" rows="3" placeholder="Descreva o motivo do encaminhamento..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarEncaminhamento">Confirmar
                        Encaminhamento</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Upload de Comprovativo -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload de Comprovativo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" id="uploadConsultaId" name="agendamento_id">
                        <div class="form-group">
                            <label>Comprovativo (PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="comprovativo" name="comprovativo"
                                accept=".pdf" required>
                            <small class="text-muted">Apenas arquivos PDF são aceitos (máx. 10MB)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnUploadComprovativo">Enviar
                        Comprovativo</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="token-value" id="token-value" value="{{ csrf_token() }}">
    <input type="hidden" name="date_format_datepiker" id="date_format_datepiker" value="{{ $date_format_datepiker }}">
    <input type="hidden" name="common_change_state" id="common_change_state" value="{{ url('common_change_state') }}">

@endsection

@push('js')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <script src="{{ asset('assets/admin/jquery-confirm-master/js/jquery-confirm.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/appointmentReuniao-datatable.js') }}"></script>

    <script>
        window.routes = {
            getAdvogados: '{{ route('consulta.getAdvogados') }}',
            blockedDates: '{{ route('horario_advogado.blockedDates', '') }}',
            availableTimes: '{{ route('horario_advogado.availableTimesByAdvogado', ['advogado_id' => '__ADVOGADO__', 'date' => '__DATA__']) }}',
            encaminhar: '{{ route('agenda.encaminhar') }}',
            uploadComprovativo: '{{ route('agenda.uploadComprovativo') }}',

        };
    </script>
    <script src="{{ asset('assets/js/appointment/forward-lawyer.js') }}"></script>
    <script src="{{ asset('assets/js/appointment/upload-Payment.js') }}"></script>
@endpush
