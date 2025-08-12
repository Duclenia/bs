@extends('admin.layout.app')
@section('title','Invoice Setting')
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
    <form id="mail_setup" name="mail_setup" role="form" method="POST"
          action="{{ route('date-timezone.update',$GeneralSettings->id) }}" enctype="multipart/form-data"
          autocomplete="off">
        @csrf()
        <input type="hidden" name="_method" value="PATCH">
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        @include('admin.configuracoes.setting-header')

                        <div class="row">

                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">

                            </div>

                        </div>


                        <div class="row">


                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="fullname">{{__('Date Formet')}} </label><br>

                                <input type="radio" id="test3" name="forment"
                                       value="1" {{(!empty($GeneralSettings) && $GeneralSettings->formato_data =='1')?'checked':''}} {{empty($GeneralSettings)?'checked':''}}>
                                &nbsp;&nbsp;Dia-M&ecirc;s-Ano&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="test4" name="forment"
                                       value="2" {{(!empty($GeneralSettings) && $GeneralSettings->formato_data == '2')?'checked':''}}>&nbsp;&nbsp;Ano-M&ecirc;s-Dia&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="test5" name="forment"
                                       value="3" {{(!empty($GeneralSettings) && $GeneralSettings->formato_data =='3')?'checked':''}}>&nbsp;&nbsp;M&ecirc;s-Dia-Ano&nbsp;&nbsp;&nbsp;

                            </div>
                        </div>


                        <div class="row">
                            <br>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <label for="timezone">{{__('Timezone')}}<span class="text-danger">*</span></label>
                                <select name="timezone" id="timezone" class="form-control">

                                    @foreach($timezone as $t)
                                        <option value="{{ $t->id }}"
                                                @if(isset($GeneralSettings) && $GeneralSettings->timezone== $t->id) selected @endif>{{ $t->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group pull-right">
                            <div class="col-md-12 col-sm-6 col-xs-12">

                                <button type="submit" class="btn btn-success" name="btn_add_smtp">
                                    <i class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                                </button>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </form>

@endsection



@push('js')
    <script src="{{asset('assets/admin/js/selectjs.js')}}"></script>
    <script src="{{asset('assets/admin/js/jquery.checkImageSize.js')}}"></script>
    <script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

    <script type="text/javascript">
        "use strict";
        $("#timezone").select2();
    </script>
@endpush
