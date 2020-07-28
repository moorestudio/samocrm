@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="position-relative">
            <div class="row">
              <div class="col-12">
                  <a class="d-flex" href="{{ route('buy_for_client_events')}}" >
                      <img src="{{ asset('images/arrow_back.svg') }}" alt=""><span class="annotation-back ml-1">Назад</span>
                  </a>
              </div>
              <div class="col-12 text-center py-4 d-flex align-items-center">
                  <img src="{{asset('images/calendar.svg')}}" alt="">
                  <p class="secondary-title mb-0 ml-3"> Мероприятие</p>
              </div>
              <div class="col-12">
                <div class="about-course p-5 ">
                  <p class="mb-1 title">Название:</p>
                  <p class="name">{{ $event->title }}</p>
                  <p class="name">Свободных мест: <span style="color:black;">{{ $event->free_seats() }}</span>, общее количество мест: <span style="color:black;">{{ $event->ticket_count }}</span></p>
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
                         <p class="mb-1 title" >Дата:</p>
                         <p class="description">{{ $event->date }}</p>
                     </div>
                 </div>
               </div>
           </div>
           @if($event->scheme)
           <div class="col-12 text-center py-3 d-flex align-items-center">
             <img src="{{asset('images/chair.svg')}}" alt="">
             <p class="secondary-title mb-0 ml-3">Выберите место</p>
           </div>
           @endif
           <?php
             $event_currency = \App\CurrencyNames::find($event->currency)->currency;
            ?>
        </div> 
        @if($event->scheme)
          <div class="main-scene d-flex justify-content-center flex-wrap">

           
            <div class="d-flex flex-wrap overflow-auto" style="width:min-content">
                  <div class="d-flex">
                    <div class="scene-place" style="margin: 0 4px;"></div>
                    <div id="scene_block"  style="width: {{$column*35}}px;margin-bottom:24px;">
                      Сцена
                    </div>
                    <div class="scene-place"></div>
                  </div>
                    @for($i = 1; $i <= $row;$i++)
                      <div class="d-flex"  style="width: max-content;">
                        <div class="m-1 font-weight-bold scene-place">{{$i}}</div>
                        @for($j=1;$j<=$column; $j++)
                          @if(isset($halls[$i-1]['column'][$j]['ticket_id']))
                                <?php

                                            $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['ticket_id']);
                                            $user = \App\User::find($ticket->user_id);

                                ?>
                          <div class="m-1 buied scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                          @elseif(isset($halls[$i-1]['column'][$j]['reserve_id']))
                            <?php


                                $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['reserve_id']);
                                $user = \App\User::find($ticket->user_id);

                            ?>
                          <div class="m-1 place reserve scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                          @else
                             @if($halls[$i-1]['column'][$j]['status']!=-1)
                                  @foreach($event->rate as $rate)
                                      @if ($halls[$i-1]['column'][$j]['status'] == $loop->index)
                                        <?php
                                          $current_rate_price = $rate[3] >= \Carbon\Carbon::now() ? $rate[4] : $rate[2];
                                          $without = $rate[2];
                                        ?>
                                      <div class="m-1 place scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" data-without="{{$without}}" data-parent3="{{$current_rate_price}}" data-parent4="{{$loop->index}}"  id="{{$i}}-{{$j}}" data-toggle="tooltip" title="{{$j}} место,{{$rate['0']}},{{$current_rate_price}} {{$event_currency}}" style="background-color:{{$rate[1]}};border-bottom-color: {{$rate[1]}};">
                                      </div>
                                      @endif
                                  @endforeach
                                  @if ($halls[$i-1]['column'][$j]['status'] == 105)
                                      <div class="m-1 place place-empty scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}"></div>

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
            <div class="d-flex mt-3 flex-wrap">
              <div class="col-4 d-flex flex-wrap">
                
                <div class="col-12 text-center d-flex align-items-center py-4">
                  <p class="main-title text-uppercase text-lg-left text-center mb-0 ml-3" style="color: #1E2433;">Категории билетов</p>
                </div>

              </div>
              <div class="col-4 d-flex flex-wrap">
                <div class="col-12 text-center d-flex align-items-center py-4">
                  <p class="main-title text-uppercase text-lg-left text-center mb-0 ml-3" style="color: #1E2433;">Инфо</p>
                </div>
              </div>
              <div class="col-4 d-flex flex-wrap">
                <div class="col-12 text-center d-flex align-items-center py-4">
                  <p class="main-title text-uppercase text-lg-left text-center mb-0 ml-3" style="color: #1E2433;">Итог / Промокод</p>
                </div>
              </div>
              <div class="col-4 d-flex flex-wrap align-self-start">
                  <div class="col-lg-12 col-12 ">
                    
                      @foreach($event->rate as $rate)
                        <div class="d-flex my-3">
                           <div class="ml-1 scene-place-category" style="background-color: {{$rate[1]}};display: inline-block;"></div>
                           <div class="d-flex flex-column">
                                <span class="event-place-price-list ml-2 scene-place-title"> Места - {{$rate[0]}}</span>
                                @if($rate[3] >= \Carbon\Carbon::now())
                                  <span class="scene-place-text ml-2" style="color:{{$rate[1]}}">По акции {{$rate[4]}} {{$event_currency}}, до {{$rate[3]}}, после {{$rate[2]}} {{$event_currency}}</span>
                                @else
                                  <span class="scene-place-text ml-2" style="color:{{$rate[1]}}">{{$rate[2]}} {{$event_currency}} (акция закончилась {{$rate[3]}})</span>
                                @endif
                            </div>
                       </div>
                      @endforeach
                  
                  </div>
              </div>

              <div class="col-4 d-flex flex-wrap align-self-start">
                <div class="d-flex align-items-center mr-5">
                    <div class="m-1 scene-place" style="background-color: #1D1D1D;"></div> <span class="event-place-price-list ml-2"> Выкупленные
                   <br> места</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="m-1 scene-place" style="background-color: #0055A7;"></div> <span class="event-place-price-list ml-2"> Забронированные
                   <br> места</span>
                </div>
              </div>


              <div class="col-4 d-flex flex-wrap align-self-start">
                <div class="d-flex flex-column col-lg-4">
                  <div class="">Ряд</div>
                  <div class="scene-place-price" id="chosen_row"></div>
                </div>
                <div class="d-flex flex-column col-lg-4">
                  <div class="">Место</div>
                  <div class="scene-place-price" id="chosen_col"></div>
                </div>

                <div class="d-flex flex-column col-lg-4">
                  <div class="">Цена</div>
                  <div class="scene-place-price"><span id="chosen_price"></span> {{$event_currency}}</div>
                </div>
                <div class="d-flex flex-column col-lg-12 mt-2">
                  <label for="promocode">Промокод</label>
                  <div class="mt-0">
                      <input placeholder="название" type="text" id="promocode" name="promocode" class="form-control input-style grey-bg" required>
                  </div>
                </div>
                <div class="d-flex flex-column col-lg-6 mt-2">
                  <div class="">Скидка промокода</div>
                  <div class="scene-place-price promocode-info promo-name w-100"></div>
                </div>
                <div class="d-flex flex-column col-lg-6 mt-2">
                  <div class="">Со скидкой</div>
                  <div class="scene-place-price promocode-info w-100"><span id="with_promo"></span> {{$event_currency}}</div>
                </div>
              </div>
              </div>
              

          </div>
          @endif
          @if(!$event->scheme)
          <div class="row about-course p-3 m-0 mt-5">
            <div class="col-6">
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

            <div class="col-6">
                <div class="d-flex flex-column col-lg-12">
                  <div class="">Цена</div>
                  <div class="scene-place-price w-100" style="height: 47px;"><span id="chosen_price"></span>  {{$event_currency}}</div>
                </div>
                <div class="d-flex flex-column col-lg-12 mt-2">
                  <label for="promocode">Промокод</label>
                  <div class="mt-0">
                      <input placeholder="название" type="text" id="promocode" name="promocode" class="form-control input-style grey-bg" required>
                  </div>
                </div>
                <div class="d-flex col-lg-12">
                  <div class="mt-2  w-100 mr-2">
                    <div class="">Скидка промокода</div>
                    <div class="scene-place-price promocode-info promo-name w-100"></div>
                  </div>
                  <div class="mt-2  w-100">
                    <div class="">Со скидкой</div>
                    <div class="scene-place-price promocode-info w-100"><span id="with_promo"></span>  {{$event_currency}}</div>
                  </div>
                </div>

            </div>



          </div>
          @endif


        </div>

