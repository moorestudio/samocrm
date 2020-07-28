@if($financial->franch_percent)
    <div class="col-12">
        <div class="row mb-3 align-items-center report-header">
            <div class="col-lg-2 col-12 pr-1 pl-2">
                <p class="mb-0 title">ФИО продавца</p>
            </div>
            <div class="col-lg-1 col-12 px-1">
                <p class="mb-0 title">Роль продавца</p>
            </div>
            <div class="col-lg-2 col-12 px-1">
                <p class="mb-0 title">Общая сумма продаж</p>
            </div>
            <div class="col-lg-1 col-12 px-1">
                <p class="mb-0 title">Доля продавца</p>
            </div>
            <div class="col-lg-2 col-12 px-1">
                <p class="mb-0 title">Выплата продавцу</p>
            </div>
            <div class="col-lg-2 col-12 px-1">
                <p class="mb-0 title">Доля куратора продавца</p>
            </div>
            <div class="col-lg-2 col-12 px-1">
                <p class="mb-0 title">Выплата куратору продавца</p>
            </div>
        </div>
        @foreach($subtotals as $id=>$sub)
            <div class="row align-items-center mb-1 report-card">
                <div class="col-lg-2 col-12 pr-1 pl-2">
                    <p class="mb-0">{{$sub[5]}}</p>
                </div>
                <div class="col-lg-1 col-12 px-1">
                    <p class="mb-0">{{\App\Role::find($sub[4])->display_name}}</p>
                </div>
                <div class="col-lg-2 col-12 px-1">
                    <p class="mb-0">{{($sub[0]+$sub[2])/$sub[1]*100}} {{$event_currency}}</p>
                </div>
                <div class="col-lg-1 col-12 px-1">
                    <p class="mb-0">{{$sub[1]}} %</p>
                </div>
                <div class="col-lg-2 col-12 px-1">
                    <p class="mb-0">{{$sub[0]}} {{$event_currency}}</p>
                </div>
                <div class="col-lg-2 col-12 px-1">
                    <p class="mb-0">{{$sub[3]}} %</p>
                </div>
                <div class="col-lg-2 col-12 px-1">
                    <p class="mb-0">{{$sub[2]}} {{$event_currency}}</p>
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
