@extends('admin.layout.app')
@section('title','Editar membro')
@push('style')
<link href="{{ asset('assets/admin/Image-preview/dist/css/bootstrap-imageupload.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/jcropper/css/cropper.min.css') }}" rel="stylesheet">
@endpush
@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>{{__('Edit Member')}}</h3>
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
                <form id="add_user" name="add_user" role="form" method="POST" enctype="multipart/form-data"
                      action="{{route('client_user.update',$adm->id)}}">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" id="id" name="id" value="{{ $adm->id}}">
                    <input type="hidden" id="imagebase64" name="imagebase64">
                    
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">


                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="imageupload">
                                        <div class="file-tab">
                                            @if($adm->pessoasingular->pessoa->foto !='')
                                            <img id="crop_image"
                                                 src='{{asset('public/'.config('constants.CLIENT_FOLDER_PATH') .'/'. $users->profile_img)}}'
                                                 width='100px' height='100px'
                                                 class="crop_image_img"
                                                 >
                                            <br>
                                            <label id="remove_crop">
                                                <input type="checkbox" value="Yes" name="is_remove_image"
                                                       id="is_remove_image">&nbsp;Remove feature image.</label>
                                            @else
                                            <img id="demo_profile" src="{{asset('upload/profile.png')}}"
                                                 width='100px' height='100px'
                                                 class="demo_profile"
                                                 >
                                            @endif
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
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="f_name">{{__('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="f_name" name="f_name" class="form-control"
                                           value="{{ old('f_name', $adm->pessoasingular->nome ?? null)}}" required autocomplete="off">
                                </div>

                                
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="last_name">{{__('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="l_name" name="l_name" class="form-control"
                                           value="{{ $adm->pessoasingular->sobrenome}}" required autocomplete="off">
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="nome_pai"> {{__('Father\'s name')}} </label>
                                    <input type="text" id="nome_pai" name="nome_pai" class="form-control"
                                           value="{{old('nome_pai', $adm->pessoasingular->nome_pai)}}" autocomplete="off">
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="nome_mae"> {{__('Mother\'s name')}} </label>
                                    <input type="text" id="nome_mae" name="nome_mae" class="form-control"
                                           value="{{ $adm->pessoasingular->nome_mae}}" autocomplete="off">
                                </div>
                            </div>

                            <div class="row form-group">

                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="sexo">{{__('Sex')}} <span class="text-danger">*</span></label>
                                    <select name="sexo" id="sexo" class="form-control">
                                        <option value="F" {!! old('sexo',$adm->pessoasingular->sexo) == 'F' ? 'selected' : '' !!}>
                                                {{__('Female')}}
                                        </option>
                                        <option value="M" {!! old('sexo', $adm->pessoasingular->sexo) == 'M' ? 'selected' : '' !!}>
                                                {{__('Male')}}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="ddn">{{__('Date Of Birth')}} </label>
                                    <input type="text" id="ddn" name="ddn" class="form-control" readonly
                                           value="{{date($date_format_laravel,strtotime($adm->pessoasingular->data_nascimento))}}">
                                </div>

                                <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="Role">Fun&ccedil;&atilde;o <span class="text-danger">*</span></label>
                                    <select id="role" name="role" required class="form-control select2">
                                        <option value=""> Seleccionar</option>
                                        @foreach($roles as $roal)
                                        <option
                                            value="{{ $roal->id}}" {{ ($adm->utilizador->funcoes->contains($roal->id) ) ? 'selected=""' : '' }}>{{$roal->nome}}</option>

                                        @endforeach


                                    </select>
                                </div>

                            </div>

                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <input type="checkbox" id="chk_pass" name="chk_pass" value="yes">
                                      {{__('Change Password')}}
                                </div>
                            </div>
                            <div class="row form-group chk">

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

<input type="hidden" name="token-value" id="token-value" value="{{csrf_token()}}">

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
<script src="{{asset('assets/js/team_member/member-validation_edit.js')}}"></script>

@endpush
