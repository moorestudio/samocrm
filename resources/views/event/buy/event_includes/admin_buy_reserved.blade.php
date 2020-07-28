@extends('layouts.app')
@section('content')  
<div class="container mt-4">
    <div class="position-relative">

<div class="row justify-content-center">
  <div class="col-12 text-center py-4 d-flex align-items-center">
      <img src="{{asset('images/cart.svg')}}" alt="">
      <p class="secondary-title mb-0 ml-3">Детали покупки</p>
  </div> 


  <div class="col-12 p-0">
    <div class="table-responsive about-course">
      <div class="d-flex p-5" id="tbody_purchase_details">
        <div class="d-flex flex-column col-2">
          <div class="title">Имя слушателя</div>
          <div class="description" id="purchase_details_name">{{$ticket_user->name}}</div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Фамилия слушателя</div>
          <div class="description" id="purchase_details_last_name">{{$ticket_user->last_name}}</div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Мероприятие</div>
          <div class="description" id="purchase_details_event">{{ $event->title }}</div>
        </div>
        @if($event->scheme)
        <div class="d-flex flex-column col-2">
          <div class="title">Ряд</div>
          <div class="description" id="purchase_details_row">{{$ticket->row}}</div>
        </div>
        <div class="d-flex flex-column col-2">
          <div class="title">Место</div>
          <div class="description" id="purchase_details_column">{{$ticket->column}}</div>
        </div>
        @endif
        <div class="d-flex flex-column col-2">
          <div class="title">Цена</div>
          <div class="description"><span id="purchase_details_price">{{$ticket->ticket_price}}</span>{{$event_currency}}</div>
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
<!-- 
  <div class="d-flex flex-wrap px-2 py-5 col-12" style="background: #FFFFFF;border-radius: 5px;">
    <div class="col-lg-2 col-6">
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
<!--     <div class="col-12 d-flex mt-4">
      <button class="btn btn-success final_btn_purchase_for_client_class" id="final_btn_purchase_for_client_buy" event_id="{{$event->id}}" event_name="{{$event->title}}" info_source="" user_name="{{$ticket_user->name}}" ticket_row="{{$ticket->row}}" ticket_col="{{$ticket->column}}" ticket_price="{{$ticket->ticket_price}}" user_id="{{$ticket_user->id}}" type="buy">Выписать и отправить билет!</button>
    </div> -->

        <div class="col-12 d-flex mt-4">
      <button class="btn btn-success final_btn_purchase_for_client_class" id="final_btn_purchase_for_client_buy" event_id="{{$event->id}}" event_name="{{$event->title}}" info_source="" user_name="{{$ticket_user->name}}" ticket_row="{{$ticket->row}}" ticket_col="{{$ticket->column}}" ticket_price="{{$ticket->ticket_price}}" user_id="{{$ticket_user->id}}" pay_type="cash" type="buy">Выписать и отправить билет!</button>
    </div>
  </div>


</div>
@php
@endphp
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
      <input type="hidden" id="0_format_id" name="format_id" value="{{$ticket->ticket_format}}">
      <button type="submit" class="for_buy d-none"></button>
  </form>

    </div>
