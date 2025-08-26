                <div class="x_content">
                    @include('component.error')

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="tipo_cliente">Tipo de cliente <span class="text-danger">*</span></label><br>

                        <select name="new_client" class="form-control" id="tipo_cliente" required="">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($tipospessoas as $tipopessoa)
                                <option value="{{ $tipopessoa->id }}" {!! old('tipo_cliente') == $tipopessoa->id ? 'selected' : '' !!}>
                                    {{ $tipopessoa->designacao }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group f_name" style="display: none">
                        <label for="f_name">{{ __('First Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="f_name" class="form-control text-uppercase" id="f_name"
                            autocomplete="off" data-msg-required="Por favor, insere o nome do cliente">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group l_name" style="display: none">
                        <label for="l_name">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="l_name" class="form-control text-uppercase"
                            value="{{ old('l_name') }}" id="l_name" autocomplete="off"
                            data-msg-required="Por favor, insere o sobrenome do cliente">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group instituicao" style="display: none">
                        <label for="instituicao">Institui&ccedil;&atilde;o <span class="text-danger">*</span></label>
                        <input type="text" name="instituicao" class="form-control text-uppercase"
                            value="{{ old('instituicao') }}" id="instituicao" autocomplete="off"
                            data-msg-required="Por favor, insere a instituição">
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group documento" style="display: none">
                        <label for="documento">Documento de Identifica&ccedil;&atilde;o <span
                                class="text-danger">*</span></label>
                        <select name="documento" class="form-control" id="documento" required
                            data-msg-required="Por favor, seleccione o tipo de documento de identificação.">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($tiposdocumentos as $tipodocumento)
                                <option value="{{ $tipodocumento->id }}" {!! old('documento') == $tipodocumento->id ? 'selected' : '' !!}>
                                    {{ mb_strtoupper($tipodocumento->designacao) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group ndi" style="display: none">
                        <label for="ndi"> N&ordm do documento de Identifica&ccedil;&atilde;o <span
                                class="text-danger">*</span></label>
                        <input type="text" name="ndi" class="form-control text-uppercase"
                            value="{{ old('ndi') }}" id="ndi" required
                            data-msg-required="Por favor, insere o nº do documento de identificação">
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group ddvdoc" style="display: none;">
                        <label for="ddvdoc">Data de Validade do documento de identifica&ccedil;&atilde;o </label>
                        <input type="date" name="" class="form-control" id="data_bi" autocomplete="off">
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group estado_civil" style="display: none;">
                        <label for="estado_civil">{{ __('Marital status') }} <span class="text-danger">*</span></label>
                        <select name="estado_civil" class="form-control" id="estado_civil"
                            data-msg-required="Por favor, seleccione o estado civil">
                            <option value="" selected disabled> Seleccionar estado civil</option>
                            <option value="C" {!! old('estado_civil') == 'C' ? 'selected' : '' !!}> {{ __('Married') }}</option>
                            <option value="D" {!! old('estado_civil') == 'D' ? 'selected' : '' !!}> {{ __('Divorced') }}</option>
                            <option value="S" {!! old('estado_civil') == 'S' ? 'selected' : '' !!}> SOLTEIRO(A)</option>
                            <option value="V" {!! old('estado_civil') == 'V' ? 'selected' : '' !!}> Vi&Uacute;VO(A)</option>

                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group regime_casamento" style="display: none;">
                        <label for="regime_casamento">Regime do casamento </label>
                        <select name="regime_casamento" class="form-control" id="estado_civil">
                            <option value="" selected disabled> Seleccionar regime do casamento</option>
                            <option value="CB" {!! old('regime_casamento') == 'CB' ? 'selected' : '' !!}> Comunh&atilde;o de bens adquiridos</option>
                            <option value="SB" {!! old('regime_casamento') == 'SB' ? 'selected' : '' !!}> Separa&ccedil;&atilde;o de bens</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="nif" id="lb_nif">N&ordm; de Identifica&ccedil;&atilde;o Fiscal</label>
                        <input type="text" name="nif" class="form-control text-uppercase"
                            value="{{ old('nif') }}" id="nif" autocomplete="off"
                            data-msg-required="Por favor, insere o Nº de Identificação Fiscal.">
                    </div>


                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" class="form-control email" value="{{ old('email') }}"
                            id="email" autocomplete="off">
                    </div>



                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">

                        <label for="mobile">Telefone <span class="text-danger">*</span></label>
                        <input type="number" maxlength="9" minlength="9" class="form-control" id="mobile00"
                            name="mobile" value="{{ old('mobile') }}" autocomplete="off">

                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="alternate_no">Telefone Alternativo</label>
                        <input type="number" name="alternate_no" class="form-control"
                            value="{{ old('alternate_no') }}" id="alternate_no" autocomplete="off">
                    </div>

                    <div class="country col-md-4 col-sm-12 col-xs-12 form-group">
                        <label for="country">{{ __('Country') }} <span class="text-danger">*</span></label>
                        <select class="form-control select-change country-select2" required="" name="country"
                            id="country" data-url="{{ route('get.country') }}" data-clear="#city_id,#state">
                            <option value=""> Seleccionar pa&iacute;s</option>

                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group provincia" style="display:none">
                        <label for="state">{{ __('Province') }} </label>
                        <select id="state" name="state" data-url="{{ route('get.state') }}"
                            data-target="#country" data-clear="#city_id"
                            class="form-control state-select2 select-change">
                            <option value=""> Seleccionar prov&iacute;ncia</option>

                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 form-group municipio" style="display:none">
                        <label for="city_id">Munic&iacute;pio</label>
                        <select id="city_id" name="city_id" data-url="{{ route('get.city') }}"
                            data-target="#state" class="form-control city-select2">
                            <option value=""> Seleccionar Munic&iacute;pio</option>

                        </select>
                    </div>


                    <div class="col-md-8 col-sm-12 col-xs-12 form-group">
                        <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                            id="address" required autocomplete="off">
                    </div>



                    <div id="change_court_div" class="hidden">

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="type">{{ __('Client') }} <span
                                        class="text-danger">*</span></label><br>
                                <br>
                                <input type="radio" name="type_client" id="test6" value="single"
                                    checked="" required />
                                &nbsp;&nbsp;&Uacute;nico advogado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="type_client" id="test7"
                                    value="multiple" />&nbsp;&nbsp;V&aacute;rios advogados
                            </div>
                        </div>
                        <div class="repeater one">
                            <div data-repeater-list="group-a">
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="firstname">{{ __('First Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="firstname" name="firstname"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o primeiro nome."
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="middlename">{{ __('Middle Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="middlename" id="middlename"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o nome do meio."
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="lastname">{{ __('Last Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="lastname" name="lastname"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o sobrenome."
                                                class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="mobile_client" id="mobile_client"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                data-rule-number="true" data-msg-number="please enter digit 0-9."
                                                data-rule-minlength="9"
                                                data-msg-minlength="O telefone deve ter 9 g&iacute;gitos."
                                                data-rule-maxlength="9"
                                                data-msg-maxlength="O telefone deve ter 9 g&iacute;gitos."
                                                class="form-control" maxlength="9">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="address_client">{{ __('Address') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="address_client" name="address_client"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere a morada." class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <br>
                                            <button type="button" data-repeater-delete type="button"
                                                class="btn btn-danger"><i class="fa fa-trash-o"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="repeater two">
                            <div data-repeater-list="group-b">
                                <div data-repeater-item>
                                    <div class="row border-addmore">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="firstname">{{ __('First Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="firstname" name="firstname"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o primeiro nome."
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="middlename">{{ __('Middle Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="middlename" name="middlename"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o nome do meio."
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="lastname">{{ __('Last Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="lastname" name="lastname"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o sobrenome."
                                                class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="mobile_client">Telefone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="mobile_client" name="mobile_client"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o n&ordm; de telefone."
                                                class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="address_client">{{ __('Address') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="address_client" name="address_client"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere a morada." class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label for="advocate_name">Nome do Advogado <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="advocate_name" name="advocate_name"
                                                data-rule-required="true"
                                                data-msg-required="Por favor, insere o nome do advogado."
                                                class="form-control">
                                        </div>


                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <input type="hidden" name="date_format_datepiker" id="date_format_datepiker"
                    value="{{ $date_format_datepiker }}">


                    <input type="hidden" id="utils"
                    value="{{ asset('assets/plugins/intl-tel-input/js/utils.js') }}">

                <input type="hidden" name="token-value" id="token-value" value="{{ csrf_token() }}">

                <input type="hidden" name="common_check_exist" id="common_check_exist"
                    value="{{ url('common_check_exist') }}">

                <input type="hidden" name="check_user_email_exits" id="check_user_email_exits"
                    value="{{ url('admin/check_user_email_exits') }}">

                <input type="hidden" id="language" value="{{ app()->getLocale() }}">

                <script>
                    const data_bi = document.getElementById("data_bi");
                    const hoje_h = new Date().toISOString().split("T")[0];
                    data_bi.min = hoje_h;
                </script>
                @push('js')
                    <script src="{{ asset('assets/admin/js/selectjs.js') }}"></script>
                    <script src="{{ asset('assets/admin/vendors/repeter/repeater.js') }}"></script>
                    <script src="{{ asset('assets/admin/vendors/jquery-ui/jquery-ui.js') }}"></script>
                    <script src="{{ asset('assets/js/masked-input/masked-input.min.js') }}"></script>
                    <script src="{{ asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
                    <script
                        src="{{ asset('assets/admin/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.' . app()->getLocale() . '.min.js') }}">
                    </script>

                    <script src="{{ asset('assets/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
                    <script src="{{ asset('assets/plugins/intl-tel-input/js/utils.js') }}"></script>


                    <script src="{{ asset('assets/js/cliente/add-client-validation.js') }}"></script>

                    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.bundle.js') }}"></script>
                    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
                    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
                    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

                    <script>
                        $(function() {

                            //Money Euro
                            $('[data-mask]').inputmask();
                            // inputmask
                            $(":input[data-inputmask-mask]").inputmask();
                            $(":input[data-inputmask-alias]").inputmask();
                            $(":input[data-inputmask-regex]").inputmask("Regex");
                        });
                    </script>

                    @if (old('tipo_cliente') != '2')
                        <script>
                            $(document).ready(function() {
                                $('.f_name').hide();
                                $('#f_name').prop('required', false).val('');

                                $('.l_name').hide();
                                $('#l_name').prop('required', false).val('');

                                $('.instituicao').show();
                                $('#instituicao').prop('required', true);

                                $('.estado_civil').hide();

                                $('.regime_casamento').hide();

                                $('.documento').hide();
                                $('#documento').prop('required', false);

                                $('.ndi').hide();
                                $('#ndi').prop('required', false);

                                $('.ddvdoc').hide();

                                $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
                                $('#nif').prop('required', true);
                            });
                        </script>
                    @else
                        <script>
                            $(document).ready(function() {
                                $('.f_name').show();
                                $('#f_name').prop('required', true);

                                $('.l_name').show();
                                $('#l_name').prop('required', true);

                                $('.instituicao').hide();
                                $('#instituicao').prop('required', false).val('');

                                $('.estado_civil').show();

                                $('.documento').show();
                            $('#documento').prop('required', true);

                            $('.ndi').show();
                            $('#ndi').prop('required', true);

                            $('.ddvdoc').show();

                            @if (old('estado_civil') == 'C')
                                $('.regime_casamento').show();
                            @else
                                $('.regime_casamento').hide();
                            @endif

                            $('#lb_nif').html('Nº de Identificação Fiscal');
                            $('#nif').prop('required', false);
                        </script>
                    @endif


                    @if (old('documento') == 1 || old('documento') == 2)
                        <script>
                            $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
                            $('#nif').prop('required', true);
                        </script>
                    @else
                        <script>
                            $('#lb_nif').html('Nº de Identificação Fiscal');
                            $('#nif').prop('required', false);
                        </script>
                    @endif

                @endpush
