<?php
$agent = new \Jenssegers\Agent\Agent();
?>
<div class="w-100" style="background: #fff;">
    <div class="container">
        <nav class="navbar navbar-light px-0 {{$agent->isPhone() ? 'fixed-top' : ''}}" style="z-index: 999;">
            <div class="d-flex flex-wrap col-12 px-0">
                <div class="col-lg-7 d-flex flex-wrap col-12 px-0 position-relative">
                    <div class="d-flex justify-content-lg-start justify-content-between col-12 px-0">
                        <a class="navbar-brand py-0 col-lg-2 col-4" href="{{ route('main') }}">
                        <img class="img-fluid logo_header" src="{{ asset('images/logo1.png') }}" alt="">
                        </a>
                        <div class="col-2 d-lg-none d-flex align-items-center justify-content-center drop-bar">
                            <i class="fas fa-bars fa-2x"></i>
                        </div>
                        <div class="title_header pr-5 pl-0 col-8 d-lg-block d-none">
                            <h1 class="font-weight-bold" style="margin-bottom: 0px; color: #5085CD; font-size: 1.5vw; letter-spacing: 0.5px;">
                                Международный центр<br> развития человека
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="d-lg-flex d-none col-4 justify-content-end ml-auto">
                    <a href="https://www.facebook.com/worldsamodavlatov/" style=""><div class="d-flex align-items-center justify-content-center social-facebook social"><i class="fab fa-facebook-f text-white"></i></div></a>
                    <a href="https://vk.com/world.samo" style=""><div class="d-flex align-items-center justify-content-center social-vk social"><i class="fab fa-vk text-white"></i></div></a>
                    <a href="https://www.youtube.com/channel/UCVtFh_62yrW-vj95T0TvYiw" style=""><div class="d-flex align-items-center justify-content-center social-youtube social"><i class="fab fa-youtube text-white"></i></div></a>
                    <a href="https://www.instagram.com/worldsamo/" style=""><div class="d-flex align-items-center justify-content-center social-instagram social"><i class="fab fa-instagram text-white"></i></div></a>
                    <a href="#" style=""><div class="d-flex align-items-center justify-content-center social-telegram social"><i class="fab fa-telegram-plane text-white"></i></div></a>
                </div>

            </div>
        </nav>
    </div>
    <div class="w-100" style="background:#189DDF">
        <div class="container">
            <div class="col-12 pod-menu px-0 {{$agent->isPhone() ? 'fixed-top' : ''}} client-pod-menu" id="pod-menu">
                <div class="container activ_class_find px-0">
                    <div class="d-lg-flex justify-content-start d-block" id="idForActive">
                        @if(!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role_id != 7)
                            <a class="text-white activ_class " href="{{ route('main') }}"><div class="main-menu-item font-weight-bold text-uppercase no_active_find">Главная</div></a>
                            <a class="text-white activ_class " href="{{ route('how_buy') }}"><div class="main-menu-item font-weight-bold text-uppercase how_buy">Как покупать</div></a>
                            <a class="text-white activ_class " href="{{ route('contacts') }}"><div class="main-menu-item font-weight-bold text-uppercase contacts">Контакты</div></a>
                        @else
                            <a class="text-white activ_class" href="{{ route('scanner_list') }}"><div class="main-menu-item font-weight-bold text-uppercase scanner_list">Сканер</div></a>
                        @endif
                        @guest
                                <a class="text-white activ_class mr-3" style="margin-left: auto;" href="{{ route('login') }}">
                                    <div class="main-menu-item font-weight-bold text-uppercase" style="cursor:pointer; background:#2380B9!important;">Вход</div></a>

                        @else
                            <li class="nav-item dropdown droper mr-3" style="margin-left: auto">
                            <div id="navbarDropdown" class="dropdown-toggle main-menu-item font-weight-bold text-uppercase text-white" style="cursor:pointer; background:#2380B9!important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                            </div>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 )
                            <a class="dropdown-item text-dark font-weight-bold activ_class" href="{{ route('admin') }}">
                            {{ __('Админ панель') }}
                            </a>
                            @elseif( Auth::user()->role_id == 4 || Auth::user()->role_id == 6|| Auth::user()->role_id == 5 || Auth::user()->role_id == 8)
                            <a class="dropdown-item text-dark activ_class" href="{{ route('event_list') }}">
                            {{__('Админ панель')}}
                            </a>
                            @endif
                                @if(Auth::user()->role_id != 7)
                            <a class="dropdown-item text-dark activ_class" href="{{ route('user_profile') }}">
                            {{__('Личный кабинет') }}
                            </a>
                                @endif
                            <a class="dropdown-item text-dark activ_class" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Выход') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                            </form>
                            </div>
                            </li>
                            @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