</div>
@push('scripts')
<script src="{{ asset('js/blockUI.js') }}"></script>
<script>




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
        $('#cart').prop("checked", false);
        $('#cash').prop("checked", false);
        // $('#pay_type').val('yandex');
        // document.getElementById("final_btn_purchase_for_client").setAttribute("pay_type",'yandex');
        document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute("pay_type",'yandex'));
    }
    else if(btn.hasClass('cash') && btn.prop("checked") == true)
    {
        $('#cart').prop("checked", false);
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


$('.final_btn_purchase_for_client_class').on('click', function (e) {
    let event_id = e.currentTarget.getAttribute("event_id");
    let user_name = e.currentTarget.getAttribute("user_name");
    let user_id = e.currentTarget.getAttribute("user_id");
    let ticket_event = e.currentTarget.getAttribute("event_name");
    let ticket_row = e.currentTarget.getAttribute("ticket_row");
    let ticket_col = e.currentTarget.getAttribute("ticket_col");
    let ticket_price = e.currentTarget.getAttribute("ticket_price");
    let info_source = e.currentTarget.getAttribute("info_source");
    let type = e.currentTarget.getAttribute("type");
    let show = document.getElementById("show").checked;
    let pay_type =  e.currentTarget.getAttribute("pay_type");

    let text_type = type=='buy' ? 'покупку' : "бронь";
    //
    if(info_source.length<=0){
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
        text: 'Мероприятие: '+ticket_event+" "+", ряд: "+ticket_row+", место: "+ticket_col+", цена: "+ticket_price+'. И узнал о мероприятии от: '+info_source,
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: 'Отмена',
        }).then((result) => {
        if(result.isConfirmed){
          send_buy_reserve_for_user(user_id,ticket_row,ticket_col,ticket_price,type,event_id,info_source,show,pay_type);
        }
      })
    }
})
function send_buy_reserve_for_user(user_id,ticket_row,ticket_col,ticket_price,type,event_id,info_source,show,pay_type){
  let ticket = {{$ticket->id}};
  let who = 'admin';
  // let url = type=="buy"?'buy_ticket':'reserve_ticket';
  // let text_type = type=='buy' ? 'покупку' : "бронь";
  // $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });

  
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
  // $('.for_buy_promo').val(promo);
  // $('.for_buy_promotion').val(promotion);
  $('#for_buy_form').submit();
  // if(url=="buy_ticket"){

  // }else{
    // $.ajax({
    //   type : 'post',
    //   url : '/'+url,
    //   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //   data:{
    //     'user_id':user_id,
    //     'row':ticket_row,
    //     'column':ticket_col,
    //     'price':ticket_price,
    //     'type':type,
    //     'event_id':event_id,
    //     'found':info_source,
    //     'show':show,
    //     "ticket": ticket,
    //     'pay_type':pay_type,
    //     'who':who,
    //   },
    //   success:function(data){
    //     $.unblockUI();

    //     if (data.status == 'success') {
    //         Swal.fire({
    //             position: 'center',
    //             icon: 'success',
    //             title:'Выполнено!',
    //             text: 'Список билетов' ,
    //         });
    //         // $('#success_modal').modal('show');
    //         // btn.removeClass('loading');
    //     }
    //     else if (data.status == 'error') {
    //         if (data.check == 0) {
    //             Swal.fire({
    //                 position: 'center',
    //                 icon: 'info',
    //                 title: 'Слушатель уже покупал билет!',
    //                 // description: ' Каждый пользователь может купить лишь 1 билет ' ,
    //                 // showConfirmButton: true,
    //             },setTimeout(() => window.location="/admin.details_view/{{$event->id}}"));

    //         }
    //         else if (data.check = 1) {
    //             Swal.fire({
    //                 position: 'center',
    //                 icon: 'info',
    //                 title: 'У слушателя уже есть забронированный билет!',
    //                 text: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
    //                 showConfirmButton: true,
    //             });
    //         }
    //         // btn.removeClass('loading');
    //         // place.removeClass('place-choice');
    //     }
    //   }
    // });
  // }


}
$('#info_source').on('click', function (e) {
  // document.getElementById('final_btn_purchase_for_client').setAttribute('info_source',e.currentTarget.value);
  document.querySelectorAll(".final_btn_purchase_for_client_class").forEach(btn => btn.setAttribute('info_source',e.currentTarget.value));

});


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

    document.getElementById("purchase_details_row").innerHTML = row;
    document.getElementById("purchase_details_column").innerHTML = column;
    document.getElementById("purchase_details_price").innerHTML = price;

})
    </script>

    @endpush
@endsection
