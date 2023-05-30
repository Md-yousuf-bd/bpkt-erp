<!-- menu -->
<div class="menu">
    <div class="menu-header">
        <a href="{{route('home')}}" class="menu-header-logo">
            <img style="width: 160px;" src="{{asset('images/logos/logo2.png')}}" alt="{{ config('app.name', 'Laravel') }} Logo">
        </a>
        <a href="{{route('home')}}" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                        @if(\Illuminate\Support\Facades\Auth::user()->detail->picture==null || \Illuminate\Support\Facades\Auth::user()->detail->picture=='')
                            <img src="{{asset('images/defaults/user_profile_picture.png')}}"  style="height:50px; width:auto;" class="rounded-circle"  alt="User Image" >
                        @else
                            <img src="{{asset('storage/images/users/'.\Illuminate\Support\Facades\Auth::user()->detail->picture)}}"   style="height:50px; width:auto;" class="rounded-circle"  alt="User Image">
                        @endif
                </div>
                <div>
                    <div class="fw-bold">{{\Illuminate\Support\Facades\Auth::user()->name ?? ''}}</div>
                    <small class="text-muted">{{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}</small>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{route('profile_edit')}}" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-person dropdown-item-icon"></i> Profile
                </a>
                <a title="@lang('commons/top_nav.Log Out')" class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right dropdown-item-icon"></i> @lang('commons/top_nav.Log Out')
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <ul>
            <li>
                <a href="{{route('home')}}" class="@if($page_name=='Dashboard') active @endif">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <span class="nav-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span>@lang('commons/sidebar_menu.Dashboard')</span>
                </a>
            </li>




    @if(auth()->user()->can('read-coa') ||
             auth()->user()->can('create-coa') ||
             auth()->user()->can('read-unit') ||
             auth()->user()->can('create-unit') ||
             auth()->user()->can('read-owner') ||
             auth()->user()->can('create-owner') ||
             auth()->user()->can('read-user') ||
             auth()->user()->can('register-user') ||
             auth()->user()->can('read-lookup')||
             auth()->user()->can('create-lookup') ||
             auth()->user()->can('read-tax') ||
             auth()->user()->can('create-tax') ||
             auth()->user()->can('read-group-account') ||
             auth()->user()->can('create-group-account') ||
             auth()->user()->can('read-permission') ||
             auth()->user()->can('create-permission') ||
             auth()->user()->can('read-role') ||
             auth()->user()->can('create-role') ||
             auth()->user()->can('create-advertisement') ||
             auth()->user()->can('read-advertisement')
                        )
                <li>
                    <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-file-fill"></i>
        </span>
                        <span>Set-up</span>
                    </a>
                    <ul>


                        @if(auth()->user()->can('read-coa') ||
                              auth()->user()->can('create-coa'))
                            <li>
                                <a href="#">

                                    <span>Chart of Accounts</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-coa'))
                                        <li class="">
                                            <a href="{{route('coa.index')}}" class="@if(in_array($page_name,['Chart of Accounts List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-coa'))
                                        <li class="">
                                            <a href="{{route('coa.create')}}" class="@if(in_array($page_name,['Add Chart of Accounts'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                            @if(auth()->user()->can('read-unit') ||
                        auth()->user()->can('create-unit'))
                                <li>
                                    <a href="#">

                                        <span>Measuring Unit</span>
                                    </a>
                                    <ul>

                                        @if(auth()->user()->can('read-unit'))
                                            <li class="">
                                                <a href="{{route('unit.index')}}" class="@if(in_array($page_name,['Measuring Unit List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-unit'))
                                            <li class="">
                                                <a href="{{route('unit.create')}}" class="@if(in_array($page_name,['Add Measuring Unit'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-owner') ||
                         auth()->user()->can('create-owner'))
                                <li>
                                    <a href="#">
{{--                    <span class="nav-link-icon">--}}
{{--                        <i class="bi bi-file-person"></i>--}}
{{--                    </span>--}}
                                        <span>Owner Info</span>
                                    </a>
                                    <ul>

                                        @if(auth()->user()->can('read-owner'))
                                            <li class="">
                                                <a href="{{route('owner.index')}}" class="@if(in_array($page_name,['Owner Info List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-owner'))
                                            <li class="">
                                                <a href="{{route('owner.create')}}" class="@if(in_array($page_name,['Add Owner Info'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-user')
                        ||auth()->user()->can('register-user'))
                                <li>
                                    <a href="#">
{{--        <span class="nav-link-icon">--}}
{{--            <i class="bi bi-people-fill"></i>--}}
{{--        </span>--}}
                                        <span>Users</span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-user'))
                                            <li class="">
                                                <a href="{{route('settings.user.index')}}" class="@if(in_array($page_name,['User List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('register-user'))
                                            <li class="">
                                                <a href="{{route('register')}}" class="@if(in_array($page_name,['Registration'])) active @endif">
                                                    @lang('commons/sidebar_menu.Register')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-lookup')
                                            ||auth()->user()->can('create-lookup'))
                                <li>
                                    <a href="#">
{{--        <span class="nav-link-icon">--}}
{{--            <i class="bi bi-diagram-3-fill"></i>--}}
{{--        </span>--}}
                                        <span>Lookups</span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-lookup'))
                                            <li class="">
                                                <a href="{{route('settings.lookup.index')}}" class="@if(in_array($page_name,['Lookup List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-lookup'))
                                            <li class="">
                                                <a href="{{route('settings.lookup.create')}}" class="@if(in_array($page_name,['Add Lookup'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-group-account') ||
                                              auth()->user()->can('create-group-account'))
                                <li>
                                    <a href="#">
{{--                    <span class="nav-link-icon">--}}
{{--                        <i class="bi bi-people-fill"></i>--}}
{{--                    </span>--}}
                                        <span>Group Accounts</span>
                                    </a>
                                    <ul>

                                        @if(auth()->user()->can('read-group-account'))
                                            <li class="">
                                                <a href="{{route('group-account.index')}}" class="@if(in_array($page_name,['Group Accounts List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-group-account'))
                                            <li class="">
                                                <a href="{{route('group-account.create')}}" class="@if(in_array($page_name,['Add Group Account'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif



                            @if(auth()->user()->can('read-tax') ||
                                        auth()->user()->can('create-tax'))
                                <li>
                                    <a href="#">

                                        <span>Tax info</span>
                                    </a>
                                    <ul>

                                        @if(auth()->user()->can('read-tax'))
                                            <li class="">
                                                <a href="{{route('tax.index')}}" class="@if(in_array($page_name,['Tax Info List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-tax'))
                                            <li class="">
                                                <a href="{{route('tax.create')}}" class="@if(in_array($page_name,['Add Tax Info'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-role')
                         || auth()->user()->can('create-role'))
                                <li>
                                    <a href="#">
{{--        <span class="nav-link-icon">--}}
{{--            <i class="bi bi-file-earmark-person"></i>--}}
{{--        </span>--}}
                                        <span>Roles</span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-role'))
                                            <li class="">
                                                <a href="{{route('settings.role.index')}}" class="@if(in_array($page_name,['Role List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-role'))
                                            <li class="">
                                                <a href="{{route('settings.role.create')}}" class="@if(in_array($page_name,['Add Role'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if(auth()->user()->can('read-permission')
                                             ||auth()->user()->can('create-permission'))
                                <li>
                                    <a href="#">
{{--        <span class="nav-link-icon">--}}
{{--            <i class="bi bi-file-earmark-lock2"></i>--}}
{{--        </span>--}}
                                        <span>Permissions</span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-permission'))
                                            <li class="">
                                                <a href="{{route('settings.permission.index')}}" class="@if(in_array($page_name,['Permission List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif

                                        @if(auth()->user()->can('create-permission'))
                                            <li class="">
                                                <a href="{{route('settings.permission.create')}}" class="@if(in_array($page_name,['Add Permission'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                    </ul>
                </li>
     @endif


            @if(auth()->user()->can('read-assets') ||
                 auth()->user()->can('create-assets') ||

                              auth()->user()->can('read-customer') ||
                              auth()->user()->can('create-customer') ||
                              auth()->user()->can('read-product') ||
                              auth()->user()->can('create-product') ||
                              auth()->user()->can('read-bulk') ||
                              auth()->user()->can('create-bulk') ||
                              auth()->user()->can('read-billing') ||
                              auth()->user()->can('create-billing') ||
                              auth()->user()->can('read-income') ||
                              auth()->user()->can('create-income') ||
                              auth()->user()->can('read-cash-collection') ||
                              auth()->user()->can('create-cash-collection') ||
                              auth()->user()->can('read-rate') ||
                              auth()->user()->can('create-rate') ||
             auth()->user()->can('read-meter')  ||
             auth()->user()->can('create-meter') ||
             auth()->user()->can('read-advertisement')  ||
             auth()->user()->can('create-advertisement') ||
             auth()->user()->can('read-security-deposit')  ||
             auth()->user()->can('create-security-deposit')
                 )
            <li>
                <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-truck"></i>
        </span>
                    <span>Sales</span>
                </a>
                <ul>
                    @if(auth()->user()->can('read-bulk') ||
                                   auth()->user()->can('create-bulk'))
                        <li>
                            <a href="#">

                                <span>Bulk Entry</span>
                            </a>
                            <ul>
                                @if(auth()->user()->can('read-bulk'))
                                    <li class="">
                                        <a href="{{route('bulk.index')}}" class="@if(in_array($page_name,['Bulk Entry List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-bulk'))
                                    <li class="">
                                        <a href="{{route('bulk.create')}}" class="@if(in_array($page_name,['Bulk Entry'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                @endif


                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->can('read-bulk') ||
                                   auth()->user()->can('create-bulk'))
                        <li>
                            <a href="#">
                                <span>New Entery Temp</span>
                            </a>
                            <ul>
                                @if(auth()->user()->can('read-bulk'))
                                    <li class="">
                                        <a href="{{route('bulk.index')}}" class="@if(in_array($page_name,['Bulk Entry List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-bulk'))
                                    <li class="">
                                        <a href="{{route('bulk.create')}}" class="@if(in_array($page_name,['Bulk Entry'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                @endif


                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->can('read-billing') || auth()->user()->can('create-billing'))
                        <li>
                            <a href="#">

                                <span>Billing</span>
                            </a>
                            <ul>
                                @if(auth()->user()->can('read-billing'))
                                    <li class="">
                                        <a href="{{route('billing.index')}}" class="@if(in_array($page_name,['Billing List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-billing'))
                                    <li class="">
                                        <a href="{{route('billing.createM')}}" class="@if(in_array($page_name,['Add Billing'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                     @if(auth()->user()->can('read-billing') || auth()->user()->can('create-billing'))
                            <li style="display:none">
                                <a href="#">

                                    <span>Billing (Multiple)</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('create-billing'))
                                        <li class="">
                                            <a href="{{route('billing.createM')}}" ">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @if(auth()->user()->can('read-income') ||
                       auth()->user()->can('create-income'))
                        <li>
                            <a href="#">

                                <span>Income</span>
                            </a>
                            <ul>

                                @if(auth()->user()->can('read-income'))
                                    <li class="">
                                        <a href="{{route('income.index')}}" class="@if(in_array($page_name,['Income List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-income'))
                                    <li class="">
                                        <a href="{{route('income.create')}}" class="@if(in_array($page_name,['Add Income'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->can('read-cash-collection') ||
                        auth()->user()->can('create-cash-collection'))
                        <li>
                            <a href="#">

                                <span>Cash Collection</span>
                            </a>
                            <ul>

                                @if(auth()->user()->can('read-cash-collection'))
                                    <li class="">
                                        <a href="{{route('cash-collection.index')}}" class="@if(in_array($page_name,['Cash Collection List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-cash-collection'))
                                    <li class="">
                                        <a href="{{route('cash-collection.create-new')}}" class="@if(in_array($page_name,['Add Cash Collection'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                    <li class="" style="display:none">
                                        <a href="{{route('cash-collection.create-new')}}" class="@if(in_array($page_name,['New Add Cash Collection'])) active @endif">
                                            Add New
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                        @if(auth()->user()->can('read-security-deposit') ||
                                auth()->user()->can('create-security-deposit'))
                            <li>
                                <a href="#">
                                    <span> Security Deposit Collection </span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-security-deposit'))
                                        <li class="">
                                            <a href="{{route('security-deposit.index')}}" class="@if(in_array($page_name,['Security Deposit List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-security-deposit'))
                                        <li class="">
                                            <a href="{{route('security-deposit.create')}}" class="@if(in_array($page_name,['Add Security Deposit'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                    @if(auth()->user()->can('read-assets') ||
                              auth()->user()->can('create-assets')
                              )
                        <li>
                            <a href="#">

                                <span>Asset Info</span>
                            </a>
                            <ul>
                                @if(auth()->user()->can('read-assets'))
                                    <li class="">
                                        <a href="{{route('assets.index')}}" class="@if(in_array($page_name,['Asset Info List'])) active @endif">
                                            @lang('commons/sidebar_menu.List')
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('create-assets'))
                                    <li class="">
                                        <a href="{{route('assets.create')}}" class="@if(in_array($page_name,['Add Asset Info'])) active @endif">
                                            @lang('commons/sidebar_menu.Add')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                        @if(auth()->user()->can('read-meter')  || auth()->user()->can('create-meter'))
                            <li>
                                <a href="#">

                                    <span>Meter Info</span>
                                </a>
                                <ul>
                                    @if(auth()->user()->can('read-meter'))
                                        <li class="">
                                            <a href="{{route('meter.index')}}" class="@if(in_array($page_name,['Meter Info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-meter'))
                                        <li class="">
                                            <a href="{{route('meter.create')}}" class="@if(in_array($page_name,['Add Meter Info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->can('read-advertisement') ||
                                  auth()->user()->can('create-advertisement'))
                            <li>
                                <a href="#">

                                    <span>Advertisement Space</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-advertisement'))
                                        <li class="">
                                            <a href="{{route('advertisement.index')}}" class="@if(in_array($page_name,['Advertisement Space List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-advertisement'))
                                        <li class="">
                                            <a href="{{route('advertisement.create')}}" class="@if(in_array($page_name,['Add Advertisement Space'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->can('read-rate') ||
                                          auth()->user()->can('create-rate'))
                            <li>
                                <a href="#">

                                    <span>Rate Info</span>
                                </a>
                                <ul>
                                    @if(auth()->user()->can('read-rate'))
                                        <li class="">
                                            <a href="{{route('rate.index')}}" class="@if(in_array($page_name,['Rate Info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-rate'))
                                        <li class="">
                                            <a href="{{route('rate.create')}}" class="@if(in_array($page_name,['Add Rate Info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif

                                    <li class="">
                                        <a href="{{route('rate.log-view')}}" class="@if(in_array($page_name,['Log Rate Info'])) active @endif">
                                            Log View
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->can('read-customer') ||
                           auth()->user()->can('create-customer'))
                            <li>
                                <a href="#">

                                    <span>Customer info</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-customer'))
                                        <li class="">
                                            <a href="{{route('customer.index')}}" class="@if(in_array($page_name,['Customer info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-customer'))
                                        <li class="">
                                            <a href="{{route('customer.create')}}" class="@if(in_array($page_name,['Add Customer info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->can('read-product') ||
                                auth()->user()->can('create-product'))
                            <li>
                                <a href="#">

                                    <span>Product info</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-product'))
                                        <li class="">
                                            <a href="{{route('product.index')}}" class="@if(in_array($page_name,['Product info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-product'))
                                        <li class="">
                                            <a href="{{route('product.create')}}" class="@if(in_array($page_name,['Add Product Info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                </ul>
            </li>
            @endif
    @if(auth()->user()->can('read-vendor') ||
                       auth()->user()->can('create-vendor') ||
                       auth()->user()->can('read-payment')  ||
                       auth()->user()->can('create-payment') ||
                       auth()->user()->can('read-product') ||
                       auth()->user()->can('create-product') ||
                       auth()->user()->can('read-godown') ||
                       auth()->user()->can('create-godown') ||
                       auth()->user()->can('read-rate') ||
                       auth()->user()->can('create-rate') ||
                       auth()->user()->can('read-payable') ||
                       auth()->user()->can('create-payable') ||
                       auth()->user()->can('read-stock')  ||
                       auth()->user()->can('create-stock')
                        )
                <li>
                    <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-truck"></i>
        </span>
                        <span>Purchase</span>
                    </a>
                    <ul>
                        @if(auth()->user()->can('read-vendor') ||
                       auth()->user()->can('create-vendor'))
                            <li>
                                <a href="#">

                                    <span>Vendor info</span>
                                </a>
                                <ul>

                                    @if(auth()->user()->can('read-vendor'))
                                        <li class="">
                                            <a href="{{route('vendor.index')}}" class="@if(in_array($page_name,['Vendor info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-vendor'))
                                        <li class="">
                                            <a href="{{route('vendor.create')}}" class="@if(in_array($page_name,['Add Vendor info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                          @if(
                                                                           auth()->user()->can('read-stock')  ||
                                                                           auth()->user()->can('create-stock')
                                                                           )
                                <li>
                                    <a href="#">

                                        <span>Stock Management </span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-stock'))
                                            <li class="">
                                                <a href="{{route('stock.index')}}"
                                                   class="@if(in_array($page_name,['Stock List'])) active @endif">
                                                    @lang('commons/sidebar_menu.Stock List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('read-stock'))
                                            <li class="">
                                                <a href="{{route('stock-invoice.index')}}"
                                                   class="@if(in_array($page_name,['Stock Invoice List'])) active @endif">
                                                    @lang('commons/sidebar_menu.Purchase Invoices')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-stock'))
                                            <li class="">
                                                <a href="{{route('stock.create')}}"
                                                   class="@if(in_array($page_name,['Add Stock'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                 @if(auth()->user()->can('read-payment') || auth()->user()->can('create-payment') )
                            <li>
                                <a href="#">

                                    <span>Payments</span>
                                </a>
                                <ul>
                                    @if(auth()->user()->can('read-payment'))
                                        <li class="">
                                            <a href="{{route('payment.index')}}" class="@if(in_array($page_name,['Payments List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-payment'))
                                        <li class="">
                                            <a href="{{route('payment.create')}}" class="@if(in_array($page_name,['Add Payments'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                            @if(auth()->user()->can('read-payable') || auth()->user()->can('create-payable') )
                                <li>
                                    <a href="#">
                                        <span>Payable</span>
                                    </a>
                                    <ul>
                                        @if(auth()->user()->can('read-payable'))
                                            <li class="">
                                                <a href="{{route('payable.index')}}" class="@if(in_array($page_name,['Payable List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-payable'))
                                            <li class="">
                                                <a href="{{route('payable.create')}}" class="@if(in_array($page_name,['Add Payable'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if(auth()->user()->can('read-godown') ||
                auth()->user()->can('create-godown'))
                                <li>
                                    <a href="#">

                                        <span>Store info</span>
                                    </a>
                                    <ul>

                                        @if(auth()->user()->can('read-godown'))
                                            <li class="">
                                                <a href="{{route('godown.index')}}" class="@if(in_array($page_name,['Store info List'])) active @endif">
                                                    @lang('commons/sidebar_menu.List')
                                                </a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->can('create-godown'))
                                            <li class="">
                                                <a href="{{route('godown.create')}}" class="@if(in_array($page_name,['Add Store info'])) active @endif">
                                                    @lang('commons/sidebar_menu.Add')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                    </ul>
                </li>

    @endif

