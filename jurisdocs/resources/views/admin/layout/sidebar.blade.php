<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">

        <ul class="nav side-menu">

            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-tachometer"></i> {{ __('Dashboard') }}</a></li>

            @can('client_list')
                <li><a href="{{ route('clients.index') }}"><i class="fa fa-user-plus"></i> {{ __('Clients') }}</a></li>
            @endcan



            @can('case_list')
                <li><a href="{{ route('processo.index') }}"><i class="fa fa-gavel"></i> Processos</a></li>
            @endcan

            @if (auth()->user()->user_type == 'Cliente')

                @if (auth()->user()->cliente->processos()->count())
                    <li><a href="{{ url('cliente/processos') }}"><i class="fa fa-gavel"></i> Meus Processos</a></li>
                @endif

                <li><a><i class="fa fa-money"></i> {{ __('Agendamentos') }} <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ route('cliente.reuniao.index') }}">{{ __('form reuniao') }}</a></li>
                        <li><a href="{{ route('cliente.consulta.index') }}">{{ __('form consulta') }}</a>
                    </ul>
                </li>

                <li><a href="{{ url('admin/facturaz/cliente') }}"><i class="fa fa-gavel"></i> {{ __('Invoice') }}</a></li>
            @endif

            @can('task_list')
                <li><a href="{{ route('tarefas.index') }}"><i class="fa fa-tasks"></i>{{ __('Tasks') }}</a></li>
            @endcan




            @if (auth()->user()->can('service_list') || auth()->user()->can('invoice_list'))
                <li><a><i class="fa fa-money"></i> {{ __('Agendamentos') }} <span
                            class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        @can('service_list')
                            <li><a href="{{ route('reuniao.index') }}">{{ __('form reuniao') }}</a></li>
                        @endcan

                        @can('invoice_list')
                            <li><a href="{{ route('consulta.index') }}">{{ __('form consulta') }}</a>
                            @endcan

                    </ul>
                </li>
            @endif

            @if (auth()->user()->user_type == 'SuperAdmin')
                <li><a><i class="fa fa-users"></i> {{ __('Team members') }} <span
                            class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ url('admin/client_user') }}"> {{ __('Team members') }}</a></li>
                        <li><a href="{{ route('funcao.index') }}">{{ __('Role') }}</a></li>

                    </ul>
                </li>
            @endif

            @can('listar_planos')
                <li>
                    <a href="{{ route('plano.index') }}"><i class="fa fa-user-plus"></i> {{ __('plans') }}</a>
                </li>
            @endcan

            @can('listar_subscricao')
                <li>
                    <a href="{{ route('subscricao.index') }}">
                        <i class="fa fa-calendar-plus-o"></i>
                        {{ __('Subscriptions') }}
                    </a>
                </li>
            @endcan

            @can('vendor_list')
                <li><a href="{{ route('fornecedor.index') }}"><i class="fa fa-user-plus"></i> Fornecedores</a></li>
            @endcan

            @if (auth()->user()->can('service_list') || auth()->user()->can('invoice_list'))
                <li><a><i class="fa fa-money"></i> {{ __('Income') }} <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        @can('service_list')
                            <li><a href="{{ url('admin/servico') }}">{{ __('Services') }}</a></li>
                        @endcan

                        @can('invoice_list')
                            <li><a href="{{ url('admin/factura') }}">{{ __('Invoice') }}</a>
                            @endcan

                    </ul>
                </li>
            @endif


            @if (auth()->user()->can('expense_type_list') || auth()->user()->can('expense_list'))
                <li><a><i class="fa fa-money"></i> {{ __('Expenses') }} <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">

                        @can('expense_type_list')
                            <li><a href="{{ url('admin/expense-type') }}">{{ __('Expense Type') }}</a></li>
                        @endcan

                        @can('expense_list')
                            <li><a href="{{ url('admin/expense') }}">{{ __('Expenses') }}</a></li>
                        @endcan

                    </ul>
                </li>
            @endif

            @if (auth()->user()->user_type != 'Cliente')
                <li><a><i class="fa fa-gear"></i> {{ __('Settings') }} <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">

                        @can('case_type_list')
                            <li><a href="{{ url('admin/case-type') }}">Tipo de Processo</a></li>
                        @endcan

                        @can('listar_crime_enquad')
                            <li><a href="{{ url('admin/crime-enquad') }}">Enquadramento do crime</a></li>
                        @endcan

                        @can('listar_crime_subenquad')
                            <li><a href="{{ url('admin/crime-sub-enquad') }}">Sub-enquadramento do crime</a></li>
                        @endcan

                        @can('listar_crime')
                            <li><a href="{{ url('admin/crime') }}">Tipo de crime</a></li>
                        @endcan

                        @can('listar_orgao_judiciario')
                            <li><a href="{{ url('admin/orgao-judiciario') }}">&Oacute;rg&atilde;o judici&aacute;rio</a>
                            </li>
                        @endcan

                        @can('court_list')
                            <li><a href="{{ url('admin/tribunal') }}">{{ __('Court') }}</a></li>
                        @endcan

                        @can('listar_seccao')
                            <li><a href="{{ url('admin/seccao') }}">Sec&ccedil;&atilde;o</a></li>
                        @endcan

                        @can('listar_bairro')
                            <li><a href="{{ url('admin/bairro') }}">Bairro</a></li>
                        @endcan

                        @can('listar_intervdesignacao')
                            <li><a href="{{ url('admin/interv-designacao') }}">Interv. designa&ccedil;&atilde;o</a></li>
                        @endcan

                        @can('case_status_list')
                            <li><a href="{{ url('admin/case-status') }}">Estado do processo</a></li>
                        @endcan

                        @can('judge_list')
                            <li><a href="{{ url('admin/juiz') }}">{{ __('Judge') }}</a></li>
                        @endcan

                        @can('tax_list')
                            <li><a href="{{ url('admin/tax') }}">Imposto</a></li>
                        @endcan


                        @can('general_setting_edit')
                            <li><a href="{{ url('admin/general-setting') }}">{{ __('General Setting') }}</a></li>
                        @endcan

                        @can('appointment_list')
                            <li><a href="{{ route('horario.index') }}"> Horario</a></li>
                        @endcan

                        @if (auth()->user()->user_type == 'Admin')
                            <li><a href="{{ url('admin/database-backup') }}">{{ __('Database Backup') }}</a></li>
                        @endif

                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>
