@extends('layouts.app')
@section('share_meta')
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url('info').'/'.$event->id }}">
<meta name="twitter:title" content="{{ $event->title }}">
<meta name="twitter:description" content="{{ $event->mentor }}">
<meta name="twitter:image" content="{{ asset('storage/'.$event->image) }}">
{{-- <meta name="twitter:image" content="https://i.kym-cdn.com/entries/icons/original/000/028/861/cover3.jpg"> --}}

<meta property='og:locale' content='ru_RU'/>
<!-- <meta property='og:site_name' content=''> -->
<!-- <meta property='og:type' content='article'> -->
<meta property='og:url' content="{{ url('info').'/'.$event->id }}">
<meta property='og:title' content='{{ $event->title }}'>
<meta property='og:description' content="{{ $event->mentor }}">

<meta property='og:image' content='https://i.kym-cdn.com/entries/icons/original/000/028/861/cover3.jpg'>
<!-- <meta property='og:image' content="{{asset('storage/'.$event->image)}}"> -->
@endsection
@section('content')
<?php
$agent = new \Jenssegers\Agent\Agent();
?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 pl-0">
                <a href="{{ route('buy', ['id' => $event->id]) }}">
                    <img src="{{ asset('images/arrow_back.svg') }}" alt=""><span class="annotation-back ml-1">Назад</span>
                </a>
            </div>
            <div class="col-12 pl-0 text-center py-4 d-flex align-items-center">
                <img src="{{asset('images/report/open-menu1.svg')}}" alt="">
                <p class="main-title ml-2 mb-0">{{$type == 'buy'? 'Оформление заказа' : 'Оформление брони'}}</p>
            </div>
        </div>
        <div class="row white-bg-round">
            <div class="col-lg-6 col-12">
                <div class="row">
                <div class="col-12 mt-0 px-5 pt-5 pb-3">
                    <p class="second-title ">Личные данные</p>
                    <div class="d-flex flex-wrap mt-0" style="background: #F5F6FA; border-radius:5px;">
                        <div class="col-lg-12 py-4">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-4">
                                    <label for="lastname" style="color:#A6A6A6;">Фамилия</label>
                                    <p class="mb-0">{{$user->last_name}}</p>
                                </div>
                                <div class="col-lg-6 col-12 mb-4">
                                    <label for="name" style="color:#A6A6A6;">Имя</label>
                                    <p class="mb-0">{{$user->name}}</p>
                                </div>
                                <div class="col-lg-6 col-12 mb-lg-0 mb-4">
                                    <label for="phone" style="color:#A6A6A6;">Телефон</label>
                                    <p class="mb-0">{{$user->contacts}}</p>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="email" style="color:#A6A6A6;">Email</label>
                                    <p class="mb-0">{{$user->email}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 px-5 pb-5 d-flex flex-wrap">
                    <div class="col-lg-12 px-0 col-12 pb-3">
                        <div class="p-3 d-flex justify-content-between" style="background: #DDF4FF;border-radius: 5px;">
                            <span class="secondary-title" style="color: #189DDF;">Итого к оплате:</span><span class="secondary-title" style="color: #189DDF;">{{$with}} {{$event_currency}}</span>
                        </div>
                    </div>
                    @if($type == 'buy')
                        <div class="col-lg-3 col-6 pl-0">
                            <div class="p-2">
                                <div class="custom-checkbox custom-control d-flex">
                                    <input type="checkbox" class="custom-control-input pay_type paybox" name="paybox" id="paybox">
                                    <label class="custom-control-label" for="paybox"></label>
                                    <p>Paybox</p>
                                </div>
                                <img class="img-fluid" src="{{ asset('images/pay_box.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-3 col-6 px-0">
                            <div class="p-2">
                                <div class="custom-checkbox custom-control d-flex">
                                    <input type="checkbox" class="custom-control-input pay_type yandex" name="yandex" id="yandex">
                                    <label class="custom-control-label" for="yandex"></label>
                                    <p>Яндекс касса</p>
                                </div>
                                <img class="img-fluid" src="{{ asset('images/yandex.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 px-0">
                            <div class="p-2 d-flex flex-column h-100">
                                <img class="img-fluid" src="{{ asset('images/cash.svg') }}" alt="">
                                <div class="text-center" style="color: #EF6E6E;">
                                    Для оплаты наличными обратитесь к администратору  или вашему менеджеру
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!$agent->isPhone())
                        <form action="{{'reserve_ticket_for_payment'}}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                            <input type="hidden" name="row" id="row" value="{{$row}}">
                            <input type="hidden" name="column" id="column" value="{{$column}}">
                            <input type="hidden" name="price" id="price" value="{{$price}}">
                            <input type="hidden" name="found" id="found" value="{{$found}}">
                            <input type="hidden" name="show" id="show" value="{{$show}}">
                            <input type="hidden" name="ticket" id="ticket" value="{{$ticket_id}}">
                            <input type="hidden" name="promotion" id="promotion" value="{{$promotion}}">
                            <input type="hidden" name="promo" id="promo" value="{{$promo}}">
                            <input type="hidden" name="pay_type" id="pay_type">
                            <input type="hidden" name="format_id" id="format_id" value="{{$format_id}}">
                        @if($type == 'buy')
                            <div class="col-lg-10 col-12 mt-5 px-0">
                                <button type="submit" class="btn btn-success buy agree_block ml-0">
                                    Оформить покупку
                                </button>
                            </div>
                        @endif
                        </form>
                        @if($type != 'buy')
                        <div class="col-12 mt-5 px-0">
                            <button class="btn btn-success reserved  ml-0">
                                Забронировать
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
              </div>
            <div class="col-lg-6 col-12">
                <div class="row mt-4">
                    <div class="col-12 pt-4  d-lg-block d-none">
                        <p class="second-title">Проверьте данные</p>
                    </div>
                    <div class="col-12 d-lg-block d-none">
                        <div class="ticket d-flex ticket-style">
                                <div class="ticket-first-b d-flex flex-column justify-content-between pb-4 position-relative">
                                    <img class="position-absolute  w-100" src="{{ asset('images/left-blue.png') }}" alt="" style="z-index:1;">
                                    <div class="d-flex justify-content-center h-25 position-relative" style="background: transparent;z-index:2;"><img src="images/bg-samo.svg" alt=""></div>
                                    <div class="d-flex justify-content-center align-items-end h-75 position-relative" style="background: transparent;z-index:2;"><img src="{{asset('images/samo-text.svg')}}" alt=""> </div>      
                                </div>
                                <div class="ticket-second-b d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between flex-wrap">
                                    <p class="title">Номер билета:</p>
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
                                            @if(isset($row))
                                                <p class="mb-0 default mt-2">{{$row}} <span class="title">ряд</span></p>
                                            @endif
                                        </div>
                                        <div class="col-4 px-1 h-100">
                                            <p class="title mb-1">категория</p>
                                            <p id="ticket_tarif" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->city_style['font_size'].'px;' : '' }}">{{$event->rate[$format_id][0]}}</p>
                                            @if(isset($column))
                                                <p class="mb-0 default mt-2">{{$column}} <span class="title">место</span></p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="qr-code d-flex justify-content-center">
                                        <img src="{{ asset('images/test-qr.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <p id="ticket_date" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->date_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->date_style['content'] :$event->date}}</p>
                                </div>
                                </div>
                            </div>
                    </div>
            </div>

                @if($agent->isPhone())
                      <form action="{{route('reserve_ticket_for_payment')}}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                        <input type="hidden" name="row" id="row" value="{{$row}}">
                        <input type="hidden" name="column" id="column" value="{{$column}}">
                        <input type="hidden" name="price" id="price" value="{{$price}}">
                        <input type="hidden" name="found" id="found" value="{{$found}}">
                        <input type="hidden" name="show" id="show" value="{{$show}}">
                        <input type="hidden" name="ticket" id="ticket" value="{{$ticket_id}}">
                        <input type="hidden" name="promotion" id="promotion" value="{{$promotion}}">
                        <input type="hidden" name="promo" id="promo" value="{{$promo}}">
                        <input type="hidden" name="pay_type" id="pay_type">
                        <input type="hidden" name="format_id" id="format_id" value="{{$format_id}}">
                      @if($type == 'buy')
                        <div class="col-lg-10 col-12 d-lg-block d-flex justify-content-center">
                            <button type="submit" class="btn btn-success buy agree_block">
                                Оформить покупку
                            </button>
                        </div>
                      @else
                        <div class="col-lg-10 col-12 d-lg-block d-flex justify-content-center">
                            <button class="btn btn-success reserved">
                                Оформить бронирование
                            </button>
                        </div>
                      @endif
                    </form>
                @endif
        </div>
    </div>
    </div>



