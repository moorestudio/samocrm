<?php
$agent = new \Jenssegers\Agent\Agent();
?>
<div class="w-100" style="background: #fff;">
    <div class="container">
        <nav class="navbar navbar-light px-0 {{$agent->isPhone() ? 'fixed-top' : ''}}" style="z-index: 999;">
            <div class="d-flex flex-wrap col-12 px-0">
                <div class="col-lg-7 d-flex flex-wrap col-12 px-0 position-relative">
                    <div class="d-flex justify-content-lg-start justify-content-between col-12 px-0">
                        <a class="navbar-brand py-0 col-lg-2 col-4" href="/">
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
            <div class="col-12 pod-menu px-0 {{$agent->isPhone() ? 'fixed-top' : ''}} admin-pod-menu" id="pod-menu">
                <div class="container activ_class_find px-0">
                <div class="d-lg-flex justify-content-start d-block">
                    @if(Auth::user()->role_id == 3)
                        <a class="text-white activ_class" href="{{route('attendance_events')}}"><div class="main-menu-item font-weight-bold text-uppercase attendance_events">Посещение</div></a>
                        <li class="nav-item dropdown droper hover-dropdown">
                            <div id="navbarDropdown" class="dropdown-toggle main-menu-item font-weight-bold text-uppercase text-white" style="cursor:pointer; background:#189ddf!important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Мероприятии <span class="caret"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-dark " href="{{route('admin')}}">
                                Все Мероприятия
                                </a>
                                @foreach(\App\Category::all() as $category)
                                <a class="dropdown-item text-dark" href="{{route('admin',$category->id)}}">
                                    {{$category->name}}
                                </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6 || Auth::user()->role_id == 8)
                        <li class="nav-item dropdown droper hover-dropdown">
                            <div id="navbarDropdown" class="dropdown-toggle main-menu-item font-weight-bold text-uppercase text-white" style="cursor:pointer; background:#189ddf!important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Мероприятии <span class="caret"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-dark " href="{{route('event_list')}}">
                                Все Мероприятия
                                </a>
                                @foreach(\App\Category::all() as $category)
                                <a class="dropdown-item text-dark" href="{{route('event_list',$category->id)}}">
                                    {{$category->name}}
                                </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if(Auth::user()->role_id == 4 || Auth::user()->role_id == 6)
                        <a class="text-white activ_class" href="{{ route('client_list') }}"><div class="main-menu-item font-weight-bold text-uppercase clients_list franchiseclients">Продажи</div></a>
                    @else
                    @endif
                    @if(Auth::user()->role_id==4 || Auth::user()->role_id == 6)
                        <a class="text-white activ_class" href="{{ route('partners_list')}}"><div class="main-menu-item font-weight-bold text-uppercase partners_list franchisepartners">Ваши Партнеры</div></a>
                        <a class="text-white activ_class" href="{{ route('profile',['id'=>Auth::user()->id])}}"> <div class="main-menu-item font-weight-bold text-uppercase franchiseprofile">Рефералы</div></a>
                    @endif
                    @if(Auth::user()->role_id==5 || Auth::user()->role_id == 8)
                        <a class="text-white activ_class" href="{{ route('profile',['id'=>Auth::user()->id])}}"><div class="main-menu-item font-weight-bold text-uppercase franchiseprofile">Рефералы</div></a>
                    @endif

                    @if(Auth::user()->role_id==3)
                        <a class="text-white activ_class" href="{{ route('admin_clients_list') }}"><div class="main-menu-item font-weight-bold text-uppercase clients_list franchiseclients">Клиенты</div></a>
                        <a class="text-white activ_class" href="{{ route('user_list') }}"> <div class="main-menu-item font-weight-bold text-uppercase user_list">Пользователи</div></a>
                        <a class="text-white activ_class" href="{{ route('archive') }}"> <div class="main-menu-item font-weight-bold text-uppercase archive">Архив</div></a>
                    @endif
                    @if(Auth::user()->role_id == 6 || Auth::user()->role_id == 3)
                        <li class="nav-item dropdown droper hover-dropdown">
                            <div id="navbarDropdown" class="activ_class dropdown-toggle main-menu-item font-weight-bold text-uppercase text-white" style="cursor:pointer; background:#189ddf!important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Отчеты <span class="caret"></span>
                            </div>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-dark" href="{{ route('eventAdmin/report/all') }}">
                                    Общий отчет
                                </a>
                                <a class="dropdown-item text-dark" href="{{ route('eventAdmin/report') }}">
                                    По мероприятиям
                                </a>
                            </div>
                        </li>
                    <a class="text-white activ_class" href="{{ route('buy_for_client_events') }}"> <div class="main-menu-item font-weight-bold text-uppercase adminbuy">Выписка</div></a>


                        @endif
                        @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 8)
                            <a class="text-white activ_class" href="{{ route('client_list') }}"><div class="main-menu-item font-weight-bold text-uppercase clients_list franchiseclients">Продажи</div></a>
                        @endif
                        @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6)
                            <a class="text-white activ_class" href="{{ route('promo_list') }}"><div class="main-menu-item font-weight-bold text-uppercase promo_list">Промокоды</div></a>
                        @endif
                        @if(Auth::user()->role_id == 3)
                            <a class="text-white activ_class" href="{{ route('scanner_list') }}"><div class="main-menu-item font-weight-bold text-uppercase scanner_list">Сканер</div></a>
                        @endif
    
                        @guest
                            <a class="text-white activ_class" href="{{ route('login') }}"> <div class="main-menu-item font-weight-bold text-uppercase">Вход</div></a>

                        @else
                            <li class="nav-item dropdown droper mr-3" style="margin-left: auto">
                                <div id="navbarDropdown" class="dropdown-toggle main-menu-item font-weight-bold text-uppercase text-white" style="cursor:pointer; background:#2380B9!important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </div>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 )
                                        <a class="dropdown-item text-dark" href="{{ route('admin') }}">
                                            {{ __('Админ панель') }}
                                        </a>
                                    @elseif( Auth::user()->role_id == 4 || Auth::user()->role_id == 6|| Auth::user()->role_id == 5 || Auth::user()->role_id == 8)
                                        <a class="dropdown-item text-dark" href="{{ route('event_list') }}">
                                            {{__('Админ панель')}}
                                        </a>
                                    @endif
                                    <a class="dropdown-item text-dark" href="{{ route('user_profile') }}">
                                        {{__('Личный кабинет') }}
                                    </a>
                                    <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
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