@if(auth()->user()->can('read-journal') ||
auth()->user()->can('read-manual-journal')  ||
auth()->user()->can('create-manual-journal')
)
    <li>
        <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-truck"></i>
        </span>
            <span>Journal</span>
        </a>
        <ul>
            @if(auth()->user()->can('read-journal'))
                <li class="">
                    <a href="{{route('journal.index')}}" class="@if(in_array($page_name,['Journal List'])) active @endif">
                        Journal List All
                    </a>
                </li>
            @endif

            @if(auth()->user()->can('read-manual-journal')  || auth()->user()->can('create-manual-journal'))
                    <li>
                        <a href="#">

                            <span>Make Journal</span>
                        </a>
                        <ul>
                            @if(auth()->user()->can('read-manual-journal'))
                                <li class="">
                                    <a href="{{route('manual-journal.index')}}" class="@if(in_array($page_name,['Manual Journal List'])) active @endif">
                                        @lang('commons/sidebar_menu.List')
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->can('create-manual-journal'))
                                <li class="">
                                    <a href="{{route('manual-journal.create')}}" class="@if(in_array($page_name,['Add Manual Journal'])) active @endif">
                                        @lang('commons/sidebar_menu.Add')
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

        </ul>
    </li>
@endif

@if(auth()->user()->can('read-employee')  || auth()->user()->can('create-employee'))
                <li>
                    <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-truck"></i>
        </span>
                        <span>HR</span>
                    </a>
                    <ul>
                        @if(auth()->user()->can('read-employee')  || auth()->user()->can('create-employee'))
                            <li>
                                <a href="#">

                                    <span>Employee Info</span>
                                </a>
                                <ul>
                                    @if(auth()->user()->can('read-employee'))
                                        <li class="">
                                            <a href="{{route('employee.index')}}" class="@if(in_array($page_name,['Employee Info List'])) active @endif">
                                                @lang('commons/sidebar_menu.List')
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can('create-employee'))
                                        <li class="">
                                            <a href="{{route('employee.create')}}" class="@if(in_array($page_name,['Add Employee info'])) active @endif">
                                                @lang('commons/sidebar_menu.Add')
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
    @endif