<div class="row position-relative">
  <div class="col-12 text-center py-4 d-flex align-items-center">
      <img src="{{asset('images/choose.svg')}}" alt="">
      <p class="secondary-title mb-0 ml-3"> Выберите слушателя или заполните анкету</p>
  </div>
  <div class="col-8 p-0">
    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom:1px solid #E1E8F3;">
        <li class="nav-item mb-0">
            <a class="nav-link active user_list_btn_switch" id="old_user_buy_tab_btn" data-toggle="tab" href="#old_user_buy_tab_pane" role="tab" aria-controls="old_user_buy_tab_btn" aria-selected="true">
                <img src="{{asset('images/search.svg')}}" alt="">
                Поиск в базе
            </a>
        </li>
        <li class="nav-item mb-0">
            <a class="nav-link  user_list_btn_switch" id="new_user_buy_tab_btn" data-toggle="tab" href="#new_user_buy_tab_pane" role="tab" aria-controls="old_user_buy_tab_btn" aria-selected="false">
                <img src="{{asset('images/users1.svg')}}" alt="">
                Заполнить данные нового слушателя
            </a>
        </li>
    </ul>
</div>
<div class="col-12">
<div class="tab-content">
    <div class="tab-pane fade show active " id="old_user_buy_tab_pane" role="tabpanel" aria-labelledby="old_user_buy_tab_pane">
      <div class="row justify-content-center">
      @include('event.buy.event_includes.old_user_data')
      </div>
    </div>
    <div class="tab-pane fade " id="new_user_buy_tab_pane" role="tabpanel" aria-labelledby="new_user_buy_tab_pane">
      <div class="row justify-content-center">
      @include('event.buy.event_includes.user_form')
    </div>
    </div>
  </div>
