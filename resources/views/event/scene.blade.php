@extends('layouts.app')
@section('content')
    <?php
    \Illuminate\Support\Facades\Session::forget('request');

     $event_currency = \App\CurrencyNames::find($event->currency)->currency;

    ?>

    <div class="container mt-4">
        <div class="position-relative">
            <div class="d-flex flex-wrap">
            <div class="col-12">
                <a class="d-flex" href="{{ route('event.view', ['id' => $event->id]) }}" >
                    <img src="{{ asset('images/arrow_back.svg') }}" alt=""><span class="annotation-back ml-1">Назад</span>
                </a>
            </div>
            <div class="col-12 text-center py-4 d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{asset('images/calendar.svg')}}" alt="">
                    <p class="secondary-title mb-0 ml-3"> Мероприятие</p>
                </div>
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
           <div class="col-12">
              <div class="about-course p-5">
                <p class="mb-1 title">Выбранный курс:</p>
                <p class="name">{{ $event->title }}</p>
                 <div class="row mt-5">
                     <div class="col-4">
                         <p class="mb-1 title">Спикер:</p>
                         <p class="description">{{ $event->mentor }}</p>
                     </div>
                     <div class="col-lg-3 col-12">
                         <p class="mb-1 title">Город:</p>
                         <p class="description">{{ $event->city }}</p>
                     </div>
                     <div class="col-lg-3 col-12">
                         <p class="mb-1 title">Дата:</p>
                         <p class="description">{{ $event->date }}</p>
                     </div>
                     <div class="col-lg-2 col-12">
                         <p class="mb-1 title">Стоимость:</p>
                         <p class="description">{{ $event->rate[0][2]}} {{$event_currency}}</p>
                     </div>
                 </div>
              </div>
           </div>
                <div class="col-12 text-center py-3 d-flex align-items-center">
                  <img src="{{asset('images/chair.svg')}}" alt="">
                  <p class="secondary-title mb-0 ml-3">Выберите место</p>
                </div>
                <div class="col-lg-8 col-12 mt-2">
                    <div class="main-scene d-flex justify-content-center">
                        <div class="d-flex flex-wrap overflow-auto" style="width:min-content">
                            <div class="d-flex">
                                <div class="scene-place" style="margin: 0 4px;"></div>
                                <div id="scene_block"  style="width: {{$column*35}}px;margin-bottom:24px;">
                                Сцена
                                </div>
                                <div class="scene-place"></div>
                            </div>
                                @for($i = 1; $i <= $row;$i++)
                                <div class="d-flex">
                                    <div class="m-1 font-weight-bold scene-place">{{$i}}</div>
                                    @for($j=1;$j<=$column; $j++)
                                        @if(isset($halls[$i-1]['column'][$j]['ticket_id']))
                                                @php
                                                    if (isset($halls[$i-1]['column'][$j]['show']))
                                                        {
                                                            $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['ticket_id']);
                                                            $user = \App\User::find($ticket->user_id);
                                                        }
                                                @endphp
                                                @if(isset($halls[$i-1]['column'][$j]['show']))
                                                        <div class="m-1 buied scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                                                @else
                                                        <div class="m-1 buied scene-place"></div>
                                                @endif

                                        @elseif(isset($halls[$i-1]['column'][$j]['reserve_id']))
                                                @php
                                                    if (isset($halls[$i-1]['column'][$j]['show'])){
                                                        $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['reserve_id']);
                                                        $user = \App\User::find($ticket->user_id);
                                                    }
                                                @endphp
                                                @if(isset($halls[$i-1]['column'][$j]['show']))
                                                    <div class="m-1 place reserve scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                                                @else
                                                    <div class="m-1 place reserve scene-place"></div>
                                                @endif
                                        @else

                                            @if($halls[$i-1]['column'][$j]['status']!=-1)
                                                @foreach($event->rate as $rate)
                                                    @if ($halls[$i-1]['column'][$j]['status'] == $loop->index)
                                                        @php
                                                            $current_rate_price = $rate[3] >= \Carbon\Carbon::now() ? $rate[4] : $rate[2];
                                                            $without = $rate[2];
                                                        @endphp
                                                        <div class="m-1 place scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" data-parent3="{{$current_rate_price}}" data-without="{{$without}}" data-parent4="{{$loop->index}}"  id="{{$i}}-{{$j}}" data-toggle="tooltip" title="{{$j}} место,{{$rate['0']}},{{$current_rate_price}} {{$event_currency}}"
                                                            style="background-color:{{$rate[1]}};border-bottom-color: {{$rate[1]}};">
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if ($halls[$i-1]['column'][$j]['status'] == 105)
                                                    <div class="m-1 place place-empty scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" style="background-color: white;border-style: solid;border-width: 1px; opacity: 0;"></div>

                                                @endif
                                            @else
                                                    <div class="m-1 place scene-place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" style="background-color: #e6e3e3;opacity: 0;"></div>

                                            @endif
                                        @endif
                                    @endfor
                                    <div class="m-1 font-weight-bold scene-place">{{$i}}</div>
                                    </div>
                                    <div class="col-12"></div>
                                @endfor
                            </div>
                        </div>

                </div>
                <div class="col-lg-4 col-12 mt-lg-2">
                  <div class="ticket-category px-5 py-5">
                    <p class="third-title mb-0"> Категории билетов</p>
                    @foreach($event->rate as $rate)
                      <div class="d-flex align-items-center my-3">
                         <div class="m-1 scene-place-category" style="background-color: {{$rate[1]}};"></div>
                          <span class="event-place-price-list ml-2 scene-place-title">  Места - {{$rate[0]}}
                            @if($rate[3] >= \Carbon\Carbon::now())
                              <br> <span class="scene-place-text"  style="color:{{$rate[1]}}">По акции {{$rate[4]}} {{$event_currency}}, до {{$rate[3]}}, после {{$rate[2]}} {{$event_currency}} </span>
                            @else
                              <br> <span class="scene-place-text" style="color:{{$rate[1]}}">{{$rate[2]}} {{$event_currency}}</span>
                            @endif
                           </span>
                     </div>
                    @endforeach
                    <div class="col-12 mt-5 px-0">
                        <p class="third-title mb-0">Инфо</p>
                        <div class="d-flex align-items-center my-3 mr-5">
                            <div class="m-1 scene-place-category" style="background-color: #1D1D1D;"></div> <span class="event-place-price-list ml-2"> Выкупленные
                           <br> места</span>
                        </div>

                        <div class="d-flex align-items-center my-3">
                            <div class="m-1 scene-place-category" style="background-color: #0055A7;"></div> <span class="event-place-price-list ml-2"> Забронированные
                           <br> места</span>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-wrap px-0">
                      <div class="d-flex flex-column col-lg-4 px-1">
                        <div class="">Ряд</div>
                        <div class="scene-place-price" id="chosen_row"></div>
                      </div>
                      <div class="d-flex flex-column col-lg-4 px-1">
                        <div class="">Место</div>
                        <div class="scene-place-price" id="chosen_col"></div>
                      </div>
                      <div class="d-flex flex-column col-lg-4 px-1">
                        <div class="">Цена</div>
                        <div class="scene-place-price"><span id="chosen_price"></span> {{$event_currency}}</div>
                      </div>
                    </div>
                    <div class="row mt-1">
                      <div class="col-12 pt-3">
                          <label for="end_event_date" style="color:#6B6B6B;">Промокод</label>
                          <div class="mt-0">
                              <input placeholder="Если он у вас есть введите его сюда" type="text" id="promocode" name="promocode" class="form-control input-style grey-bg" required>
                          </div>
                      </div>
                      <div class="col-12 pt-3 d-flex">
                            <div class="d-flex flex-column col-lg-6 pl-0">
                                <label class="">Скидка промокода</label>
                                <div class="scene-place-price promocode-info promo-name w-100"></div>
                            </div>
                            <div class="d-flex flex-column col-lg-6 pr-0">
                                <label class="">Со скидкой</label>
                                <div class="scene-place-price promocode-info w-100" id="with_promo" data-promo="0"></div>
                            </div>
                      </div>
                    </div>
                    @auth
                        @if(Auth::user()->role_id == 2)
                          @if(isset($event->buy_deadline) && $event->buy_deadline > \Carbon\Carbon::now())
                            <button class="select-button w-100 BuyTicket mt-2" data-parent="buy" data-id="{{$event->id}}">
                                Купить билет
                            </button>
                          @endif
                          @if(isset($event->reserve_date) && \Carbon\Carbon::parseFromLocale($event->reserve_date, 'ru') > \Carbon\Carbon::now())
                            <button class="select-button select-button-grey w-100 BuyTicket mt-2" data-parent="reserve" data-id="{{$event->id}}">
                                Забронировать
                            </button>
                          @endif
                        @endif
                    @else
                        @if(isset($event->buy_deadline) && $event->buy_deadline > \Carbon\Carbon::now())
                            <button class="select-button w-100 mt-2" data-toggle="modal"
                                    data-target="#UnregBuy">
                                Купить билет
                            </button>
                        @endif
                        @if(isset($event->reserve_date) && \Carbon\Carbon::parseFromLocale($event->reserve_date, 'ru') > \Carbon\Carbon::now())
                              <button class="select-button select-button-grey w-100 mt-2" data-toggle="modal"
                                      data-target="#UnregBuy">
                                  Забронировать
                              </button>
                        @endif
                    @endauth
                     <form class="d-none" action="{{route('buy_page')}}" id="BuyForm" method="POST" enctype="multipart/form-data">
                         @csrf
                         <input type="hidden" id="send_id" name="id" value="{{$event->id}}">
                         <input type="hidden" id="send_type" name="type">
                         <input type="hidden" id="send_row" name="row">
                         <input type="hidden" id="send_column" name="column">
                         <input type="hidden" id="send_price" name="price">
                         <input type="hidden" id="send_promo" name="promo">
                         <input type="hidden" id="send_promotion" name="promotion">
                         <input type="hidden" id="send_format" name="format_id">
                     </form>
                     </div>
                </div>
                @if(auth() || \Illuminate\Support\Facades\Auth::user()->role_id == 2 )
                    <div class="auth-passport" data-parent="{{ $event->client_count }}"></div>
                @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 4 || \Illuminate\Support\Facades\Auth::user()->role_id == 5)
                    <div class="auth-passport" data-parent="{{ $event->franch_count }}"></div>
                @endif
                @auth
                    @include('modals.buy.order_modal')
                @else
                    @include('modals.buy.buy_modal')
                @endauth
        </div>
    </div>
