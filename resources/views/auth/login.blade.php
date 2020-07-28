<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
@extends('layouts.app')

@section('content')
@php
    $orig_url_arr  = explode("/",\URL::previous());
        if(isset($orig_url_arr[3]) && isset($orig_url_arr[4])){
            if(($orig_url_arr[3]=="buy" && $orig_url_arr[4]) || ($orig_url_arr[3]=="info" && $orig_url_arr[4])){
                $event_id = $orig_url_arr[4];
                Cookie::queue('orig_event_id', $event_id, 60);
                Cookie::queue('orig_event_type', $orig_url_arr[3], 60);
            }
        };
@endphp
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 500px;">
        <div class="col-lg-6 col-md-8 col-sm-12" style="padding-top: 9%;padding-top: 5%;">
            <div class="card" style="background-color:white;">
                <div class="reg_title" style="border:none;">{{ __('Авторизация') }}</div>

                <div class="card-body" style="padding: 12%;">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email">{{ __('E-Mail') }}</label>

                            <input id="email" placeholder="Адрес электронной почты" type="email" class="form-control form-control2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if(session('userStatus')=='blocked')
                              <span class="invalid-feedback d-block" role="alert">
                                  <strong>Вы были заблокированы, обратитесь к администратору</strong>
                              </span>
                            @endif
                        </div>

                        <div class="form-group row position-relative">

                            <label for="password" >{{ __('Пароль') }}</label>
                            <input id="pass_log_id" placeholder="Пароль" type="password" class="form-control form-control2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password eyeicon"></span>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

              

                        <div class="form-group row mb-0" style="    padding-top: 5%;">
                            <div class="col-lg-8 col-md-4 col-sm-4 col-xs-12 p-0 mb-2">
                                <button type="submit" class="auth_btn" style="float:left;border-radius: 5px;">
                                    {{ __('Войти') }}
                                </button>
                            </div>
                            <div class="form-check d-flex mb-2 auth_mob_btn" style="    align-items: center;    margin-left: 25px;">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" style="font-size: 12px;" for="remember">
                                    {{ __('Запомнить') }}
                                </label>
                                    
                            </div>
                        </div>
                        <hr style="width:100%;">
                        <div class="form-group row" style="padding-top:5%;">

                  
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-0">
                                <button type="button" class="auth_btn auth_mob_btn btn-reg text-decoration-none" style="">
                                <a style="color:#0055A7!important;" href="{{ route('register') }}">
                                Регистрация в системе
                                </a>
                                </button>
                            </div>
                            <div class="forgot_pass_btn">
                                @if (Route::has('password.request'))
                                  <a class="btn btn-link  auth_mob_btn" href="{{ route('password.request') }}" style="padding: 0;margin:0;font-size: 15px;">
                                       {{ __('Забыли свой пароль?') }}
                                  </a>
                            @endif
                            </div>


                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.toggle-password', function() {

$(this).toggleClass("fa-eye fa-eye-slash");

var input = $("#pass_log_id");
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});
    </script>

@endsection