</div>
  <div class="col-12 text-center py-4 d-flex align-items-center">
      <img src="{{asset('images/cart.svg')}}" alt="">
      <p class="secondary-title mb-0 ml-3">Детали покупки</p>
  </div>
  <div class="col-12 p-0">
    <div class="table-responsive about-course">
      <div class="d-flex p-5" id="tbody_purchase_details">
        <div class="d-flex flex-column col-2">
          <div class="title">Имя слушателя</div>
          <div class="description" id="purchase_details_name"></div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Фамилия слушателя</div>
          <div class="description" id="purchase_details_last_name"></div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Мероприятие</div>
          <div class="description" id="purchase_details_event">{{ $event->title }}</div>
        </div>

        @if($event->scheme)
        <div class="d-flex flex-column col-2">
          <div class="title">Ряд</div>
          <div class="description" id="purchase_details_row"></div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Место</div>
          <div class="description" id="purchase_details_column"></div>
        </div>
        @endif

        <div class="d-flex flex-column col-2">
          <div class="title">Цена</div>
          <div class="description"><span id="purchase_details_price"></span>{{$event_currency}}</div>
          <input type="hidden" name="" value="{{$event_currency}}" id="purchase_details_currency">
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 report-header mt-4 d-flex">
    <div class="col-6 d-flex align-items-center">
        <label class="w-50 mb-0" for="found">Откуда слушатель узнал о мероприятии</label>
        <select class="input-style form-control special-select w-50" type="text" id="info_source" name="info_source">
            <option value="Социальные сети">Социальные сети</option>
            <option value="Партнер">Партнер</option>
            <option value="Рекомендации">Рекомендации</option>
            <option value="Афиша по городу">Афиша по городу</option>
        </select>
    </div>
    <div class="col-4">
        <div class="custom-checkbox custom-control py-2 {{$event->scheme == 0 ? 'd-none' : ''}}">
            <input type="checkbox" class="custom-control-input" name="show" id="show">
            <label class="custom-control-label" for="show">Показывать данные слушателя при выборе места</label>
        </div>
    </div>
  </div>
