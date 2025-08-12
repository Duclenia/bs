<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            
            
            <ul class="nav navbar-nav navbar-right">

                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">

                        <img src="{{asset('upload/user-icon-placeholder.png')}}" width='50px' height='40px'>
                        {{ getNameUser() }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{ url('admin/admin-profile') }}"> <i
                                    class="fa fa-user"></i>&nbsp;&nbsp;{{__('Profile')}}</a></li>

                        <li><a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out"></i> {{__('Log Out')}}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>

                <li class="dropdown dropdown-alerts">
                    <a href="{{url("/notificacoes")}}">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">{!! $notificacoes->count() !!}</span>
                    </a>
                </li>

            </ul>
            
        </nav>
    </div>
</div>
