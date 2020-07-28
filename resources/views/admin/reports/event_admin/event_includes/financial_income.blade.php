@if($financial->income)
<div class="col-12">
    <div class="row mb-1 sticky-top report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 font-weight-bold">ФИО слушателя</p>
        </div>
          <div class="col-lg-3 col-12">
            <p class="mb-0 font-weight-bold">ФИО продавца</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 font-weight-bold">Роль продавца</p>
        </div>      
        <div class="col-lg-2 col-12">
            <p class="mb-0 font-weight-bold">Стоимость билета</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 font-weight-bold">Дата покупки</p>
        </div>
    </div>
    @foreach($financial->income as $income)
        @php 
            $ticket = \App\Ticket::find($income['ticket_id']);
        @endphp
        <div class="row align-items-center mb-1 report-card">
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{$income['user_name']}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{$ticket->seller_name !=null ? $ticket->seller_name : 'Не прикреплен'}}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{$ticket->seller_role_id !=null ? \App\Role::find($ticket->seller_role_id)->display_name : '-'}}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{$income['sum']}} {{$event_currency}} <span style="font-size: 10px;">{{isset($income['discount']) && $income['discount'] == 1 ? '(Цена со скидкой)': ''}}</span></p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{\Carbon\Carbon::parse($income['date'])}}</p>
            </div>
        </div>
    @endforeach
</div>
@else
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">На это мероприятие доход не поступал</span>
    </div>
@endif