<!--   <div class="col-12 text-center py-4 d-flex align-items-center">
      <img src="{{asset('images/card.svg')}}" alt="">
      <p class="secondary-title mb-0 ml-3">Выберите способ оплаты</p>
  </div> -->
  <div class="d-flex flex-wrap px-2 col-12" style="background: #FFFFFF;border-radius: 5px;">
<!--     <div class="col-lg-2 col-6">
      <div class="p-3 text-center">
          <div class="d-flex w-100 justify-content-center mb-2">
            <div class="custom-checkbox custom-control text-center">
                <input type="checkbox" class="custom-control-input pay_type paybox" name="paybox" id="paybox">
                <label class="custom-control-label" for="paybox"></label>
            </div>
              Pay box
          </div>
          <img class="img-fluid" src="{{ asset('images/pay_box.svg') }}" alt="">
      </div>
    </div>
    <div class="col-lg-2 col-6">
      <div class="p-3 text-center">
        <div class="d-flex w-100 justify-content-center mb-2">
            <div class="custom-checkbox custom-control text-center">
                <input type="checkbox" class="custom-control-input pay_type yandex" name="yandex" id="yandex">
                <label class="custom-control-label" for="yandex"></label>
            </div>
            Яндекс деньги
        </div>
        <img class="img-fluid" src="{{ asset('images/yandex.svg') }}" alt="">
      </div>
    </div>
    <div class="col-lg-2 col-6">
      <div class="p-3 text-center">
        <div class="d-flex w-100 justify-content-center mb-2">
            <div class="custom-checkbox custom-control text-center">
                <input type="checkbox" class="custom-control-input pay_type cash" name="cash" id="cash">
                <label class="custom-control-label" for="cash"></label>
            </div>
              Наличные
        </div>
        <img class="img-fluid" src="{{ asset('images/cash.svg') }}" alt="">
      </div>
    </div> -->
    @if($event->free_seats()>0)
    <div class="col-12 d-flex">
      <button class="btn btn-success final_btn_purchase_for_client_class" id="final_btn_purchase_for_client_reserve" event_id="{{$event->id}}" event_name="{{$event->title}}" info_source="" user_name="" type="reserve">Забронировать</button>
<!--       <button class="btn btn-warning final_btn_purchase_for_client_class" id="final_btn_purchase_for_client_buy" event_id="{{$event->id}}" event_name="{{$event->title}}" info_source="" user_name="" type="buy">Выписать и отправить</button> -->
      <button class="btn btn-warning final_btn_purchase_for_client_class" id="final_btn_purchase_for_client_buy" event_id="{{$event->id}}" event_name="{{$event->title}}" info_source="" user_name="" pay_type="cash" type="buy">Выписать и отправить</button>
    </div>
    @else
    <p class="p-3 font-weight-bold">Все места выкуплены!</p>
      
    @endif
  </div>
</div>
  <form action="{{route('reserve_ticket_for_payment')}}" method="POST" id="for_buy_form">
      @csrf
      <input type="hidden" name="user_id" class="for_buy_id">
      <input type="hidden" name="row" class="for_buy_row">
      <input type="hidden" name="column" class="for_buy_column">
      <input type="hidden" name="price" class="for_buy_price">
      <input type="hidden" name="type" class="for_buy_type">
      <input type="hidden" name="event_id" class="for_buy_event_id">
      <input type="hidden" name="found" class="for_buy_info_source">
      <input type="hidden" name="show" class="for_buy_show">
      <input type="hidden" name="ticket" class="for_buy_ticket">
      <input type="hidden" name="pay_type" class="for_buy_pay_type">
      <!-- <input type="hidden" name="pay_type" class="for_buy_pay_type"> -->
      <input type="hidden" name="who" class="for_buy_who">
      <input type="hidden" name="promo" class="for_buy_promo">
      <input type="hidden" name="promotion" class="for_buy_promotion">
      <input type="hidden" id="0_format_id" name="format_id">
      <button type="submit" class="for_buy d-none"></button>
  </form>
  </div>
