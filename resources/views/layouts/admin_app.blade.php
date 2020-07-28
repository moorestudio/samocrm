<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="min-width: 100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Бизнес-обучение и личностное развитие, тренинговый центр САМО. Тел. +996-555-89-13-22') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/countrySelect.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput.css') }}" rel="stylesheet">

    <link rel="manifest" href="{{ asset('js/manifest.json') }}">
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
    </style>
</head>
<body>
<div class="preloader">
    <div class="row h-100 align-items-center justify-content-center">
        <img class="img-fluid logo-animate" src="{{ asset('images/logo2.png') }}" alt="">
    </div>
</div>
@auth
  @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 6 || \Illuminate\Support\Facades\Auth::user()->role_id == 4 || \Illuminate\Support\Facades\Auth::user()->role_id == 5 || \Illuminate\Support\Facades\Auth::user()->role_id == 8)
    @include('_partials.admin_header')
  @endif
@endauth
<div id="app">
    <main class="main-wrapper">
        @yield('content')
    </main>
</div>
@include('_partials.footer')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/selection-js/dist/selection.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/blockUI.js') }}"></script>
<script src="{{ asset('js/DragSelect.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/countrySelect.js') }}"></script>
<script src="{{ asset('js/intlTelInput.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/7igxhp273q1om5tyajc6o1k5glvmodbbfjoq7e4n7x2b0lq8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript" src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="https://api-maps.yandex.ru/2.1/?apikey=1dc5f6a0-7f44-4dcf-8a38-15f7166f37dc&lang=ru_RU" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@stack('scripts')


<script>

navigator.serviceWorker.getRegistrations().then( function(registrations) { for(let registration of registrations) { registration.unregister(); } }); 

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
    var input = document.querySelector("#telephone");
    window.intlTelInput(input,({
        // options here
    }));
</script>
<script>
    $("#country").countrySelect();
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
    tinymce.init({
        selector: '.tiny_area',
        mobile: {
            menubar: true
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.date').bootstrapMaterialDatePicker
        ({
            time: false,
            clearButton: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.date-format').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY HH:mm',
            minDate : new Date()
        });
    });
</script>
<script>

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
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