@if(auth()->user()->can('read-gl') || auth()->user()->can('read-tb') ||
auth()->user()->can('read-bs') || auth()->user()->can('read-rs') || auth()->user()->can('read-el') ||
            auth()->user()->can('read-meter-el') ||  auth()->user()->can('read-asset-report') ||
              auth()->user()->can('read-csr') ||  auth()->user()->can('read-bwcr')||  auth()->user()->can('read-security-deposit-report') ||  auth()->user()->can('read-rate-history')||
              auth()->user()->can('read-receipt-payment') ||  auth()->user()->can('read-due-statement-customer') || auth()->user()->can('read-due-statement-shop')

            )
    <li>
        <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-truck"></i>
        </span>
            <span>Report</span>
        </a>
        <ul>
            @if(auth()->user()->can('read-gl'))
                <li class="">
                    <a href="{{route('report.gl')}}" class="@if(in_array($page_name,['General Ledger'])) active @endif">
                        @lang('commons/sidebar_menu.gl')
                    </a>
                </li>
            @endif
                @if(auth()->user()->can('read-tb'))
                    <li class="">
                        <a href="{{route('report.tb')}}" class="@if(in_array($page_name,['Trial Balance'])) active @endif">
                            @lang('commons/sidebar_menu.tb')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-bs'))
                    <li class="">
                        <a href="{{route('report.bs')}}" class="@if(in_array($page_name,['Balance Sheet'])) active @endif">
                            @lang('commons/sidebar_menu.bs')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-rs'))
                    <li class="">
                        <a href="{{route('report.rs')}}" class="@if(in_array($page_name,['Receivable Statement'])) active @endif">
                            @lang('commons/sidebar_menu.rs')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-is'))
                    <li class="">
                        <a href="{{route('report.is')}}" class="@if(in_array($page_name,['Income Statement'])) active @endif">
                            @lang('commons/sidebar_menu.is')
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-el'))
                    <li class="">
                        <a href="{{route('report.el')}}" class="@if(in_array($page_name,['Meter Reading'])) active @endif">
                            @lang('commons/sidebar_menu.meter')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-meter-el'))
                    <li class="">
                        <a href="{{route('report.els')}}" class="@if(in_array($page_name,['Electricity Billing Statement'])) active @endif">
                            @lang('commons/sidebar_menu.Meter Statement')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-aar'))
                    <li class="">
                        <a href="{{route('report.aar')}}" class="@if(in_array($page_name,['Asset Allotment Report'])) active @endif">
                            @lang('commons/sidebar_menu.Asset Allotment Report')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-dr'))
                    <li class="">
                        <a href="{{route('report.dr')}}" class="@if(in_array($page_name,['Dues Statement'])) active @endif">
                            @lang('commons/sidebar_menu.Dues Statement')
                        </a>
                    </li>
                @endif
                  @if(auth()->user()->can('read-due-statement-shop'))
                                <li class="">
                                    <a href="{{route('report.due-statement-shop')}}" class="@if(in_array($page_name,['Dues Statement Shop Wise'])) active @endif">
                                        Dues Statement Shop Wise
                                    </a>
                                </li>
                            @endif
                  @if(auth()->user()->can('read-due-statement-customer'))
                                <li class="">
                                    <a href="{{route('report.due-statement-customer')}}" class="@if(in_array($page_name,['Dues Statement Customer Wise'])) active @endif">
                                        Dues Statement Customer Wise
                                    </a>
                                </li>
                            @endif
                @if(auth()->user()->can('read-cs'))
                    <li class="">
                        <a href="{{route('report.cs')}}" class="@if(in_array($page_name,['Collection Statement'])) active @endif">
                            @lang('commons/sidebar_menu.Collection Statement')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-rcs'))
                    <li class="">
                        <a href="{{route('report.rcs')}}" class="@if(in_array($page_name,['Receivable & Collection Summary Report'])) active @endif">
                            @lang('commons/sidebar_menu.Receivable & Collection Summary Report')
                        </a>
                    </li>
                @endif
{{--                @if(auth()->user()->can('read-cs'))--}}
{{--                    <li class="">--}}
{{--                        <a href="{{route('report.cs')}}" class="@if(in_array($page_name,['Collection Statement'])) active @endif">--}}
{{--                            @lang('commons/sidebar_menu.Collection Statement')--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endif--}}
                @if(auth()->user()->can('read-asset-report'))
                    <li class="">
                        <a href="{{route('report.asset-report')}}" class="@if(in_array($page_name,['Asset List Report'])) active @endif">
                            @lang('commons/sidebar_menu.Asset List Report')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-csr'))
                    <li class="">
                        <a href="{{route('report.csr')}}" class="@if(in_array($page_name,['Daily Collection Report'])) active @endif">
                            @lang('commons/sidebar_menu.Daily Collection Report')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-bwcr'))
                    <li class="">
                        <a href="{{route('report.bwcr')}}" class="@if(in_array($page_name,['Bill wise Customer Report'])) active @endif">
                            @lang('commons/sidebar_menu.Bill wise Customer Report')
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-security-deposit-report'))
                    <li class="">
                        <a href="{{route('report.security-deposit')}}" class="@if(in_array($page_name,['Advance & Security Deposit Report'])) active @endif">
                            Security/ Advance Deposit Statement
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-receipt-payment'))
                    <li class="">
                        <a href="{{route('report.receipt-payment')}}" class="@if(in_array($page_name,['Receipt & Payment Statement'])) active @endif">
                            Receipt & Payment Statement
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('read-rate-history'))
                    <li class="">
                        <a href="{{route('report.rate-history')}}" class="@if(in_array($page_name,['Rate History'])) active @endif">
                            Rate & Allotment History
                        </a>
                    </li>
                @endif
        </ul>
    </li>
