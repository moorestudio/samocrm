<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title></title>
    {{--<link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <style>
        body{
            background: #FFFFFF!important;
            font-family: courier;
        }
        .ticket-style{
            width: 599px;
            height: 339px;
            border: 1px solid #189DDF;
            border-radius: 10px;
            background-color: #fff;
        }
        .ticket-first-b{
            width:10%;
            border-radius: 8px 0 0 8px;
            background-repeat:no-repeat;
        }
        .ticket-second-b{
            width:90%;
            padding: 3%;
        }
        .md-form {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .rotated-text{
            transform: rotate(-90deg);
            color: #fff;
            height: 45px;
        }
        .ticket-owner-name{
            font-weight: 600;
            color: #1E2433;
            font-size: 16px;
        }
        .ticket-style .title{
            font-weight: normal;
            font-size: 12px;
            line-height: 110%;
            color: #C9CED6;
        }
        .ticket-style .description{
            border: 1px solid #1E2433;
            border-radius: 5px;
            height: 120px;
            width: 70%;
        }
        .ticket-style .qr-code{
            width: 30%;
        }
        .ticket-style .qr-code img{
            width: 130px;
            height: 130px;
        }
        .ticket-style #ticket_title{
            font-weight: 600;
            font-size: 20px;
            line-height: 110%;
            color: #1E2433;
        }
        .ticket-style #ticket_date{
            font-weight: 600;
            font-size: 20px;
            line-height: 110%;
            color: #1E2433;
        }
        .ticket-style #ticket_address,.ticket-style #ticket_city,.ticket-style #ticket_tarif,.ticket-style .default{
            font-weight: 600;
            font-size: 20px;
            line-height: 110%;
            color: #1E2433;
            margin-bottom: 0;
        }
        .img-25{
            width: 25px;
            height: 25px;
        }
        .logo-text{
            font-weight: 600;
            font-size: 11px;
            line-height: 115%;
            color: #1E2433;
            margin-left: 9px;
        }
        .additional-description{
            font-weight: 300;
            font-size: 10px;
            line-height: 120%;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-start flex-wrap p-5">
        <div class="col-lg-12 px-0 d-flex justify-content-between">
            <div class="d-flex">
                <img class="img-25" src="{{asset('images/logo1.png')}}" alt="">
                <p class="mb-0 logo-text">Международный центр <br> развития человека</p>
            </div>
            <div class="d-flex flex-column">
                <p class="mb-0 logo-text ml-0">Информация о заказе: </p>
                <p class="mb-0 additional-description">Номер заказа: № {{$ticket->id}}</p>
                <p class="mb-0 additional-description">Дата заказа: {{$ticket->dateFormat('d.m.Y')}}</p>
            </div>
        </div>
        <div class="col-lg-12 md-form px-0">
            {!! isset($ticketDesign) ? $ticketDesign->text_block : '' !!}
        </div>
        <div class="ticket d-flex ticket-style">
            <div class="ticket-first-b d-flex flex-column justify-content-between pb-4 overflow-hidden position-relative">
                <img class="position-absolute  w-100" src="{{ asset('images/left-blue.png') }}" alt="" style="z-index:1;">
                <div class="d-flex justify-content-center h-25 position-relative" style="background: transparent;z-index:2;"><img src="{{asset('images/bg-samo.svg')}}" alt=""></div>
                <div class="d-flex justify-content-center align-items-end h-75 position-relative" style="background: transparent;z-index:2;"><img src="{{asset('images/samo-text.svg')}}" alt=""> </div>      
            </div>
            <div class="ticket-second-b d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between flex-wrap">
                <p class="title">Номер билета: № {{$ticket->id}}</p>
                <p class="title">билет на имя <span class="ticket-owner-name">{{ $user->last_name }} {{ \Illuminate\Support\Str::limit($user->name,1,'.')}}{{ \Illuminate\Support\Str::limit($user->middle_name,1,'.')}}</span></p>
                <p id="ticket_title" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->title_style['font_size'].'px;' : ''}}" class="w-100 mb-0">{{isset($ticketDesign) ? $ticketDesign->title_style['content'] : $event->title }}</p>
            </div>
            <div class="d-flex">
                <div class="description d-flex flex-wrap align-items-center py-4 px-2">
                    <div class="col-5 px-1 h-100">
                        <p class="title mb-1">место проведения</p>
                        <p id="ticket_address" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->address_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->address_style['content'] : $event->address}}</p>
                    </div>
                    <div class="col-3 px-1 h-100">
                        <p class="title mb-1">город</p>
                        <p id="ticket_city" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->city_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->city_style['content'] :$event->city}}</p>
                        @if($event->scheme)
                            @if(isset($ticket->row))
                                <p class="mb-0 default mt-2">{{$ticket->row}} <span class="title">ряд</span></p>
                            @endif
                        @endif
                    </div>
                    <div class="col-4 px-1 h-100">
                        <p class="title mb-1">категория</p>
                        <p id="ticket_tarif" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->city_style['font_size'].'px;' : '' }}">{{isset($ticket) ? $ticket->format() :"standart"}}</p>
                        @if($event->scheme)
                            @if(isset($ticket->column))
                                <p class="mb-0 default mt-2">{{$ticket->column}} <span class="title">место</span></p>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="qr-code d-flex justify-content-center">
                    <img src="{{ asset($qr_code) }}" alt="">
                </div>
            </div>
            <div class="d-flex">
                <p id="ticket_date" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->date_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->date_style['content'] :$event->date}}</p>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>