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

                                        <input type="radio" id="test4"  name="type"
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
                                        <label for="data2">{{ __('Data Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data2" name="data"
                                            value="{{ old('date', $appointment->data ?? null) }}" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="hora2">{{ __('Horario Preferencial') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="hora2" name="hora"
                                            value="{{ old('hora', $appointment->hora ?? null) }}" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="vc_plataforma">Plataforma preferida <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="vc_plataforma" name="vc_plataforma" required>
                                            <option value="">-- Selecionar --</option>
                                            <option value="Google Meet"
                                                {{ old('vc_plataforma', $appointment->vc_pataforma ?? null) == 'Google Meet' ? 'selected' : '' }}>
                                                Google Meet
                                            </option>
                                            <option value="Zoom"
                                                {{ old('vc_plataforma', $appointment->vc_pataforma ?? null) == 'Zoom' ? 'selected' : '' }}>
                                                Zoom</option>
                                         
                                            <option value="Chamada Telefónica"
                                                {{ old('vc_plataforma', $appointment->vc_pataforma ?? null) == 'Chamada Telefónica' ? 'selected' : '' }}>
                                                Chamada Telefónica</option>
                                            <option value="Presencial"
                                                {{ old('vc_plataforma', $appointment->vc_pataforma ?? null) == 'Presencial' ? 'selected' : '' }}>
                                                Presencial
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" id="div_link_acesso">
                                        <label for="vc_link_acesso">Link de Acesso</label>
                                        <input type="text" class="form-control" id="vc_link_acesso"
                                            value="{{ old('link_reuniao', $appointment->link_reuniao ?? null) }}"
                                            name="vc_link_acesso" readonly>
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
@endpush