@endif

@if(auth()->user()->can('read-user')
  ||auth()->user()->can('read-role')
  ||auth()->user()->can('read-permission')
  ||auth()->user()->can('read-lookup')
  ||auth()->user()->can('read-log')
  ||auth()->user()->can('read-all-user-log')
  )
    <li class="menu-divider">Settings</li>


    @if(auth()->user()->can('backup'))
        <li>
            <a href="#">
        <span class="nav-link-icon">
            <i class="bi bi-cloud-download-fill"></i>
        </span>
                <span>@lang('commons/sidebar_menu.Backup')</span>
            </a>
            <ul>
                <li class="">
                    <a href="{{route('settings.backup.all')}}" class="">
                        <p>@lang('commons/sidebar_menu.All')</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{route('settings.backup.db')}}" class="">
                        <p>@lang('commons/sidebar_menu.Only Database')</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{route('settings.backup.files')}}" class="">
                        <p>@lang('commons/sidebar_menu.Only Files')</p>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if(auth()->user()->can('read-log')||auth()->user()->can('read-all-user-log'))
        <li class="">
            <a href="{{route('settings.log.index')}}" class="@if(in_array($page_name,['Logs'])) active @endif">
                 <span class="nav-link-icon">
                    <i class="bi bi-list-check"></i>
                 </span>
                @lang('commons/sidebar_menu.Logs')
            </a>
        </li>
    @endif
