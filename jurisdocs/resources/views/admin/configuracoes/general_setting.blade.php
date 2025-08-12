@extends('admin.layout.app')
@section('title',__('General Setting'))
@push('style')

@endpush
@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>{{__('General Setting')}}</h3>
        </div>


        <div class="title_right">
            <div class="form-group pull-right top_search">
            </div>
        </div>
    </div>

        <div class="clearfix"></div>
    <form id="mail_setup" name="mail_setup" role="form" method="POST"
          action="{{ route('general-setting.update',$GeneralSettings->id) }}" enctype="multipart/form-data"
          autocomplete="off">
        @csrf()
        <input type="hidden" name="_method" value="PATCH">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        @include('admin.configuracoes.setting-header')

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="invoice_prefex">{{__('Company Name')}} <span class="text-danger">*</span></label>
                                <input type="text" required data-msg-required="Por favor, insere o nome da empresa" placeholder=""
                                       class="form-control" id="cmp_name" name="cmp_name"
                                       value="{{ $GeneralSettings->nome_escritorio }}">
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>{{__('Address')}}</h2>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="x_content">

                                        <div class="row">

                                            <div class="col-md-4 form-group">
                                                <label for="state">{{__('Province')}} <span class="text-danger">*</span></label>
                                                <select id="provincia" name="provincia" data-clear="#municipio_id" class="form-control" data-rule-required="true"
                                                        data-msg-required=" Por favor, seleccione a província"
                                                        data-url = "{{route('get.state')}}"
                                                        >
                                                    <option value="{{$GeneralSettings->endereco->municipio->provincia->id ?? ''}}"> {{$GeneralSettings->endereco->municipio->provincia->nome ?? ''}}</option>

                                                </select>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label for="municipio_id">Munic&iacute;pio <span class="text-danger">*</span></label>
                                                <select id="municipio_id" name="municipio_id"
                                                        class="form-control" data-rule-required="true"
                                                        data-msg-required=" Por favor, seleccione o município"
                                                        data-url= "{{route('get.city')}}"
                                                        >
                                                    <option value="{{$GeneralSettings->endereco->municipio->id ?? ''}}"> {{$GeneralSettings->endereco->municipio->nome ?? ''}}</option>

                                                </select>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label for="bairro">{{__('Neighborhood')}} </label>
                                                <select id="bairro_id" name="bairro_id" class="form-control"
                                                         data-url="{{route('get.bairro')}}"
                                                     >
                                                    <option value="{{ $GeneralSettings->endereco->bairro->id ?? ''}}"> {{ $GeneralSettings->endereco->bairro->nome ?? ''}}</option>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="rua">{{__('Street')}} </label>
                                                <input type="text" name="rua" class="form-control" id="rua" value="{{$GeneralSettings->endereco->rua}}">
                                            </div>

                                            <div class="col-md-2 col-sm-12 col-xs-12 form-group numero">
                                                <label for="numero">N&uacute;mero</label>
                                                <input type="text" name="numero" class="form-control" id="numero" value="{{$GeneralSettings->endereco->numero}}">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <label for="invoice_number">Pincode <span class="text-danger">*</span></label>
                                <input type="text" placeholder="" data-msg-required="Please enter pincode"
                                       class="form-control" id="pincode" name="pincode" required
                                       value="{{ $GeneralSettings->pincode }}">
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <div class="valfavicon">
                                    <label for="invoice_number">favicon </label>

                                    <input type="file" name="favicon" id="favicon" class="form-control"
                                           data-min-width="16" data-min-height="16" data-max-width="16"
                                           data-max-height="16">
                                    <span class="text-danger">(Nota:O tamanho da imagem deve ser 16 de largura e 16 de altura)</span>
                                    
                                </div>


                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <div class="vallogo">

                                    <label for="invoice_number">logo </label>
                                    <input type="file" placeholder="" class="form-control" id="logo" name="logo"
                                           data-min-width="230" data-min-height="46" data-max-width="230"
                                           data-max-height="46">
                                    <span class="text-danger"> (Nota:O tamanho da imagem deve ser 230 de largura e 46 de altura)</span>
                                    @if($GeneralSettings->logo_img!='')
                                        <br>
                                        <br>
                                        <img height="46" width="230"
                                             src="{{asset('public/'.config('constants.LOGO_FOLDER_PATH') .'/'. $GeneralSettings->logo_img)}}">
                                    @endif
                                </div>
                            </div>


                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn btn-success" name="btn_add_smtp"><i
                                            class="fa fa-save"
                                            id="show_loader"></i>&nbsp;{{__('Save')}}
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
        
 <input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

@endsection
@push('js')
    
    <script src="{{asset('assets/admin/js/jquery.checkImageSize.js')}}"></script>
    <script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
    <script src="{{asset('assets/js/configuracoes/general-setting-validation.js')}}"></script>
@endpush