</div>
@push('scripts')
<script src="{{ asset('js/blockUI.js') }}"></script>
@if ($errors->any())
 <script>
      $('#new_user_form').show();
      $('#tbody_chosen_user_col').hide();
      $('#tbody_user').hide();
 </script>
@endif

<script>
    $(document).on('click','.place',function(){
        $('#promocode').trigger('input');
    });
    $(document).on('input','#promocode',function () {
        var promo = $('#promocode').val();
        @if($event->scheme)
        let place_choice = parseInt($('.place-choice').attr('data-without'));
        @else
        let place_choice = parseInt($('#chosen_price').attr('full'));
        @endif
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

                        $('#purchase_details_price').html(parseInt(result)).attr('data-promo',data.promo.promo);

                        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("promo_price",parseInt(result)));
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
<script>

function getData(select) {
    console.log('-sdsdsdsd-');
    var price = select.value;
    var selectedOption = select.options[select.selectedIndex];
    var name = selectedOption.getAttribute('name');
    var discounted = selectedOption.getAttribute('discounted');
    var full = selectedOption.getAttribute('full');
    var discount_date = selectedOption.getAttribute('discount_date');
    var text = "";

    if(discount_date){
        text = name+': '+discounted+': до '+discount_date+" после "+full;
    }
    else{
        text = name+': '+full;
    }
    $('#chosen_price').html(text);
    $('#chosen_price').attr("full",full);
    $('#chosen_price').attr("discounted",discounted);

    $('#purchase_details_price').html(discounted);
    document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("ticket_price", discounted));
    document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("format_id", select.selectedIndex-1));

    $('#0_format_id').val( select.selectedIndex-1);
    // $('#0_price').val(discounted);
    // $('#0_format_id').val(select.selectedIndex-1);
    // $('#0_promotion').val(full);
}







$('.pay_type').change(function (e) {
    var btn = $(e.currentTarget);

    if (btn.hasClass('paybox') && btn.prop("checked") == true)
    {
        $('#yandex').prop("checked",false);
        $('#cash').prop("checked", false);
        // $('#pay_type').val('cart');
        // document.getElementById("final_btn_purchase_for_client").setAttribute("pay_type",'cart');
        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("pay_type",'paybox'));
    }
    else if(btn.hasClass('yandex') && btn.prop("checked") == true)
    {
        $('#paybox').prop("checked", false);
        $('#cash').prop("checked", false);
        // $('#pay_type').val('yandex');
        // document.getElementById("final_btn_purchase_for_client").setAttribute("pay_type",'yandex');
        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("pay_type",'yandex'));
    }
    else if(btn.hasClass('cash') && btn.prop("checked") == true)
    {
        $('#paybox').prop("checked", false);
        $('#yandex').prop("checked", false);
        // $('#pay_type').val('yandex');
        // document.getElementById("final_btn_purchase_for_client").setAttribute("pay_type",'cash');
        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("pay_type",'cash'));
    }
    else
    {
        // $('#pay_type').val('');
        // document.getElementById("final_btn_purchase_for_client").setAttribute("pay_type",'');
        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("pay_type",''));

    }

    if($('#pay_type').val() == '')
    {
        $('#final_btn_purchase_for_client').addClass('agree_block');
    }
    else
    {
        $('#final_btn_purchase_for_client').removeClass('agree_block');
    }
})

  $('#search').on('keyup',function(){
  $('#tbody_chosen_user_col').show();
  $('#tbody_user').show();
  value=$(this).val();
  $.ajax({
  type : 'get',
  url : '{{URL::to('search_client_ajax')}}',
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  data:{'search':value},
  success:function(data){
  $('#tbody_user').html(data.view);
  }
  });
  })
