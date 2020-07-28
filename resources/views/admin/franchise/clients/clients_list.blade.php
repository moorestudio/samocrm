@extends('layouts.admin_app')
@section('content')

<div class="container">
@if(request()->path()!=='franchise.clients')
    <?php 
        $bonus_from_part = 0;
    ?>
@endif
@if(count($clients) || count($lost_clients) || count($bonus_from_part) || 1) {{-- 1 test --}}

    @if(request()->path()==='franchise.clients')
    <div class="d-flex pb-2">
        <p class="main-title">Ваши продажи</p>
    </div>
    @else
    <div class="d-flex pb-2">
        <p class="main-title">Продажи партнера: {{$franch->fullName()}}</p>
    </div>    
    @endif
    <div class="d-flex pb-5">
        <div class="col px-0">
            <ul class="nav nav-tabs d-flex flex-wrap" id="myTab" role="tablist" style="border-bottom:1px solid #E1E8F3;">
                <li class="nav-item col-lg-auto col-12 tabber pl-0">
                    <a class="nav-link active" id="main_pf-tab" data-toggle="tab" href="#main_pf" role="tab" aria-controls="home" aria-selected="true">
                    <img class="mr-1" src="{{ asset('images/i.svg') }}" alt="" style="vertical-align: text-top;">
                    <span>Общий отчет</span>
                </a>
                </li>
                <li class="nav-item col-lg-auto col-12 tabber">
                    <a class="nav-link" id="clients_pf-tab" data-toggle="tab" href="#clients_pf" role="tab" aria-controls="profile" aria-selected="false">
                    <img class="mr-1" src="{{ asset('images/friends.svg') }}" alt="" style="vertical-align: text-top;">
                    
                    @if(request()->path()==='franchise.clients')
                        <span>Ваши слушатели</span>
                    @else
                        <span>Cлушатели данного партнера</span>
                    @endif
                    </a>
                </li>
                <li class="nav-item col-lg-auto col-12 tabber pr-0">
                    <a class="nav-link" id="lost_clients_pf-tab" data-toggle="tab" href="#lost_clients_pf" role="tab" aria-controls="contact" aria-selected="false">
                    <img class="mr-1" src="{{ asset('images/chart-b.svg') }}" alt="" style="vertical-align: text-top;">
                    <span>Прошлые продажи</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="main_pf" role="tabpanel" aria-labelledby="main_pf">
            <?php
                $total_clients = 0;
                $total_income = 0;
            ?>
            @foreach($financials as $financial)

            <?php
                $total_clients = $total_clients + $financial->franchise_clients_from_event($financial,$franch);
                $total_income = $total_income + $financial->franchise_percent_from_event($financial,$franch);
            ?>
            @endforeach

                <div class="d-flex mb-4" style="height:90px;">
                    <div class="col-lg-3 col-md-4 col-sm-6 pl-0">
                        <div class="report-stats-block px-3 py-2 mr-lg-1 mr-0" style="border-color: #2B98E7;">
                            <div class="d-flex align-items-center h-100">
                                <img class="mr-2" src="{{ asset('images/addUser.svg') }}" alt="">
                                <div class="d-flex flex-column">
                                    <p class="report_stat_title mb-1" >Общее кол-во <br> клиентов:</p>     
                                    <p class="report_stat_value mb-0">{{count($clients)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6" >
                        <div class="report-stats-block px-3 py-2 mr-lg-1 mr-0" style="border-color: #FFA05B;">
                            <div class="d-flex align-items-center h-100">
                                <img class="mr-2" src="{{ asset('images/cost2.svg') }}" alt="">
                                <div class="d-flex flex-column align-items-between">
                                    <p class="report_stat_title mb-1">Итого выручка</p>     
                                    <p class="report_stat_value mb-0">
                                        @foreach($franch_percent_values as $ids=> $franch_per)
                                            <span>{{$franch_per}}-{{\App\CurrencyNames::find($ids)->currency}}</span><br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->path()==='franchise.clients')
                    @if(Auth::user()->role_id==4 || Auth::user()->role_id==6)
                    <div class="col-lg-3 col-md-4 col-sm-6" >
                        <div class="report-stats-block px-3 py-2 mr-lg-1 mr-0" style="border-color: #1FBE06;">
                            <div class="d-flex align-items-center h-100">
                                <img class="mr-2" src="{{ asset('images/money3.svg') }}" alt="">
                                <div class="d-flex flex-column justify-content-between">
                                    <p class="report_stat_title mb-1" >Итого бонусы <br> от продаж партнеров</p>     
                                    <p class="report_stat_value mb-0">
                                        @foreach($bonus_from_part as $id=> $bonus)
                                            <span>{{$bonus}}-{{\App\CurrencyNames::find($id)->currency}}</span><br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                </div>


<div class="col-12 px-0">
    <div class="d-flex  report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Названия мероприятий</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Дата проведения мероприятий</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Клиентов на мероприятии</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Доход от мероприятия</p>
        </div>
    </div>
    @foreach($financials as $financial)
        @if($financial->franchise_clients_from_event($financial,$franch)>=1)
        <?php 
            $event_obj = \App\Event::find($financial->event_id);
            $event_curr = $event_obj->currency; 
        ?>
        <div class="d-flex special-card report-card">
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{$event_obj->title}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{\App\Event::find($financial->event_id)->date}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $financial->franchise_clients_from_event($financial,$franch) }}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $financial->franchise_percent_from_event($financial,$franch) }} {{\App\CurrencyNames::find($event_curr)->currency}}</p>
            </div>
        </div>
        @endif
    @endforeach
</div>



            </div>

            <div class="tab-pane fade show" id="clients_pf" role="tabpanel" aria-labelledby="clients_pf-tab">




<div class="col-12 px-0">
    <div class="d-flex report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">ФИО</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">E-mail</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Номер телефона</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Город</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Кол. куп. билетов</p>
        </div>
    </div>
    @forelse($clients as $client)
        <div class="d-flex special-card report-card">
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $client->fullName() }}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $client->email }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{ $client->contacts }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{ $client->city }}</p>
            </div>
            <div class="col-lg-2 col-12">
            <?php
                $count_events = count($client->tickets->where('type','buy'));
            ?>
            <p class="mb-0">{{$count_events}}</p>
            </div>
        </div>
    @empty
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Нет слушателей</span>
        </div>
    @endforelse
</div>
   </div>  
   <div class="tab-pane fade show" id="lost_clients_pf" role="tabpanel" aria-labelledby="lost_clients_pf-tab">

<div class="col-12 px-0">
    <div class="d-flex report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">ФИО</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">E-mail</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Номер телефона</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Город</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Кол. куп. билетов</p>
        </div>
    </div>
    @forelse($lost_clients as $client)
        <div class="d-flex special-card report-card">
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $client->fullName() }}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $client->email }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{ $client->contacts }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">{{ $client->city }}</p>
            </div>
            <div class="col-lg-2 col-12">
            <?php
                $count_events = count($client->tickets->where('type','buy'));
            ?>
            <p class="mb-0">{{$count_events}}</p>
            </div>
        </div>
    @empty
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Нет продаж</span>
        </div>
    @endforelse
</div>

        </div>      
        </div>

</div>
@else

    @if(Auth::user()->role_id == 5)
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">У вас нет клиентов</span>
        </div>
    @else
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Продаж еще не было</span>
        </div>
    @endif
@endif

@endsection