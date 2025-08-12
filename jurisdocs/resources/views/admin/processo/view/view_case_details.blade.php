@extends('admin.layout.app')
@section('title','Detalhes do processo')
@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                @include('admin.processo.view.card_header')
                <div class="dashboard-widget-content">
                    <h2 class="line_30 case_detail-m-f-10">Detalhes do processo</h2>
                    <div class="col-md-6 hidden-small">

                        <table class="countries_list">
                            <tbody>
                                <tr>
                                    <td>Tipo de processo</td>
                                    <td class="fs15 fw700 text-right">{{$case->caseType}}</td>
                                </tr>
                                
                                <tr>
                                    <td>Natureza do processo</td>
                                    <td class="fs15 fw700 text-right">{{$case->areaprocessual}}</td>
                                </tr>
                                
                                <tr>
                                    <td>Estado do processo</td>
                                    <td class="fs15 fw700 text-right">{{$case->estado}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 hidden-small">

                        <table class="countries_list">
                            <tbody>

                                <tr>
                                    <td>Tribunal</td>
                                    <td class="fs15 fw700 text-right">{{$case->tribunal}}</td>
                                </tr>
                                <tr>
                                    <td>Magistrado(a)</td>
                                    <td class="fs15 fw700 text-right">{{$case->juiz}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <div class="dashboard-widget-content">


                    <div class="col-md-6 hidden-small">
                        <h4 class="line_30">Respondent and Advogado</h4>

                        <table class="countries_list">
                            <tbody>

                                <tr>


                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<div id="load-modal"></div>


<div class="modal fade" id="modal-next-date" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal_next_date">

        </div>
    </div>
</div>

<input type="hidden" name="getNextDateModals"
       id="getNextDateModals"
       value="{{url('admin/getNextDateModal')}}">
@endsection

@push('js')
<script src="{{asset('assets/js/processo/case_view_detail.js')}}"></script>
@endpush