function get_user(row) {
  fill_user_data(row.getAttribute('user_id'))
  };
function fill_user_data(id){
  $.ajax({
  type : 'get',
  url : '{{URL::to('get_user_data')}}',
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  data:{'id':id},
  success:function(data){
    fill_input_data(data.user);
  }
  });
}
function fill_input_data(user){
  $('#tbody_chosen_user_col').show();
  document.getElementById("chosen_user_name").innerHTML = user.name;
  document.getElementById("chosen_user_last_name").innerHTML = user.last_name;
  document.getElementById("chosen_user_middle_name").innerHTML = user.middle_name;
  document.getElementById("chosen_user_city").innerHTML = user.city;
  document.getElementById("chosen_user_country").innerHTML = user.country;
  document.getElementById("chosen_user_email").innerHTML = user.email;
  document.getElementById("chosen_user_contacts").innerHTML = user.contacts;
  document.getElementById("chosen_user_job").innerHTML = user.job;
  document.getElementById("chosen_user_company").innerHTML = user.company;
  document.getElementById("chosen_user_work_type").innerHTML = user.work_type;
  // document.getElementById("chosen_user_status").innerHTML = user.status;
  if(user.status=='blocked'){
    $('#chosen_user_blocked').show();
  }
  else{
    $('#chosen_user_blocked').hide();
  }
  // document.getElementById("buy_for_exisiting_user").setAttribute("existing_user_name", user.name);
  // document.getElementById("buy_for_exisiting_user").setAttribute("existing_user_id", user.id);


  document.getElementById("purchase_details_name").innerHTML = user.name;
  document.getElementById("purchase_details_last_name").innerHTML = user.last_name;
  // document.getElementById("final_btn_purchase_for_client").setAttribute("user_id", user.id);
  // document.getElementById("final_btn_purchase_for_client").setAttribute("user_name", user.name);

  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("user_id", user.id));
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("user_name", user.name));



}

$('.final_btn_purchase_for_client_class').on('click', function (e) {


    let event_id = e.currentTarget.getAttribute("event_id");
    let user_name = e.currentTarget.getAttribute("user_name");
    let user_id = e.currentTarget.getAttribute("user_id");
    let ticket_event = e.currentTarget.getAttribute("event_name");
    let ticket_row = e.currentTarget.getAttribute("ticket_row");
    let ticket_col = e.currentTarget.getAttribute("ticket_col");
    let ticket_price = e.currentTarget.getAttribute("ticket_price");

    let promo_price_ = e.currentTarget.getAttribute("promo_price");
    let display_price = promo_price_ ? promo_price_ : ticket_price;

    let info_source = e.currentTarget.getAttribute("info_source");
    let type = e.currentTarget.getAttribute("type");
    let show = document.getElementById("show").checked;
    let pay_type =  e.currentTarget.getAttribute("pay_type");

    let promo = $('#with_promo').attr('data-promo');

    let text_type = type=='buy' ? 'покупку' : "бронь";
    
    @if($event->scheme)
      let place_choice = parseInt($('.place-choice').attr('data-without'));
      let place = $('.place-choice');
      let promotion = place.data('without');
      let swal_text = 'Мероприятие: '+ticket_event+" "+", ряд: "+ticket_row+", место: "+ticket_col+", цена: "+display_price+', узнал(а) о мероприятии от: '+info_source;
    @else
      let place_choice = parseInt($('#chosen_price').attr('full'));
      let place = 'Ali';
      let promotion = $('#chosen_price').attr('full');    
      let swal_text = 'Мероприятие: '+ticket_event+", "+" цена: "+display_price+', узнал(а) о мероприятии от: '+info_source;
    @endif




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
    else if(info_source.length<=0){
        Swal.fire({
        position: 'center',
        icon: 'info',
        title: 'Вы не заполнили пункт, откуда клиент узнал о мероприятии!',
        showConfirmButton: true,
        });
    }
    else if(user_name.length<=0){
        Swal.fire({
        position: 'center',
        icon: 'info',
        title: 'Вы не выбрали пользователя!',
        showConfirmButton: true,
        });
    }
    else
    {
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Подтвердить '+ text_type +' для '+user_name,
        text: swal_text,
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: 'Отмена',
        }).then((result) => {
        if(result.isConfirmed){
          send_buy_reserve_for_user(user_id,ticket_row,ticket_col,ticket_price,type,event_id,info_source,show,pay_type,promo,promotion);
        }
      })
    }
})

