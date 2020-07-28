<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SamoCRM') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
{{--<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />--}}

<!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
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

<div id="app" style="height: 90vh;">
    <main class="py-4 mt-5 h-100">
        <div class="d-flex align-items-center h-100 justify-content-center" >
            <div class="error text-center">
                <p class="text-center"><i class="fas fa-exclamation-triangle fa-6x text-danger"></i></p>
                <p class="h3 text-uppercase font-weight-bold mb-5">
                    Билет не найден!
                </p>
                    <button class="btn btn-warning back"> Вернуться к сканеру</button>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    $('.back').click(function () {
        window.history.back();
    })
</script>
<script>
    function preloader() {
        $('.preloader').fadeOut('slow').delay(1000);
    }
</script>

<script>
    setTimeout(preloader, 500);
</script>
</body>
</html>