@endsection
@push('scripts')
<script src="{{ asset('js/blockUI.js') }}"></script>

@endpush
@push('scripts')
    <script>

        $('.pay_type').change(function (e) {
            var btn = $(e.currentTarget);

            if (btn.hasClass('paybox') && btn.prop("checked") == true)
            {
                $('#yandex').prop("checked",false);
                $('#pay_type').val('paybox');
            }
            else if(btn.hasClass('yandex') && btn.prop("checked") == true)
            {
                $('#paybox').prop("checked", false);
                $('#pay_type').val('yandex');
            }
            else
            {
                $('#pay_type').val('');
            }

            if($('#pay_type').val() == '')
            {
                $('.buy').addClass('agree_block');
            }
            else
            {
                $('.buy').removeClass('agree_block');
            }
        })
    </script>
    <script>
        // $('.buy').click(function (e) {
        //     $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        //     var event_id = $('#id').val();
        //     var row = $('#row').val();
        //     var column = $('#column').val();
        //     var price = $('#price').val();
        //     var found = $('#found').val();
        //     var promo = $('#promo').val();
        //     var promotion = $('#promotion').val();
        //     var show = $('#show').val();
        //     var ticket = $('#ticket').val();
        //     var pay_type = $('#pay_type').val();
        //     var btn = $(e.currentTarget);
        //     btn.addClass('loading');
        //     $.ajax({
        //         url: '{{ route('buy_ticket') }}',
        //         method: 'POST',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "row": row,
        //             "column": column,
        //             "price": price,
        //             "promo": promo,
        //             "promotion": promotion,
        //             "event_id": event_id,
        //             "found": found,
        //             "show": show,
        //             "ticket": ticket,
        //             "pay_type": pay_type
        //         },
        //         success: data => {
        //             $.unblockUI();
        //             if (data.status == 'success') {
        //                 $('#success_modal').modal('show');
        //                 btn.removeClass('loading');
        //             }
        //             else if (data.status == 'error') {
        //                 if (data.check == 0) {
        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'info',
        //                         title: 'Вы уже покупали билет!',
        //                         // description: ' Каждый пользователь может купить лишь 1 билет ' ,
        //                         showConfirmButton: true,
        //                     });
        //                 }
        //                 else if (data.check = 1) {
        //                     Swal.fire({
        //                         position: 'center',
        //                         icon: 'info',
        //                         title: 'У вас есть забронированный билет!',
        //                         description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
        //                         showConfirmButton: true,
        //                     });
        //                 }
        //                 btn.removeClass('loading');
        //                 // place.removeClass('place-choice');
        //             }
        //
        //
        //         },
        //         error: () => {
        //             btn.removeClass('loading');
        //         }
        //     });
        // });
    </script>

    <script>
        $('.reserved').click(function (e) {
            var event_id = $('#event_id').val();
            var row = $('#row').val();
            var column = $('#column').val();
            var price = $('#price').val();
            var found = $('#found').val();
            var show = $('#show').val();
            var promo = $('#promo').val();
            var promotion = $('#promotion').val();
            var format_id = $('#format_id').val();
            var btn = $(e.currentTarget);
            btn.addClass('loading');
            $.ajax({
                url: '{{ route('reserve_ticket') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "row": row,
                    "column": column,
                    "price": price,
                    "promo": promo,
                    "promotion": promotion,
                    "event_id": event_id,
                    "found": found,
                    "format_id": format_id,
                    "show": show,
                },
                success: data => {
                    if (data.status == 'success') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Бронь совершена!',
                            // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                        });
                        setTimeout(() => window.location="/profile",2000);
                        // $('#success_modal').modal('show');


                        btn.removeClass('loading');
                    }
                    else if (data.status == 'error') {
                        if (data.check == 0) {
                            Swal.fire({
                                position: 'center',
                                icon: 'info',
                                title: 'Вы уже покупали билет!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: true,
                            });
                        }
                        else if (data.check = 1) {
                            Swal.fire({
                                position: 'center',
                                icon: 'info',
                                title: 'У вас есть забронированный билет!',
                                description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
                                showConfirmButton: true,
                            });
                        }
                        btn.removeClass('loading');
                        // place.removeClass('place-choice');
                    }


                },
                error: () => {
                    btn.removeClass('loading');
                }
            });
        });
    </script>
@endpush