function send_buy_reserve_for_user(user_id,ticket_row,ticket_col,ticket_price,type,event_id,info_source,show,pay_type,promo,promotion){
  let ticket = null;
  let who = 'admin';
  let url = type=="buy"?'buy_ticket':'reserve_ticket';
  let text_type = type=='buy' ? 'покупку' : "бронь";
  $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });

  if(url=="buy_ticket"){
    $('.for_buy_id').val(user_id);
    $('.for_buy_row').val(ticket_row);
    $('.for_buy_column').val(ticket_col);
    $('.for_buy_price').val(ticket_price);
    $('.for_buy_type').val(type);
    $('.for_buy_event_id').val(event_id);
    $('.for_buy_info_source').val(info_source);
    $('.for_buy_show').val(show);
    $('.for_buy_ticket').val(ticket);
    $('.for_buy_pay_type').val(pay_type);
    $('.for_buy_who').val(who);
    $('.for_buy_promo').val(promo);
    $('.for_buy_promotion').val(promotion);
    $('#for_buy_form').submit();
  }else{
    $.ajax({
      type : 'post',
      url : '/'+url,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data:{
        'user_id':user_id,
        'row':ticket_row,
        'column':ticket_col,
        'price':ticket_price,
        'type':type,
        'event_id':event_id,
        'found':info_source,
        'show':show,
        "ticket": ticket,
        'pay_type':pay_type,
        'who':who,
        'promo':promo,
        'promotion':promotion,
      },
      success:function(data){
        $.unblockUI();

        if (data.status == 'success') {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title:'Выполнено!',
                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
            });
            setTimeout(() => window.location="/admin.details_view/{{$event->id}}");
            // $('#success_modal').modal('show');
            // btn.removeClass('loading');
        }
        else if (data.status == 'error') {
            if (data.check == 0) {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Слушатель уже покупал билет!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });
            }
            else if (data.check = 1) {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'У слушателя уже есть забронированный билет!',
                    text: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
                    showConfirmButton: true,
                });
            }
            // btn.removeClass('loading');
            // place.removeClass('place-choice');
        }


      }
    });
  }
}
$('#info_source').on('click', function (e) {
  // document.getElementById('final_btn_purchase_for_client').setAttribute('info_source',e.currentTarget.value);
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute('info_source',e.currentTarget.value));

});

   $('.save_new_user_form_btn').on('click', function (e) {
      let send_email=e.currentTarget.getAttribute("send_email")

      let name = document.getElementById("name").value;
      let last_name = document.getElementById("last_name").value;
      let middle_name = document.getElementById("middle_name").value;
      let country = document.getElementById("country").value;
      let city = document.getElementById("city").value;
      let email = document.getElementById("email").value;
      let contacts = document.getElementById("telephone").value;
      let job = document.getElementById("job").value;
      let company = document.getElementById("company").value;
      let work_type = document.getElementById("work_type").value;
      let other_work_type = document.getElementById("other_work_type").value;

      $.ajax({
        type : 'post',
        url: "/save_user_ajax",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data:{'name':name,
              'last_name':last_name,
              'middle_name':middle_name,
              'city':city,
              'country':country,
              'company':company,
              'email':email,
              'contacts':contacts,
              'job':job,
              'work_type':work_type,
              'other_work_type':other_work_type,
              'send_email':send_email,
        },
        success:function(data){
          Swal.fire({
          position: 'center',
          timer: 3000,
          icon: 'info',
          title: data.user.name +' был сохранен и выбран!',
          showConfirmButton: true,
          });

          fill_purchase_data(data.user.name,data.user.last_name,data.user.id);
        },
        error: function (err) {
          if (err.status == 422) { // when status code is 422, it's a validation issue
              // display errors on each form field
              var errors = JSON.parse(err.responseText.substr(1))
              let error_text='';
              $.each(errors.errors, function (i, error) {
                switch(i) {
                  case 'name':
                    error_text+="<li>"+error[0].replace("name","Имя")+"</li>";
                    break;
                  case 'last_name':
                    error_text+="<li>"+error[0].replace("last name", "Фамилия")+"</li>";
                    break;
                  case 'middle_name':
                    error_text+="<li>"+error[0].replace("middle name", "Отчество")+"</li>";
                    break;
                  case 'city':
                    error_text+="<li>"+error[0].replace("city", "Город")+"</li>";
                    break;
                  case 'country':
                    error_text+="<li>"+error[0].replace("country", "Страна")+"</li>";
                    break;
                  case 'company':
                    error_text+="<li>"+error[0].replace("company", "Компания")+"</li>";
                    break;
                  case 'email':
                    error_text+="<li>"+error[0]+"</li>";
                    break;
                  case 'contacts':
                    error_text+="<li>"+error[0].replace("contacts", "# телефона")+"</li>";
                    break;
                  case 'job':
                    error_text+="<li>"+error[0].replace("job", " должность")+"</li>";
                    break;
                  case 'work_type':
                    error_text+="<li>"+error[0].replace("work_type", "# деятельность")+"</li>";
                    break;
                  default:
                    error_text+="<li>"+error[0]+"</li>";

                  }

              });
              document.getElementById("error_modal_body").innerHTML =error_text
              $('#errorModal').modal('show');

          }
        }
      });
    })



