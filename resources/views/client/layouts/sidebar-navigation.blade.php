<section>
    <aside id="leftsidebar" class="sidebar">
        <div class="user-info">
            <div class="image">
                <img src="{{ asset('assets/client/images/vroom_white_logo.png') }}" width="48" height="48" alt="User">
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name ?? 'Wali Illah' }}
                </div>
                <div class="email">
                    {{ Auth::user()->email ?? 'demo@yopmail.com' }}
                </div>

                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="{{ url('client/Home/profile') }}" class="waves-effect waves-block">
                                <i class="material-icons">person</i>Profile
                            </a>
                        </li>
                        <li role="seperator" class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                               class="waves-effect waves-block">
                                <i class="material-icons">input</i>Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="menu">
            <div class="slimScrollDiv" style="position: relative; overflow-y: scroll; width: auto; height: 530px;">
                <ul class="list">
                @php
                    $moduleGroupIdForCollapse = get_module_group($leftMenuModuleUrl);
                @endphp

                <li {{ $moduleGroupIdForCollapse == "" ? "class=active" : "" }}>
                    <a href="{{ url('client/Home') }}">
                        <i class="material-icons">home</i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @php
                    $userGroup = auth()->user()->user_group; 
                    $customer_type = DB::table('customer_employee')->where('employee_id',auth()->user()->user_id)->pluck('customer_type')->first();
                    $moduleList = get_modules($userGroup);
                    $module = explode(",", $moduleList);

                    $rowDistincts = get_distinct_rows($module);

                    $modulegroupList = [];
                    foreach ($rowDistincts as $rowDistinct) {
                        $modulegroupList[] = $rowDistinct->module_group;
                    }
                @endphp

                @for ($i = 0; $i < count($modulegroupList); $i++)
                    @php
                        $moduleGroupId = $modulegroupList[$i];
                        $modulegroupName = get_module_group_name($moduleGroupId);

                        if (auth()->user()->customer_type == 'indv_customer') {
                            if ($moduleGroupId == 'M-GRP-00008') {
                                $modulegroupName = "Assign";
                            }
                        }
                    @endphp

                    <li {{ $moduleGroupId == $moduleGroupIdForCollapse ? "class=active" : "" }}>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">swap_calls</i>
                            <span>{{ $modulegroupName }}</span>
                        </a>

                        <ul class="ml-menu" {{ $moduleGroupId == $moduleGroupIdForCollapse ? "style=display:block" : "" }}>
                            @for ($j = 0; $j < count($module); $j++)
                                @php
                                    $moduleId = $module[$j];
                                    $rowModules = get_row_modules($moduleId, $moduleGroupId);
                                @endphp

                                @foreach ($rowModules as $rowModule)
                                    @php
                                        $moduleName = $rowModule->modules_name;
                                        $moduleUrl = $rowModule->module_url;
                                        $moduleGroup = $rowModule->module_group;
                                    @endphp

                                    @if ($leftMenuModuleUrl == $moduleUrl)
                                        <li class="active">
                                    @else
                                        <li>
                                    @endif

                                    <a href="{{ url($moduleUrl) }}"
                                    class="{{ $moduleGroupId == $moduleGroupIdForCollapse ? 'toggled' : '' }} waves-effect waves-block">
                                        {{ $moduleName }}
                                    </a>

                                    </li>
                                @endforeach
                            @endfor
                        </ul>
                    </li>
                @endfor
            </ul>
            </div>
        </div>    
        <div class="legal">
            <div class="copyright">
                © {{ date('Y') }} <a href="javascript:void(0);">Vroom Services Limited</a>
            </div>
            <div class="version">
                <b>Developed by ArrowLink™ Soft </b>
            </div>
        </div>
        </aside>
    </section>