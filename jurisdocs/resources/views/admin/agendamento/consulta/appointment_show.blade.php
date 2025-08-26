@extends('admin.layout.app')
@section('title', 'Detalhes da Consulta')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Detalhes da Consulta</h3>
            </div>
            <div class="title_right">
                <div class="form-group pull-right top_search">
                    <a href="{{ route('consulta.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Informações da Consulta</h4>
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Tipo de Consulta:</th>
                                            <td>{{ $appointment->vc_tipo }}</td>
                                        </tr>
                                        <tr>
                                            <th>Área do Direito:</th>
                                            <td>{{ $appointment->vc_area }}</td>
                                        </tr>
                                        <tr>
                                            <th>Plataforma:</th>
                                            <td>{{ $appointment->vc_pataforma }}</td>
                                        </tr>
                                        <tr>
                                            <th>Link da Consulta:</th>
                                            <td>
                                                @if ($appointment->link_reuniao)
                                                    <a href="{{ $appointment->link_reuniao }}"
                                                        target="_blank">{{ $appointment->link_reuniao }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nota:</th>
                                            <td>{{ $appointment->vc_nota ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Termo Aceito:</th>
                                            <td>{{ $appointment->it_termo ? 'Sim' : 'Não' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Envio de Documentos:</th>
                                            <td>{{ $appointment->it_envDocs ? 'Sim' : 'Não' }}</td>
                                        </tr>
                                        @if ($appointment->vc_caminho_documento)
                                            <tr>
                                                <th>Documento:</th>
                                                <td>
                                                    <a href="{{ asset('storage/' . $appointment->vc_caminho_documento) }}"
                                                        target="_blank">
                                                        Ver Documento
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h4>Informações da Agenda</h4>
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Data:</th>
                                            <td>{{ date('d/m/Y', strtotime($appointment->data)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hora:</th>
                                            <td>{{ date('H:i', strtotime($appointment->hora)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Telefone:</th>
                                            <td>{{ $appointment->telefone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if ($appointment->activo == 'OPEN')
                                                    <span class="badge badge-success">Aberto</span>
                                                @elseif($appointment->activo == 'CANCEL BY CLIENT')
                                                    <span class="badge badge-warning">Cancelado pelo cliente</span>
                                                @elseif($appointment->activo == 'CANCEL BY ADVOCATE')
                                                    <span class="badge badge-danger">Cancelado pelo advogado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observação:</th>
                                            <td>{{ $appointment->observacao ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br> <br>
                        @if ($appointment->cliente_sobrenome || $appointment->cliente_instituicao)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Informações do Cliente</h4>
                                    <table class="countries_list">
                                        <tbody>
                                            <tr>
                                                <th width="15%">Nome:</th>
                                                <td>{{ $appointment->cliente_nome }} {{ $appointment->cliente_sobrenome }}
                                                </td>
                                            </tr>
                                            @if ($appointment->cliente_instituicao)
                                                <tr>
                                                    <th>Instituição:</th>
                                                    <td>{{ $appointment->cliente_instituicao }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