function fill_purchase_data(user_name,user_last_name,user_id){
  document.getElementById("purchase_details_name").innerHTML =user_name;
  document.getElementById("purchase_details_last_name").innerHTML =user_last_name;

  // document.getElementById("final_btn_purchase_for_client").setAttribute("user_id", user_id);
  // document.getElementById("final_btn_purchase_for_client").setAttribute("user_name", user_name);


  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("user_id", user_id));
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("user_name", user_name));


}

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
    }
    else {
    $('.place').removeClass('place-choice');
    btn.addClass('place-choice');
    }
    var price = btn.data('parent3');
    var row = btn.data('parent');
    var column = btn.data('parent2');
    var format = btn.data('parent4');

    document.getElementById("chosen_row").innerHTML =Object.is(row, undefined) ? "" : row;
    document.getElementById("chosen_col").innerHTML =Object.is(column, undefined) ? "" : column;
    document.getElementById("chosen_price").innerHTML =Object.is(price, undefined) ? "" : price;

    // document.getElementById("final_btn_purchase_for_client").setAttribute("ticket_row", row);
    // document.getElementById("final_btn_purchase_for_client").setAttribute("ticket_col", column);
    // document.getElementById("final_btn_purchase_for_client").setAttribute("ticket_price", price);


  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("ticket_row", row));
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("ticket_col", column));
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("ticket_price", price));

    // document.getElementById("buy_for_new_user_form_btn").setAttribute("existing_user_ticket_row", row);
    // document.getElementById("buy_for_new_user_form_btn").setAttribute("existing_user_ticket_col", column);
    // document.getElementById("buy_for_new_user_form_btn").setAttribute("existing_user_ticket_price", price);

    document.getElementById("purchase_details_row").innerHTML = row;
    document.getElementById("purchase_details_column").innerHTML = column;
    document.getElementById("purchase_details_price").innerHTML = price;
    // document.getElementById("hidden_input_row").value = row;
    // document.getElementById("hidden_input_collumn").value = column;
    // document.getElementById("hidden_input_price").value = price;

})
    </script>


    @endpush
@endsection
