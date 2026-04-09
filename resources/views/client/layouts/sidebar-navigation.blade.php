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
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 452px;">
                <ul class="list" style="overflow: hidden; width: auto; height: 452px; overflow-y: scroll;">
                    
                    {{-- Dashboard --}}
                    <li class="{{ Request::is('client/Home*') ? 'active' : '' }}">
                        <a href="{{ url('client/Home') }}" class="waves-effect waves-block">
                            <i class="material-icons">home</i>
                            <span style="color: #F79522">Dashboard</span>
                        </a>
                    </li>

                    {{-- Employee --}}
                    <li class="{{ Request::is('client/Employee*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Employee</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ url('client/Employee/employeeList') }}" class="waves-effect waves-block">Employee Profile</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Vehicle --}}
                    <li class="{{ Request::is('client/Vehicle*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Vehicle</span>
                        </a>
                        <ul class="ml-menu" style="{{ Request::is('client/Vehicle*') ? 'display: block' : '' }}">
                            <li class="{{ Request::is('client/Vehicle/vehicleList') ? 'active' : '' }}">
                                <a href="{{ url('client/Vehicle/vehicleList') }}" class="waves-effect waves-block">Vehicle List</a>
                            </li>
                            <li class="{{ Request::is('client/Vehicle/accidentalLog') ? 'active' : '' }}">
                                <a href="{{ url('client/Vehicle/accidentalLog') }}" class="waves-effect waves-block">Accidental Log</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Pool --}}
                    <li class="{{ Request::is('client/VehicleAssign*') || Request::is('client/BookingReq*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Pool</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/VehicleAssign/driverVehicleAssign') }}" class="waves-effect waves-block">Driver Assign</a></li>
                            <li><a href="{{ url('client/VehicleAssign/employeeVehicleAssign') }}" class="waves-effect waves-block">Vehicle Assign</a></li>
                            <li><a href="{{ url('client/BookingReq/bookingReqList') }}" class="waves-effect waves-block">Vehicle Requisition</a></li>
                            <li><a href="{{ url('client/BookingReq/forwardedBookingReqList') }}" class="waves-effect waves-block">Forwarded Requisition Request</a></li>
                            <li><a href="{{ url('client/BookingReq/bookingSchedule') }}" class="waves-effect waves-block">Vehicle Requisition Schedule</a></li>
                            <li><a href="{{ url('client/VehicleAssign/allVehicleLocation') }}" class="waves-effect waves-block">All Vehicle Location</a></li>
                        </ul>
                    </li>

                    {{-- Maintenance --}}
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Vehicle Maintenance</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/GenHomeService/homeServiceList') }}" class="waves-effect waves-block">Home Service</a></li>
                            <li><a href="{{ url('client/Appointment/setAppoinment') }}" class="waves-effect waves-block">Set Workshop Appointment</a></li>
                            <li><a href="{{ url('client/Appointment/appointmentList') }}" class="waves-effect waves-block">Workshop Appointment List</a></li>
                            <li><a href="{{ url('client/GenHomeService/setHomeService') }}" class="waves-effect waves-block">Set Home Service</a></li>
                        </ul>
                    </li>

                    {{-- Quotation --}}
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Quotation</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/Quotation/reqQuotationList') }}" class="waves-effect waves-block">Request For Quotation</a></li>
                        </ul>
                    </li>

                    {{-- Expense --}}
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Expense</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/Expense/expenseList') }}" class="waves-effect waves-block">Expenses With Vehicle</a></li>
                            <li><a href="{{ url('client/Expense/generalExpenseList') }}" class="waves-effect waves-block">Expense Without Vehicle</a></li>
                        </ul>
                    </li>

                    {{-- Report --}}
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Report</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/Report/expenseReportCorp') }}" class="waves-effect waves-block">Corporate Expense Report</a></li>
                            <li><a href="{{ url('client/Report/poolReport') }}" class="waves-effect waves-block">Pool Report</a></li>
                            <li><a href="{{ url('client/Report/inventoryReport') }}" class="waves-effect waves-block">Inventory Report</a></li>
                            <li><a href="{{ url('client/Report/expenseHistory') }}" class="waves-effect waves-block">Expense History</a></li>
                            <li><a href="{{ url('client/Report/expenseDetailsHistory') }}" class="waves-effect waves-block">Expense Details History</a></li>
                        </ul>
                    </li>

                    {{-- Master Data --}}
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">swap_calls</i>
                            <span>Master Data</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('client/MasterData/inventory') }}" class="waves-effect waves-block">Inventory</a></li>
                            <li><a href="{{ url('client/MasterData/expense') }}" class="waves-effect waves-block">Expense</a></li>
                        </ul>
                    </li>

                </ul>
                <div class="slimScrollBar" style="background: rgba(0, 0, 0, 0.5); width: 4px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 265.33px;"></div>
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