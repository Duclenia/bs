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
                                        value="{{ old('mobile', Auth::user()->cliente->telefone) }}" autocomplete="off" readonly>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="email">Endereço de e-mail <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::User()->email) }}" autocomplete="off" readonly>
                                </div>

                                @include('admin.agendamento.consulta.agenda')

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
                <div class="modal-body">
                    <p><strong>Resumo:</strong></p>
                    <p>Os dados fornecidos neste formulário serão utilizados exclusivamente para fins de agendamento e
                        comunicação relativa à reunião marcada.</p>
                    <p>Garantimos que não serão partilhados com terceiros sem o seu consentimento, conforme previsto na Lei
                        Geral de Proteção de Dados.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" target="_blank" class="btn btn-link">📄 Ler documento completo</a>
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
            const radioSim = document.getElementById('radio-sim');
            const radioNao = document.getElementById('radio-nao');
            const documentoInput = document.getElementById('documento-input');
            const btnSubmitExisting = document.getElementById('btn_submit_existing');
            checkbox.addEventListener('change', function() {
                btnSubmit.disabled = !this.checked;
                btnSubmitExisting.disabled = !this.checked;
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
        });
    </script>
@endsection

@push('js')
    <script src="{{ asset('assets/cliente/appointment/appointment.js') }}"></script>
    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
    <script src="{{ asset('assets/cliente/appointment/appointment-validation.js') }}"></script>
@endpush
