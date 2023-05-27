<!-- header -->
<div class="header">
    <div class="menu-toggle-btn"> <!-- Menu close button for mobile devices -->
        <a href="#">
            <i class="bi bi-list"></i>
        </a>
    </div>
    <!-- Logo -->
    <a href="{{route('home')}}" class="logo">
        <img width="100" src="{{asset('images/logos/logo2.png')}}" alt="logo">
    </a>
    <!-- ./ Logo -->
    <div class="page-title">{{$page_name ?? ''}}</div>
{{--    <form class="search-form">--}}
{{--        <div class="input-group">--}}
{{--            <button class="btn btn-outline-light" type="button" id="button-addon1">--}}
{{--                <i class="bi bi-search"></i>--}}
{{--            </button>--}}
{{--            <input type="text" class="form-control" placeholder="Search..."--}}
{{--                   aria-label="Example text with button addon" aria-describedby="button-addon1">--}}
{{--            <a href="#" class="btn btn-outline-light close-header-search-bar">--}}
{{--                <i class="bi bi-x"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </form>--}}
    <div class="header-bar ms-auto">
        <ul class="navbar-nav justify-content-end">
            @if(auth()->user()->can('edit-profile'))
                <li class="nav-item">
                    <a  class="nav-link" href="{{route('profile_edit')}}" title="@lang('commons/top_nav.My Profile')"><i class="bi bi-person"  style="color: dodgerblue; font-size: 20px;"></i> </a>
                </li>
            @endif
            @if(auth()->user()->can('change-password'))
                <li class="nav-item">
                    <a  class="nav-link" href="{{route('change_password')}}" title="@lang('commons/top_nav.Change Password')"><i class="bi bi-key"  style="color: green; font-size: 20px;"></i></a>
                </li>
            @endif
            <li class="nav-item">
                <a title="@lang('commons/top_nav.Log Out')" class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right" style="color: red; font-size: 20px;"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <!-- Header mobile buttons -->
    <div class="header-mobile-buttons">
{{--        <a href="#" class="search-bar-btn">--}}
{{--            <i class="bi bi-search"></i>--}}
{{--        </a>--}}
        <a href="#" class="actions-btn">
            <i class="bi bi-three-dots"></i>
        </a>
    </div>
    <!-- ./ Header mobile buttons -->
</div>
<!-- ./ header -->
