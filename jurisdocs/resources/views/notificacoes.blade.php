@extends('admin.layout.app')
@section('title','Notificações')
@section('content')
@component('component.heading' , [
'page_title' => 'Notificações',
])
@endcomponent
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        @include('component.error')
        <div class="x_panel">

            @forelse($notificacoes as $notificacao)
            <div class="alert alert-success" role="alert">

                [{{ date('d-m-Y H:i:s', strtotime($notificacao->created_at))  }}] {{$notificacao->data['notificacao']}}


                <a href="#" class="float-right mark-as-read" data-id="{{ $notificacao->id }}">
                    Marcar como lida
                </a>
            </div>

            @if($loop->last)
            <a href="#" id="mark-all">
                Marcar todas como lidas
            </a>
            @endif

            @empty

            N&atilde;o h&aacute; notifica&ccedil;&otilde;es

            @endforelse

        </div>

    </div>
</div>

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">

@endsection

@push('js')
<script src="{{asset('assets/admin/js/selectjs.js')}}"></script>
<script src="{{asset('assets/admin/vendors/repeter/repeater.js')}}"></script>
<script src="{{asset('assets/admin/vendors/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js')}}"></script>
<script src="{{asset('assets/js/cliente/add-client-validation.js')}}"></script>

<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>    
@endpush
