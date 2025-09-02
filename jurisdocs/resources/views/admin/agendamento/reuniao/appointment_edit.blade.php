@extends('admin.layout.app')
@section('title', __('Appointment Edit'))

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Editar Agendamento</h3>
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
                    <form id="add_appointment" name="add_appointment" role="form" method="POST"
                        action="{{ route('reuniao.update', $appointment->id) }}">
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

                                        <input type="radio" id="test4" name="type"
                                            @if ($appointment->activo == 'OPEN') checked @endif>

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
                                <div class="col-md-6 form-group">
                                    <label for="mobile">Contacto telefónico (opcional)</label>
                                    <input type="number" class="form-control" id="" name="mobile"
                                        value="{{ old('telefone', $appointment->telefone ?? null) }}" autocomplete="off">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="email">Endereço de e-mail <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $appointment->email ?? null) }}" autocomplete="off">
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="vc_entidade">Entidade / Organização (opcional)</label>
                                        <input type="text" class="form-control" id="vc_entidade" name="vc_entidade"
                                            value="{{ old('vc_entidade', $appointment->vc_entidade ?? null) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="vc_motivo">Motivo da reunião <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="vc_motivo" name="vc_motivo" rows="3" required>{{ old('vc_motivo', $appointment->vc_motivo ?? null) }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="date">{{ __('Data Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="data" class="form-control" name="date"
                                            value="{{ old('data', $appointment->data ?? null) }}" required>

                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="time">{{ __('Horario Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="time" id="hora" required>
                                            @if ($appointment->hora)
                                                <option value="{{ $appointment->hora }}" selected>
                                                    {{ date('H:i', strtotime($appointment->hora)) }}
                                                </option>
                                            @else
                                                <option value="">Selecionar horário</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="vc_plataforma">Plataforma preferida <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
                                            <option value="">-- Selecionar --</option>

                                            <option value="zoom"
                                                {{ old('vc_plataforma', $appointment->vc_plataforma ?? null) == 'zoom' ? 'selected' : '' }}>
                                                Zoom</option>

                                            <option value="Chamada Telefónica"
                                                {{ old('vc_plataforma', $appointment->vc_plataforma ?? null) == 'Chamada Telefónica' ? 'selected' : '' }}>
                                                Chamada Telefónica</option>
                                            <option value="Presencial"
                                                {{ old('vc_plataforma', $appointment->vc_plataforma ?? null) == 'Presencial' ? 'selected' : '' }}>Presencial
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="vc_nota">Nota adicional (opcional)</label>
                                        <textarea class="form-control" id="vc_nota" name="vc_nota" rows="3">{{ old('vc_nota', $appointment->vc_nota ?? null) }}</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="it_termo"
                                                id="it_termo" value="1"
                                                {{ old('it_termo', $appointment->it_termo ?? null) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="it_termo">
                                                Autorização para tratamento de dados pessoais. Autorizo o tratamento dos
                                                meus dados para fins de
                                                agendamento e
                                                comunicação.
                                                <a href="#" data-toggle="modal" data-target="#termosModal"
                                                    style="color: #3f6fb3">Ver termos</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <br>
                                    <a href="{{ route('agenda.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>

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
@endpush
