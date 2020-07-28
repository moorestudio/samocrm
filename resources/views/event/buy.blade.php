@extends('layouts.app')
@section('content')
    <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    @if($event->scheme == 1)
                      <a class="d-flex" href="{{ route('buy', ['id' => $event->id]) }}">
                          <img src="{{ asset('images/arrow_back.svg') }}" alt=""><span class="annotation-back ml-1">Назад</span>
                      </a>
                    @else
                        <a class="d-flex" href="{{ route('event.view', ['id' => $event->id]) }}">
                            <img src="{{ asset('images/arrow_back.svg') }}" alt=""><span class="annotation-back ml-1">Назад</span>
                        </a>
                    @endif
                </div>
                <div class="col-12 text-center py-4 d-flex align-items-center justify-content-lg-start justify-content-center">
                    <img src="{{asset('images/report/open-menu1.svg')}}" alt="">
                    <p class="main-title ml-2 mb-0">{{$type == 'buy'? 'Оформление заказа' : 'Оформление брони'}}</p>
                    @auth
                        @if($message = \App\Ticket::hasTicket($event->id,auth()->user()->id))
                            <div class="ml-5">
                                <p class="notify mb-0" style="font-weight: bolder;font-size: 20px;">
                                    {{$message}}
                                </p>
                            </div>
                        @endif
                    @endauth 
                </div>
 
                @php
                    $event_currency = \App\CurrencyNames::find($event->currency)->currency;
                @endphp
            </div>
        <form action="{{route('buy_first')}}" id="BuyForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$event->id}}">
            <input type="hidden" name="row" value="{{$row}}">
            <input type="hidden" name="column" value="{{$column}}">
            <input type="hidden" id="0_price" name="price" value="{{$price}}">
            <input type="hidden" id="0_promotion" name="promotion" value="{{$promotion}}">
            <input type="hidden" id="0_promo" name="promo" value="{{$promo}}">
            <input type="hidden" name="type" value="{{$type}}">
            <input type="hidden" name="ticket" value="{{$ticket_id}}">
            <input type="hidden" id="0_format_id" name="format_id" value="{{$format_id}}">
            <input type="hidden" name="currency_name" id="currency_name" value="{{$event->getCurrencyName()}}">
            <div class="d-flex flex-wrap white-bg-round">
                <div class="col-lg-10 col-12 p-5">
                    <p class="second-title text-uppercase text-left mb-3">Личные данные</p>
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-4">
                            <label for="lastname" style="color:#A6A6A6;">Фамилия*</label>
                            <input class="input-style form-control" type="text" id="lastname" value="{{$user->last_name}}" disabled>
                        </div>
                        <div class="col-lg-6 col-12 mb-4">
                            <label for="name" style="color:#A6A6A6;">Имя*</label>
                            <input class="input-style form-control" type="text" id="name" value="{{$user->name}}" disabled>
                        </div>
                        <div class="col-lg-6 col-12 mb-lg-0 mb-4">
                            <label for="phone" style="color:#A6A6A6;">Номер телефона*</label>
                            <input class="input-style form-control" type="text" id="phone" value="{{$user->contacts}}" disabled>
                        </div>
                        <div class="col-lg-6 col-12">
                            <label for="email" style="color:#A6A6A6;">Почта*</label>
                            <input class="input-style form-control" type="text" id="email" value="{{$user->email}}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10 col-12 px-5 pb-5">
                    <p class="second-title text-uppercase text-left mb-3">Дополнительно</p>
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-4">
                            <label for="country" style="color:#A6A6A6;">Страна</label>
                            <input class="input-style form-control grey-bg" type="text" id="country" name="country" value="{{$user->country}}">
                        </div>
                        <div class="col-lg-6 col-12 mb-4">
                            <label for="city" style="color:#A6A6A6;">Город</label>
                            <input class="input-style form-control grey-bg" type="text" id="city" name="city" value="{{$user->city}}">
                        </div>
                        <div class="col-lg-6 col-12 mb-lg-0 mb-4">
                            <label for="work" style="color:#A6A6A6;">Деятельность</label>
                            <input class="input-style form-control grey-bg" type="text" id="work" name="work" value="{{$user->work_type}}">
                        </div>
                        <div class="col-lg-6 col-12">
                            <label for="found" style="color:#A6A6A6;">Откуда узнали?*</label>
                            <select class="input-style form-control grey-bg" type="text" id="found" name="found">
                                <option value="Социальные сети">Социальные сети</option>
                                <option value="Партнер">Партнер</option>
                                <option value="Рекомендации">Рекомендации</option>
                                <option value="Афиша по городу">Афиша по городу</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if($event->scheme == 0 && empty($ticket_id))
                <div class="col-12 px-5 pb-4">
                    <div class="about-course p-4">
                    <p class="mb-1 title">Выбранный курс:</p>
                    <p class="name">{{ $event->title }}</p>
                    <div class="row mt-5">
                        <div class="col-4">
                            <p class="mb-1 title">Спикер:</p>
                            <p class="description">{{ $event->mentor }}</p>
                        </div>
                        <div class="col-lg-2 col-12">
                            <p class="mb-1 title">Город:</p>
                            <p class="description">{{ $event->city }}</p>
                        </div>
                        <div class="col-lg-4 col-12">
                            <p class="mb-1 title">Дата:</p>
                            <p class="description">{{ $event->date }}</p>
                        </div>
                        <div class="col-lg-6 col-12">
                            <p class="mb-1 title">Категории билетов:</p>
                            <select onchange="getData(this)" class="browser-default custom-select input-style mt-1 grey-bg" name="category_id" id="category_id">
                                <option value="" selected disabled>Выберите категорию</option>
                                @foreach($event->rate as $rate)
                                    @if($rate[3] >= \Carbon\Carbon::now())
                                        <option name="{{$rate[0]}}" discounted="{{$rate[4]}}" full="{{$rate[2]}}" discount_date="{{$rate[3]}}" value="{{$rate[4]}}">{{$rate[0]}}</option>
                                    @else
                                        <option name="{{$rate[0]}}" discounted="{{$rate[2]}}" full="{{$rate[2]}}"  value="{{$rate[2]}}"><span class="font-weight-bold">{{$rate[0]}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-12 d-flex align-items-between flex-wrap">
                            <p class="mb-1 title w-100">Стоимость билета:</p>
                            <p class="selected_ticket_cat mb-0 description mt-2 pb-3"></p>
                        </div>
                        <div class="col-lg-6 col-12 mt-4">
                            <p class="mb-1 title">Промокод</p>
                            <input placeholder="Если он у вас есть введите его сюда" type="text" id="promocode" name="promocode" class="form-control input-style grey-bg">
                        </div>
                        <div class="col-lg-6 col-12 mt-4">
                            <p class="mb-1 title promo-name"></p>
                            <div class="mt-0 description mt-3" id="with_promo" data-promo="0">
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endif
            <div class="d-flex flex-column px-5 pb-5">
                <div class="custom-checkbox custom-control py-2 {{$event->scheme == 0 ? 'd-none' : ''}}">
                    <input type="checkbox" class="custom-control-input" name="show" id="show">
                    <label class="custom-control-label" for="show">Показывать ваше ФИО при выборе места?</label>
                </div>
                <div class="custom-checkbox custom-control py-2">
                    <input type="checkbox" class="custom-control-input" name="agree_conf" id="agree_conf">
                    <label class="custom-control-label" for="agree_conf">Я согласен с <span style="color: #6eb7ee;">
                    <a href="{{ asset('images/Пользовательское_соглашение_САМО_2020г.pdf') }}" target="_blank">
                    политикой конфиденциальности
                    </a>
                    </span></label>
                </div>
                <div class="custom-checkbox custom-control py-2">
                    <input type="checkbox" class="custom-control-input" name="agree" id="agree">
                    <label class="custom-control-label" for="agree">Я согласен с <span style="color: #6eb7ee;">
                    <a href="{{ asset('images/Пользовательское_соглашение_САМО_2020г.pdf') }}" target="_blank">
                    пользовательским соглашением
                    </a>
                    </span></label>
                </div>

                <button class="btn btn-success agree_check agree_block ml-0 mt-4" type="submit">Далее</button>
                </div>
            </div>
        </form>
    </div>

@endsection
@push('scripts')
    <script>
        $('#agree, #category_id').on('change', function (e) {
            let btn = $('#agree');
            let btn_conf = $("#agree_conf");
            let category = $("#category_id");
            if (btn.prop("checked") == true && btn_conf.prop("checked") == true){
                if(!category.val() && category.length){
                    Swal.fire({
                        position: 'center',
                        icon: 'info',
                        title: 'Выберите категорию',
                        showConfirmButton: false,
                    });
                }else{
                    $('.agree_check').removeClass('agree_block');
                } 
            }
            else {
                $('.agree_check').addClass('agree_block');
            }
        })
        $('#agree_conf').on('click', function (e) {
            let btn = $(e.currentTarget);
            let btn_user_agree = $("#agree");
            if (btn.prop("checked") == true && btn_user_agree.prop("checked") == true) {
                $('.agree_check').removeClass('agree_block')
            }
            else {
                $('.agree_check').addClass('agree_block');
            }
        })

        function getData(select) {
            console.log('-sdsdsdsd-');
            var price = select.value;
            var selectedOption = select.options[select.selectedIndex];
            var name = selectedOption.getAttribute('name');
            var discounted = selectedOption.getAttribute('discounted');
            var full = selectedOption.getAttribute('full');
            var discount_date = selectedOption.getAttribute('discount_date');
            var text = "";
            var currency_name = $('#currency_name').val();
            if(discount_date){
                text = 'по акции '+discounted+' '+currency_name+' до '+discount_date+", после "+full+' '+currency_name;
            }
            else{
                text = full+' '+currency_name;
            }
            $('.selected_ticket_cat').html(text);
            $('.selected_ticket_cat').attr("full",full);
            $('.selected_ticket_cat').attr("discounted",discounted);

            $('#0_price').val(discounted);
            $('#0_format_id').val(select.selectedIndex-1);
            $('#0_promotion').val(full);
        }


        // $('#category_id').on('change', function() {
        // });

        $(document).on('click','.place',function(){
            $('#promocode').trigger('input');
        });
        $(document).on('input','#promocode',function () {
            var promo = $('#promocode').val();
            var currency_name = $('#currency_name').val();
            let place_choice = parseInt($('.selected_ticket_cat').attr('full'));
            if(!place_choice){
              $('.promo-name').html('Выберите категорию');
              $('#with_promo').html('').attr('data-promo',0);
            }else if(promo != ''){
                $.ajax({
                    url: '{{ route('check_promo') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "promo": promo,
                    },
                    success: data => {
                        if(data.check == true)
                        {
                            let result = place_choice - (place_choice/ 100)* parseInt(data.promo.discount);
                            $('#promo').attr('disabled',true);
                            $('.promo-name').html(data.promo.name+' '+data.promo.discount + '%');
                            $('#with_promo').html(parseInt(result)+' '+currency_name).attr('data-promo',data.promo.promo);
                            $('#0_promo').val(promo);
                        }else{
                          $('.promo-name').html('Промокод не найден');
                          $('#with_promo').html('').attr('data-promo',0);
                        }
                    },
                    error: () => {
                    }
                })
            }
        })













    </script>
@endpush
