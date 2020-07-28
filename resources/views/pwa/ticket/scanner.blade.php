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
<div style="position: relative;">
    <p class="p-2 m-2" style="background-color: white;border-radius: 5px;width:fit-content">Выбор камеры</p>
</div>
<div id="camera_count" style="position: relative;">

</div>
<div id="app" style="height: 60vh;">
    <main class="py-4 mt-5 h-100">
        <input type="hidden" id="event_id" value="{{$id}}">
        <div class="d-flex align-items-center h-100" >
        <video id="preview"  playsinline class="w-100"></video>
        </div>
    </main>
</div>
<div class="container text-center" style="top: 50%;position: absolute;left: 50%;transform: translate(-50%, -50%);">
    <p class="main-title text-danger re-find" style="font-size: 26px; display: none;">
        Билет уже был использован.
    </p>
    <p class="main-title text-danger not-find" style="font-size: 26px; display: none;">
        Билет не найден
    </p>
    <div class="find text-center" style="display: none;">
        <p class="main-title text-success mb-2" style="font-size: 26px;">
            Билет найден
        </p>
        <p class="main-title text-success mb-4 user" style="font-size: 24px;">

        </p>
    <button class="select-button" id="activate" data-parent="">
        Активировать билет
    </button>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror:false,facingMode:'environment' });
    scanner.addListener('scan', function (content) {
        if(content.indexOf("ticket") >= 0)
        {
            var event = $('#event_id').val();
                $.ajax({
                    url: '{{ route('scanner_check') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "qr": content,
                        "event_id": event,
                    },
                    success: data => {
                        if(data.check == 1)
                        {
                            $('.find').show(200);
                            $('.not-find').hide(200);
                            $('.re-find').hide(200);
                            $('#activate').attr('data-parent',content);
                            $('.user').html(data.user);
                        }
                        else if(data.check == 0)
                        {
                            $('.find').hide(200);
                            $('.not-find').show(200);
                            $('.re-find').hide(200);
                        }
                        else if(data.check == 2)
                        {
                            $('.find').hide(200);
                            $('.not-find').hide(200);
                            $('.re-find').show(200);
                        }
                    },
                    error: () => {
                            // window.location.href = '/scanner_error';
                    }
                });
        }
    });
    Instascan.Camera.getCameras().then(function (cameras) {
        console.log(cameras.length);
        if (cameras.length > 0) {
            let buttons_ ='';
            let cam_len = cameras.length;
            while (cam_len > 0) {
              let button_ ='<button class="btn camera_btn" style="background-color:azure;z-index:13;position:relative;" cam_num="'+(cam_len-1)+'">'+cam_len+'</button>';
              buttons_+=button_;
              cam_len--;
            }    
            $('#camera_count').html(buttons_);
            scanner.start(cameras[0]);
        } else {
            
            console.error('No cameras found.');
        }
    }).catch(function (e) {
        $('#camera_count').html('No cameras found.');
    });

    $(document).on('click',".camera_btn",function() {
        console.log('innn');
        let cam_num_ = $(this).attr("cam_num");
        console.log(cam_num_);
        Instascan.Camera.getCameras().then(function (cameras) {
            scanner.start(cameras[cam_num_]);
        }); 
    });



</script>
<script>
    $('#activate').click(function (e) {
        var btn = $(e.currentTarget);
        var content = btn.data('parent');
        var event = $('#event_id').val();
        $.ajax({
            url: '{{ route('scanner_find') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "qr": content,
                "event_id": event,
            },
            success: data => {
                if(data.check == 1)
                {
                    window.location.href = '/scanner_success';
                }
                else if(data.check == 0)
                {
                    window.location.href = '/scanner_done';
                }
            },
            error: () => {
                window.location.href = '/scanner_error';
            }
        });
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
