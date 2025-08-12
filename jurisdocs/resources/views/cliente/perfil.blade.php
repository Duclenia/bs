@extends('admin.layout.app')
@section('title',__('Profile'))
@push('style')
<link href="{{ asset('assets/admin/Image-preview/dist/css/bootstrap-imageupload.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/jcropper/css/cropper.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Minha conta</h3>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
                <div class="x_title">
                    <h2>Detalhes do Perfil</h2>
                    <div class="clearfix"></div>
                </div>

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

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_content">

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation"
                                        class="@if(request()->segment(2)=='admin-profile') active @else @endif"><a
                                            href="{{ url('admin/admin-profile') }}">{{__('Profile')}}</a>
                                    </li>

                                    <li role="presentation"
                                        class="@if(request()->segment(3)=='password') active @else @endif"><a
                                            href="{{ url('admin/change/password') }}">{{__('Change Password')}}</a>

                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="profile_detail"
                                         aria-labelledby="profile">
                                        <form id="add_user" name="add_user" role="form" method="POST"
                                              enctype="multipart/form-data"
                                              action="{{ url('cliente/editar-perfil')}}">
                                            @csrf

                                            <input type="hidden" id="id" name="id" value="{{ $user->id}}">
                                            <input type="hidden" id="imagebase64" name="imagebase64">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xs-12">

                                                    <div class="row">
                                                        <div class="col-md-12 text-center dimage">
                                                            @if($user->cliente->foto !='')
                                                            <img id="crop_image"
                                                                 src='{{asset('public/'.config('constants.CLIENT_FOLDER_PATH') .'/'. $user->cliente->foto)}}'
                                                                 width='100px' height='100px'
                                                                 class="crop_image_profile"
                                                                 >
                                                            <div class="contct-info">
                                                                <label id="remove_crop">
                                                                    <input type="checkbox" value="Yes"
                                                                           name="is_remove_image"
                                                                           id="is_remove_image">&nbsp;{{__('Remove profile picture')}}
                                                                </label>
                                                            </div>
                                                            @else
                                                            <img id="demo_profile"
                                                                 src='{{asset('upload/profile.png')}}'
                                                                 width='100px'
                                                                 height='100px'
                                                                 class="crop_image_profile"
                                                                 >

                                                            @endif


                                                            <div class="imageupload">
                                                                <div class="file-tab">

                                                                    <div
                                                                        id="upload-demo"
                                                                        class="upload-demo"

                                                                        ></div>
                                                                    <div id="upload-demo-i"
                                                                         ></div>

                                                                    <br>
                                                                    <label class="btn btn-link btn-file">
                                                                        <span class="fa fa-upload text-center font-15 set-profile-picture" ><span
                                                                                > &nbsp;Definir foto de perfil</span>
                                                                        </span>
                                                                        <!-- The file is stored here. -->
                                                                        <input type="file" id="upload" name="image"
                                                                               data-src="{{ $user->id}}">

                                                                    </label>
                                                                    <button type="button" class="btn btn-default"
                                                                            id="cancel_img">{{__('Cancel')}}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-8 col-sm-12 col-xs-12">
                                                    <div class="row form-group">

                                                        <div class="col-md-4">
                                                            <label for="no_registo">N&ordm; de registo </label>
                                                            <input  class="form-control" value="{{str_pad($user->cliente->id, 5, '0', STR_PAD_LEFT)}}" readonly>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="tipo_cliente">Tipo de cliente <span 
                                                                    class="text-danger">*</span></label>
                                                            <select name="tipo_cliente" class="form-control" required="" id="tipo_cliente">
                                                                @foreach($tipospessoas as $tipopessoa)
                                                                <option value="{{$tipopessoa->id}}"
                                                                        @if($tipopessoa->id == $user->cliente->tipo) selected @endif>
                                                                        {{$tipopessoa->designacao}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="f_name">{{__('First Name')}} <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="f_name" name="f_name"
                                                                   placeholder="" class="form-control"
                                                                   value="{{ $user->cliente->nome}}" autocomplete="off">
                                                        </div>

                                                    </div>

                                                    <div class="row form-group">

                                                        <div class="col-md-4">
                                                            <label for="last_name">{{__('Last Name')}} <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="l_name" name="l_name"
                                                                   class="form-control"
                                                                   value="{{ $user->cliente->sobrenome}}" autocomplete="off">
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label for="nome_meio">Institui&ccedil;&atilde;o</label>
                                                            <input type="text" id="nome_meio" name="nome_meio"
                                                                   class="form-control"
                                                                   value="{{ $user->cliente->instituicao}}" autocomplete="off">
                                                        </div>

                                                        <div class="col-md-4 col-sm-12 col-xs-12 genero">
                                                            <label for="sexo">G&eacute;nero <span class="text-danger">*</span></label>
                                                            <select name="sexo" id="sexo" class="form-control">
                                                                <option value="F" @if($user->cliente->sexo == 'F') selected @endif>Feminino</option>
                                                                <option value="M" @if($user->cliente->sexo == 'M') selected @endif>Masculino</option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="row form-group">

                                                        <div class="col-md-4 col-sm-12 col-xs-12 ddn">
                                                            <label for="ddn">{{__('Date Of Birth')}} <span class="text-danger">*</span></label>
                                                            <input type="text" name="ddn" id="ddn"  class="form-control"

                                                                   data-inputmask-alias="{{$date_format_datepiker}}" data-inputmask="'yearrange': { 'maxyear': '{{ date('Y') }}' }"
                                                                   data-date-end-date="0d" data-mask

                                                                   >
                                                        </div>

                                                        <div class="col-md-6 col-sm-12 col-xs-12 nome_pai">
                                                            <label for="nome_pai">{{__('Father\'s name')}} </label>
                                                            <input type="text" id="nome_pai" name="nome_pai"
                                                                   placeholder="" class="form-control"
                                                                   value="{{ $user->cliente->nome_pai}}" autocomplete="off">
                                                        </div>

                                                    </div>


                                                    <div class="row form-group">

                                                        <div class="col-md-6 col-sm-12 col-xs-12 nome_mae">
                                                            <label for="nome_meio">{{__('Mother\'s name')}}</label>
                                                            <input type="text" id="nome_mae" name="nome_mae"
                                                                   class="form-control"
                                                                   value="{{ $user->cliente->nome_mae}}" autocomplete="off">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="email">{{__('E-mail')}} <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="email" name="email"
                                                                   class="form-control" value="{{ $user->email}}">
                                                        </div>

                                                    </div>

                                                    <div class="row form-group">

                                                        <div class="col-md-4 col-sm-12 col-xs-12">

                                                            <label for="language">{{__('Language')}}</label>

                                                            <select name='language' class="form-control">

                                                                @foreach($languages as $lang)

                                                                <option value="{{$lang->iso}}" {!! auth()->user()->language == $lang->iso ? 'selected' : '' !!}>
                                                                        {{$lang->name}}
                                                                </option>

                                                                @endforeach
                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="form-group pull-right">
                                                    <div class="col-md-12 col-sm-6 col-xs-12">
                                                        <br>
                                                        <input type="hidden" name="route-exist-check"
                                                               id="route-exist-check"
                                                               value="{{ url('admin/check_user_email_exits') }}">
                                                        <input type="hidden" name="token-value"
                                                               id="token-value"
                                                               value="{{csrf_token()}}">

                                                        <button type="submit" class="btn btn-success"
                                                                id="upload-result"><i class="fa fa-save"
                                                                              id="show_loader"></i>&nbsp;{{__('Update')}}
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">
@endsection

@push('js')
<script src="{{asset('assets/admin/js/selectjs.js')}}"></script>
<script src="{{ asset('assets/admin/jcropper/js/cropper.min.js') }}"></script>

<script src="{{asset('assets/cliente/perfil/image-crop.js')}}"></script>
<script src="{{asset('assets/cliente/perfil/profile-validation.js')}}"></script>
<script src="{{asset('assets/cliente/perfil/change-password-validation.js')}}"></script>

<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>

<script>

$(function () {

    //Money Euro
    $('[data-mask]').inputmask();
    // inputmask
    $(":input[data-inputmask-mask]").inputmask();
    $(":input[data-inputmask-alias]").inputmask();
    $(":input[data-inputmask-regex]").inputmask("Regex");
});

</script>

@if(!empty($user->cliente->tipo) && $user->cliente->tipo !='2')
<script type="text/javascript">
    $('.genero').css('display', 'none');
    //$('.estado_civil').css('display', 'none');
    $('.ddn').css('display', 'none');
    $('.nome_pai').css('display', 'none');
    $('.nome_mae').css('display', 'none');
</script>
@endif

@endpush
