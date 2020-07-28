@extends('layouts.admin_app')
@section('content')
<div class="container px-0">
  <div class="d-flex justify-content-between about-course p-2 mb-2">
    <div class="d-flex align-items-center">
      <h3 class="mb-0" style="font-size: 18px;">{{$data['event']->title}}</h3>
    </div>
    <div class="d-flex align-items-center">
      <a class="exportBtn" href="/export_attendance/{{$data['event']->id}}"><img class="mr-1" src="{{asset('images/bx_bx-export.svg')}}" alt=""> Экспорт в excel</a>
      <a class="exportBtn ml-1" href="/export_attendance_xml/{{$data['event']->id}}"><img class="mr-1" src="{{asset('images/bx_bx-export.svg')}}" alt=""> Экспорт в xml</a>
    </div>
 </div>
 <div>
   <div class="d-flex flex-wrap px-0 mb-1 report-header2">
     <div class="col-6 d-flex no-marg pl-0" style="padding-left: .15rem!important;padding-right: .0rem!important;    font-size: 14px;">
       <div class="col-lg-3 col-12 pl-0 pr-1 no-marg"><div class="bg-whi-pdd2"><p class="mb-0 text-black font-weight-medium bg-whi-pdd no-marg">Дата<br> начало</p>
        <div class="d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$data['event']->normalDate()}}</p>
         </div></div>
      </div>
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Дата<br> окончания</p>
       <div class="d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$data['event']->normalEndDate()}}</p>
     </div></div>
      </div>
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Общ. кол-во<br> билетов</p>
       <div class="d-lg-block d-none">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$all_ticket = intval($data['event']->client_count)}}</p>
     </div></div>
      </div>
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Продано<br> билетов</p>
       <div class=" d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$sold_ticket = count($data['joined_table'])}}</p>
     </div></div>
      </div>
      </div>
      <div class="col-6 d-flex pl-1 pr-0" style="padding-left: .0rem!important;padding-right: .15rem!important;  font-size: 14px;">
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Не продано<br> билетов</p>
       <div class=" d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$all_ticket - $sold_ticket}}</p>
     </div></div>
      </div>
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2 d-flex flex-column justify-content-between h-100"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Выручка</p>
       <div class=" d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$data['joined_table']->sum('ticket_price')}}</p>
     </div></div>
      </div>
       <!-- <div class="col-lg-2 col-12 px-1"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Спикер</p>
       <div class="d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link of-elipsis">{{$data['event']->mentor}}</p>
     </div>
      </div> -->
       <div class="col-lg-3 col-12 px-1"><div class="bg-whi-pdd2 d-flex flex-column justify-content-between h-100"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Город</p>
       <div class=" d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$data['event']->city}}</p>
     </div></div>
      </div>
       <div class="col-lg-3 col-12 pl-1 pr-0 bg-whi-pdd3">
       <div class="bg-whi-pdd2 d-flex flex-column justify-content-between h-100"><p class="mb-0 text-black font-weight-medium bg-whi-pdd">Адрес</p>
       <div class=" d-lg-block d-none ">
         <p class="mb-0 list-link pdd5left font-weight-bold">{{$data['event']->address}}</p>

     </div></div>
      </div>
   </div>
   </div>
   <div class="d-flex container px-0 my-4">
     <div class="report-header col-6 d-flex justify-content-between mr-2" style="height: 55px;">
       <p class="mb-0">Общее кол-во<br> пришедших клиентов:</p>
       <p class="mb-0 num_come mr-3">{{$data['event']->come()}}</p>
     </div>
     <div class="report-header col-6 d-flex justify-content-between" style="height: 55px;">
       <p class="mb-0">Общее кол-во<br> не пришедших клиентов:</p>
       <p class="mb-0 num_come mr-4">{{$data['event']->notCome()}}</p>
     </div>
   </div>
   <!-- <div class="row mb-1 report-card">
     <div class="col-2 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['event']->date}}</p>
     </div> -->
     <!-- <div class="col-2 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['event']->end_date}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$all_ticket = intval($data['event']->client_count)}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$sold_ticket = count($data['joined_table'])}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$all_ticket - $sold_ticket}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['joined_table']->sum('ticket_price')}}</p>
     </div> -->
     <!-- <div class="col-2 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['event']->mentor}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['event']->city}}</p>
     </div> -->
     <!-- <div class="col-1 d-lg-block d-none bg-whi-pdd2">
         <p class="mb-0 list-link">{{$data['event']->address}}</p>
     </div> -->
   <!-- </div> -->
 <!-- </div> -->
 <!-- <div class="d-flex justify-content-end" style="    padding-top: 10%;">
   <input type="hidden" name="" value="{{$data['event']->id}}" id="event_id">
   <select name="sortByVistit" id="sortByVistit">
     <option value="">Все</option>
     <option value="done">По пришедим</option>
     <option value="buy">По не пришедим</option>
   </select>
 </div> -->
 <input type="hidden" name="" value="{{$data['event']->id}}" id="event_id">
  <div class="report-header d-flex mb-1 event report-header mt-4">
        <div class="col-lg-5 col-12"><p class="mb-0 text-black font-weight-medium">ФИО</p></div>
        <div class="col-lg-3 col-12"><p class="mb-0 text-black font-weight-medium">Статус</p></div>
        <div class="col-lg-4 col-12 d-flex justify-content-end align-items-center attend">
          <p class="mb-0 mr-2 disabled-text">Сортировать по</p>
          <select class="btn btn-warning m-0 p-0" name="sortByVistit" id="sortByVistit">
            <option value="">Всем</option>
            <option value="done">По пришедим</option>
            <option value="buy">По не пришедим</option>
          </select>
        </div>
  </div>
  <div class="changeAble">
    @forelse($data['joined_table'] as $users)

    <div class="d-flex special-card event report-card">
      <div class="col-5 d-lg-flex d-none">
          <p class="mb-0 list-link of-elipsis">{{$users->name}} {{$users->last_name}} {{$users->middle_name}}</p>
      </div>
      <div class="col-7 d-lg-flex d-none">
            @if($users->type==="buy")
              <button user_id={{$users->id}} ticket_id={{$users->ticket_id}} class="change_type_btn attend border-0">Не участвовал <img class="mx-2" src="{{asset('images/attend-not-check.svg')}}" alt=""> <span class="disabled-text">Участвовал</span></button>
            @elseif($users->type==="done")
              <button user_id={{$users->id}} ticket_id={{$users->ticket_id}} class="change_type_btn attend border-0"><span class="disabled-text">Не участвовал</span> <img  class="mx-2" src="{{asset('images/attend-check.svg')}}" alt=""><span class="active-text">Участвовал</span></button>
            @endif
      </div>
    </div>
    @empty
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/server.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>
    @endforelse
  </div>
@push('scripts')
    <script>
      $('#sortByVistit').on('change',function(){
        let val = $(this).val();
        let id = $("#event_id").val();

        axios.post('/sortByVistit',{
          'val':val,
          'id':id,
        }).then(function(response){
          $('.changeAble').html(response.data.view);
        });
      });
    </script>
@endpush

@endsection
