<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('share_meta')

    <title>{{ config('app.name', 'Бизнес-обучение и личностное развитие, тренинговый центр САМО. Тел. +996-555-89-13-22') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    {{--<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />--}}
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/countrySelect.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ asset('js/manifest.json') }}">
    @include('_partials.pwa_ios')
    @stack('styles')
    <style>
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            /*background-color:grey;*/
            /*background-image: url('https://to-moore.com/images/beeline.png');*/
            background-repeat: no-repeat;
            background-color: white;
            background-position: center;
        }
        @media screen and (min-width: 300px) and (max-width: 700px) {
            .preloader
            {
                background-size:80%;
            }
        }

        body
        {

        }
    </style>
</head>
<body>
<div class="preloader">
    <div class="row h-100 align-items-center justify-content-center">
        <img class="img-fluid logo-animate" src="{{ asset('images/logo2.png') }}" alt="">
    </div>
</div>
@guest
    @include('_partials.header')
@endguest

@auth
    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 6 || \Illuminate\Support\Facades\Auth::user()->role_id == 5 || \Illuminate\Support\Facades\Auth::user()->role_id == 4)
        @include('_partials.admin_header')
    @else
         @include('_partials.header')
    @endif
@endauth


<?php
$agent = new \Jenssegers\Agent\Agent();
?>
    <div id="app">
        <main class="pb-4">
            @yield('content')
        </main>
    </div>
@include('_partials.footer')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/countrySelect.js') }}"></script>
<script src="{{ asset('js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="https://api-maps.yandex.ru/2.1/?apikey=1dc5f6a0-7f44-4dcf-8a38-15f7166f37dc&lang=ru_RU" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.tiny.cloud/1/7igxhp273q1om5tyajc6o1k5glvmodbbfjoq7e4n7x2b0lq8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    var input = document.querySelector("#telephone");
    window.intlTelInput(input,({
        // options here
    }));
</script>
<script>
    $(document).ready(function(){
        let name = window.location.pathname.replace(/[0-9 / .]/g, '');
        if(name){
            $('.activ_class').find('.'+name).addClass('active');

        }else{
            $('.activ_class').find('.no_active_find').addClass('active');
        }
    });

</script>
<script>
    $("#country").countrySelect();
</script>
@stack('scripts')
<script>

    // if ('serviceWorker' in navigator) {
        // window.addEventListener('load', function() {
            // navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            // }, function(err) {
            // }).catch(function(err) {
            // });
        // });
    // } else {
    // }

</script>
<script>
    function preloader() {
        $('.preloader').fadeOut('slow').delay(1000);
    }
</script>

<script>
    setTimeout(preloader, 1);
</script>
<script>

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on('change','.max-100-value',function(){
      let val = $(this).val();
      if(val > 100){
        $(this).val(100);
      }
      if(val < 0){
        $(this).val(0);
      }
    });
</script>
<script>
    $('.drop-bar').click(function (e) {
        var btn = $(e.currentTarget);

        if(btn.hasClass('active'))
        {
            btn.removeClass('active');
            document.getElementById('pod-menu').style.top = '-200px';
            document.getElementById('pod-menu').style.opacity = '0';
            document.getElementById('pod-menu').style.height = '0';
            $('#pod-menu').css('z-index','-1');
        }
        else
        {
            btn.addClass('active');
            document.getElementById('pod-menu').style.top = '75px';
            document.getElementById('pod-menu').style.opacity = '1';
            document.getElementById('pod-menu').style.height = 'auto';
            $('#pod-menu').css('z-index','9');
        }
    })
</script>


</body>
</html>