</div>
    @push('scripts')
        <script>
            $(document).ready(function() {
              var triangles = document.querySelectorAll(".triangle_shape");
              triangles.forEach(t => t.classList.add('triangle_shape_scene'));

              var ovals = document.querySelectorAll(".oval_shape");

              ovals.forEach(o => o.classList.remove('oval_shape'));
              ovals.forEach(o => o.classList.add('oval_shape_scene'));

            });


            $('.place').click(e => {
                e.preventDefault();
                let btn = $(e.currentTarget);

                if (btn.hasClass('place-choice')) {
                    btn.removeClass('place-choice');
                    $('#chosen_row').html();
                    $('#chosen_col').html();
                    $('#chosen_price').html();
                }
                else {
                  $('.place').removeClass('place-choice');
                  btn.addClass('place-choice');
                  $('#chosen_row').html(btn.attr('data-parent'));
                  $('#chosen_col').html(btn.attr('data-parent2'));
                  $('#chosen_price').html(btn.attr('data-parent3'));
                }

            })
        </script>

        @if ($errors->any())
        <script>
        $('#UnregBuy').modal('show');
        </script>
        @endif
        <script>
            $('.BuyTicket').on('click', function (e) {
                var place = $('.place-choice');
                if (place.length < 1)
                {
                    Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Вы не выбрали место!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                    });
                }
                else
                {
                    var btn = $(e.currentTarget);
                    var price = place.data('parent3');
                    var without_promotion = place.data('without');
                    var type = btn.data('parent');
                    var row = place.data('parent');
                    var column = place.data('parent2');
                    var format = place.data('parent4');
                    var promo = $('#with_promo').attr('data-promo');
                    $('#send_type').val(type);
                    $('#send_row').val(row);
                    $('#send_column').val(column);
                    $('#send_price').val(price);
                    $('#send_promo').val(promo);
                    $('#send_promotion').val(without_promotion);
                    $('#send_format').val(format);
                    $('#BuyForm').submit();
                }
            })
        </script>
        <script>
            $('.reserve-ticket').on('click', function (e) {
                var btn = $(e.currentTarget);
                var place = $('.place-choice');
                var row = place.data('parent');
                var event_id = btn.data('id');
                var column = place.data('parent2');
                var format = place.data('parent4');

                $.ajax({
                    url: '{{ route('reserve_ticket') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "row": row,
                        "column": column,
                        "event_id": event_id,
                    },
                    success: data => {
                        if (data.status == 'success') {
                            place.addClass('reserve');

                        }
                        else if (data.status == 'error') {
                            Swal.fire({
                                position: 'center',
                                icon: 'info',
                                title: 'У вас уже есть бронь!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: true,
                            });
                            place.removeClass('place-choice');
                        }


                    },
                    error: () => {
                    }
                })
            })
        </script>
        <script>
            $(document).on('click','.place',function(){
                $('#promocode').trigger('input');
            });
            $(document).on('input','#promocode',function () {
                var promo = $('#promocode').val();
                let place_choice = parseInt($('.place-choice').attr('data-without'));
                if(!place_choice){
                  $('.promo-name').html('Выберите место');
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
                                $('#with_promo').html(parseInt(result)).attr('data-promo',data.promo.promo);
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
@endsection
