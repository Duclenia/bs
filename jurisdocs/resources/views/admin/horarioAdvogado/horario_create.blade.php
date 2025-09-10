@extends('admin.layout.app')

@push('style')
    <link href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet" />

    <style>
        .iti {
            width: 100%;
        }
    </style>
@endpush

@section('title', __('Adicionar Horario'))

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('Adicionar Horario') }}</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                <a href="{{ route('horario.index') }}" class="btn btn-primary">{{ __('Back') }}</a>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
                <form action="{{ route('horario-advogado.store') }}" method="POST" id="tagForm" name="tagForm">
                    @csrf()
                    <div class="x_content">
                        @include('component.error')

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label>Selecionar Dia da Semana <span class="text-danger">*</span></label><br>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="monday" id="seg">
                                            Segunda-feira
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="tuesday" id="ter">
                                            Terça-feira
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="wednesday" id="qua">
                                            Quarta-feira
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="thursday" id="qui">
                                            Quinta-feira
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="friday" id="sex">
                                            Sexta-feira
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="saturday" id="sab"> Sábado
                                        </label>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="day_of_week" value="sunday" id="dom"> Domingo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="horario-fields" style="display: none;">
                            <div class="row">

                                <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                    <label for="start_time">Horário de Início <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="start_time" name="start_time">
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                    <label for="end_time">Horário de Fim <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="end_time" name="end_time">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                    <label for="interval_minutes">Intervalo entre Atendimentos (minutos) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="interval_minutes" name="interval_minutes"
                                        min="15" max="120" step="15" value="30">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                    <label for="breaks">Pausas do trabalho (opcional)</label>
                                    <small class="form-text text-muted">Ex: 12:00-13:00 (uma pausa por linha)</small>
                                    <textarea class="form-control" id="breaks" name="breaks" rows="3" placeholder="12:00-13:00&#10;15:00-15:15"></textarea>
                                </div>

                            </div>
                            <label for="breaks">Dias de trabalho?</label>
                            <div class="row">

                                <div class="col-md-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="day_off" value="1" id="day_off_sim"> Sim
                                    </label>
                                </div>
                                <div class="col-md-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="day_off" value="0" id="day_off_nao"> Não
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="form-group pull-right">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <a href="{{ route('horario.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>

                            <input type="hidden" name="token-value" id="token-value" value="{{ csrf_token() }}">

                            <input type="hidden" name="common_check_exist" id="common_check_exist"
                                value="{{ route('horario_check_exist') }}">

                            <button type="submit" name="btn_add_client" class="btn btn-success">
                                <i class="fa fa-save" id="show_loader"></i>&nbsp;{{ __('Save') }}
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="advocate_id" value="{{ $advogado_id }}">
                </form>
            </div>

        </div>
    </div>

@endsection

@push('js')
    <script>
        // Função para desabilitar inputs de trabalho
        function disableWorkInputs() {
            $('#start_time, #end_time, #interval_minutes, #breaks').prop('disabled', true).addClass('disabled');
        }

        // Função para habilitar inputs de trabalho
        function enableWorkInputs() {
            $('#start_time, #end_time, #interval_minutes, #breaks').prop('disabled', false).removeClass('disabled');
        }

        $(document).ready(function() {
            // Evento para controlar day_off
            $('input[name="day_off"]').on('change', function() {
                if ($(this).val() == '1') {
                    enableWorkInputs();
                } else {
                    disableWorkInputs();
                    // Limpar campos quando não é dia de trabalho
                    $('#start_time, #end_time, #interval_minutes, #breaks').val('');
                }
            });
            $('input[name="day_of_week"]').on('change', function() {
                var selectedDay = $(this).val();

                // Mostrar os campos
                $('#horario-fields').show();

                // Carregar dados do dia selecionado
                $.ajax({
                    url: '{{ route('horario_advogado.getByDay') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        day_of_week: selectedDay,
                        advocate_id: $('input[name="advocate_id"]').val()
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            $('#start_time').val(response.data.start_time);
                            $('#end_time').val(response.data.end_time);
                            $('#interval_minutes').val(response.data.interval_minutes);

                            if (response.data.day_off == 1) {
                                $('#day_off_sim').prop('checked', true);
                                enableWorkInputs();
                            } else {
                                $('#day_off_nao').prop('checked', true);
                                disableWorkInputs();
                            }

                            if (response.data.breaks && response.data.breaks.length > 0) {
                                $('#breaks').val(response.data.breaks.join('\n'));
                            } else {
                                $('#breaks').val('');
                            }
                        } else {
                            $('#start_time').val('');
                            $('#end_time').val('');
                            $('#interval_minutes').val('30');
                            $('#breaks').val('');
                            $('input[name="day_off"]').prop('checked', false);
                        }
                    }
                });
            });
        });
    </script>


    <script src="{{ asset('assets/js/configuracoes/horario-validation.js') }}"></script>
@endpush
