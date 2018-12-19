<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('font/iconsmind/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('font/simple-line-icons/css/simple-line-icons.css') }}"/>

    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/vendor/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
    @yield('style')
</head>

<body id="app-container" class=" show-spinner menu-sub-hidden main-hidden sub-hidden">
<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        @auth
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1"/>
                    <rect x="0.48" y="7.5" width="7" height="1"/>
                    <rect x="0.48" y="15.5" width="7" height="1"/>
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1"/>
                    <rect x="1.56" y="7.5" width="16" height="1"/>
                    <rect x="1.56" y="15.5" width="16" height="1"/>
                </svg>
            </a>

            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1"/>
                    <rect x="0.5" y="7.5" width="25" height="1"/>
                    <rect x="0.5" y="15.5" width="25" height="1"/>
                </svg>
            </a>
        @endauth
    </div>


    <a class="navbar-logo" href="/">
        <span class="logo d-none d-xs-block"></span>
        <span class="logo-mobile d-block d-xs-none"></span>
    </a>

    <div class="navbar-right">
        @auth
            <div class="user d-inline-block">
                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <span class="name">{{ Auth::user()->name }}</span>
                    <span>
                        <img alt="Profile Picture" src="/img/profile-pic-l.jpg"/>
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-3">
                    <a class="dropdown-item" href="#">Account</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        @endauth
        @guest
            <div class="header-icons d-inline-block align-middle">
                <a class="btn btn-sm btn-outline-primary mr-2 d-none d-md-inline-block mb-2"
                   href="{{ route('register') }}">&nbsp;REGISTER&nbsp;</a>
                <a class="btn btn-sm btn-outline-primary mr-2 d-none d-md-inline-block mb-2"
                   href="{{ route('login') }}">&nbsp;LOGIN&nbsp;</a>
            </div>
        @endguest
    </div>
</nav>
<div class="sidebar">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class="active">
                    <a href="/events">
                        <i class="iconsmind-Shop-4"></i>
                        <span>Events</span>
                    </a>
                </li>
                <li>
                    <a href="#admin">
                        <i class="iconsmind-Digital-Drawing"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sub-menu">
        <div class="scroll">

            <ul class="list-unstyled" data-link="admin">
                <li>
                    <a href="{{ route('events.create') }}">
                        <i class="simple-icon-credit-card"></i> Create Event
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<main>
    @yield('content')
</main>

<script src="{{ asset('js/vendor/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/vendor/mousetrap.min.js') }}"></script>
<script src="{{ asset('js/dore.script.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@yield('script')
</body>

</html>