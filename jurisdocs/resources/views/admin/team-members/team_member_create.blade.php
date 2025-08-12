@extends('admin.layout.app')
@section('title','Adicionar membro')
@push('style')
<link href="{{ asset('assets/admin/Image-preview/dist/css/bootstrap-imageupload.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/jcropper/css/cropper.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>{{__('Add Member')}}</h3>
    </div>

    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{ url('admin/client_user') }}" class="btn btn-primary">{{__('Back')}}</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        @include('component.error')
        <div class="x_panel">
            <div class="x_content">
                <form id="add_user" name="add_user" role="form" method="POST"
                      action="{{route('client_user.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" id="imagebase64" name="imagebase64">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="imageupload">
                                        <div class="file-tab">

                                            <img id="demo_profile" src="{{asset('upload/profile.png')}}"
                                                 width='100px' height='100px'
                                                 class="demo_profile">

                                            <div id="upload-demo" class="upload-demo-img"></div>


                                            <br>

                                            <label class="btn btn-link btn-file">
                                                <span class="fa fa-upload text-center font-15"><span
                                                        class="set-profile-picture"> &nbsp; Definir foto de perfil</span>
                                                </span>
                                                <!-- The file is stored here. -->
                                                <input type="file" id="upload" name="image" data-src="">

                                            </label>
                                            <button type="button" class="btn btn-default" id="cancel_img">{{__('Cancel')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="f_name">{{__('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="f_name" name="f_name" class="form-control" value="{{old('f_name')}}" autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name">{{__('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="l_name" name="l_name" value="{{old('l_name')}}" class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="row form-group">

                                <div class="col-md-3">
                                    <label for="sexo">Gen&eacute;ro <span class="text-danger">*</span></label>
                                    <select name="sexo" id="sexo" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="F" {!! old('sexo') == 'F' ? 'selected' : '' !!}>{{__('Female')}}</option>
                                        <option value="M" {!! old('sexo') == 'M' ? 'selected' : '' !!}>{{__('Male')}}</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="ddn">{{__('Date Of Birth')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="ddn" id="ddn"  class="form-control" data-inputmask-alias="{{$date_format_datepiker}}"
                                           data-inputmask="'yearrange': { 'maxyear': '{{ date('Y') }}' }" data-mask readonly
                                           >
                                </div>

                                <div class="col-md-6">
                                    <label for="email">E-mail <span class="text-danger">*</span></label>
                                    <input type="text" id="email" name="email" value="{{old('email')}}" class="form-control" autocomplete="off">
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="password">{{__('Password')}} <span class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password" class="form-control"
                                           autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <label for="cnm_password">{{__('Confirm Password')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="cnm_password" name="cnm_password"
                                           class="form-control" autocomplete="off">
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="Role">Fun&ccedil;&atilde;o <span class="text-danger">*</span></label>
                                    <select id="role" name="role" required class="form-control select2">
                                        <option value="" selected disabled> Seleccionar fun&ccedil;&atilde;o</option>
                                        @foreach($funcoes as $funcao)
                                        <option value="{{ $funcao->id}}" {!! old('role') == $funcao->id ? 'selected' : '' !!}>
                                                {{$funcao->nome}}
                                        </option>

                                        @endforeach


                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="form-group pull-right">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                <br>
                                <a class="btn btn-danger" href="{{ url('admin/client_user') }}">{{__('Cancel')}}</a>

                                <button type="submit" class="btn btn-success" id="upload-result"><i
                                        class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="check_user_email_exits"
       id="check_user_email_exits"
       value="{{ url('admin/check_user_email_exits') }}">

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">

@endsection
@push('js')
<script src="{{asset('assets/admin/js/selectjs.js')}}"></script>
<script src="{{ asset('assets/admin/jcropper/js/cropper.min.js') }}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/admin/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js')}}"></script>
<script src="{{asset('assets/js/team_member/member-validation.js')}}"></script>

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

@endpush