@endif
 @if(auth()->user()->can('show-bill-wrong-data-fill'))
                <li class="">
                    <a href="{{route('billing.show-bill-wrong-data-fill')}}" >
                 <span class="nav-link-icon">
                    <i class="bi bi-list-check"></i>
                 </span>
                     Show Data Mismatch
                    </a>
                </li>
            @endif
             @if(auth()->user()->can('delete-fine-amount'))
                <li class="">
                    <a href="{{route('billing.remove-fine-amount')}}" >
                 <span class="nav-link-icon">
                    <i class="bi bi-list-check"></i>
                 </span>
                        Remove Fine Amount From Billing
                    </a>
                </li>
            @endif
            <li class="">
                <a href="{{route('auto-rent')}}" class="@if(in_array($page_name,['Apply auto increment'])) active @endif">
                 <span class="nav-link-icon">
                    <i class="bi bi-list-check"></i>
                 </span>
                    Apply auto increment
                </a>
            </li>
            <li class="">
                <a href="{{route('make-journal')}}" class="@if(in_array($page_name,['Apply auto Fine & Interest'])) active @endif">
                 <span class="nav-link-icon">
                    <i class="bi bi-list-check"></i>
                 </span>
                    Apply auto Fine & Interest
                </a>
            </li>
</ul>
</div>
</div>
<!-- ./  menu -->



