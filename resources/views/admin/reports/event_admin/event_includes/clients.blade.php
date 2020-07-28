<?php
$agent = new \Jenssegers\Agent\Agent();
    if (!isset($type))
        {
            $type = 1;
        }

               
$event_currency = \App\CurrencyNames::find($event->currency)->currency;
               
?>
<div class="col-12">
    @if($type == 1 || $type == 2 )
<div class="d-flex report-header">
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">ФИО слушателя</p>
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">Тариф</p>
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">Сумма</p>
    </div>
    <div class="col-lg-2 col-12">
        @if($type == 1)
            <p class="mb-0 title">Дата покупки</p>
        @elseif( $type == 2 )
            <p class="mb-0 title">Дата брони</p>
        @endif
        
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">ФИО продавца</p>
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">Статус</p>
    </div>
</div>
    @elseif($type == 3)
        <div class="d-flex report-header">
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">ФИО слушателя</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Тариф</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Сумма</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Причина возврата</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Дата возврата</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">ФИО продавца</p>
            </div>
        </div>
        @elseif($type == 4)
        <div class="d-flex flex-wrap mb-4">
            <div class="col-12 px-0">
                <div class="d-flex" style="height:91px;">
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color: #049827;">
                            <div class="d-flex align-items-end">
                                <img class="img-fluid" src="{{ asset('images/report/cost1.svg') }}" alt="">
                                <?php
                                    $count_buy = \App\Ticket::where('event_id', $event->id)->where('type','buy')->get();
                                    $count_buy = count($count_buy);
                                ?>
                                <div class="d-flex flex-column">
                                    <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">
                                    Продано всего</p>
                                    <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{$count_buy}}
                                    шт
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color: #E83030;">
                            <div class="d-flex align-items-end">
                                <img class="img-fluid" src="{{ asset('images/report/return.svg') }}" alt="">
                                <?php
                                $count_return = \App\Ticket::where('event_id', $event->id)->where('type','return')->get();
                                $count_return = count($count_return);
                                ?>
                                <div class="d-flex flex-column">
                                <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">Возвращено всего</p>
                                   <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{$count_return}}
                                    шт</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color:#A6ACBE;">
                            <div class="d-flex align-items-end">
                                <img class="img-fluid" src="{{ asset('images/report/calendar1.svg') }}" alt="">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">Забронирова-нных мест</p>
                                    <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{count(\App\Ticket::where('event_id', $event->id)->where('type','reserve')->get())}}
                                        шт
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color: #F38230;">
                            <div class="d-flex align-items-end">
                                <?php
                                    $count_done = \App\Ticket::where('event_id', $event->id)->where('type','done')->get();
                                    $count_done = count($count_done);
                                ?>
                                <img class="img-fluid" src="{{ asset('images/report/coin1.svg') }}" alt="">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">Пришло всего</p>
                                    <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{$count_done}}
                                    чел</p>
                                </div>
                        </div>
                        </div>
                    </div>
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color: #E95A5A;">
                            <div class="d-flex align-items-end">
                                <img class="img-fluid" src="{{ asset('images/report/close3.svg') }}" alt="">
                                <?php
                                    $count_false = \App\Ticket::where('event_id', $event->id)->where('type','false')->get();
                                    $count_false = count($count_false);
                                ?>
                                <div class="d-flex flex-column">
                                    <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">Не пришло всего</p>
                                    <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{$count_false}}
                                    чел</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="report-stats-parent p-0 col">
                        <div class="report-stats-block p-3 mr-lg-3 mr-0" style="border-color: #2B98E7;">
                            <div class="d-flex align-items-end">
                                <?php
                                    $count_buy = \App\Ticket::where('event_id', $event->id)->where('type','buy')->get();
                                    $count_buy = count($count_buy);
                                ?>
                                <img class="img-fluid" src="{{ asset('images/report/seat1.svg') }}" alt="">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-light mb-0 ml-2" style="font-size: 14px; color: #333333; line-height: 100%;">Свободных мест</p>
                                    <p class="font-weight-bold mb-0 ml-2" style="font-size: 18px; color: #333333; line-height: 100%;">{{$event->free_seats()}}
                                    шт</p>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                     <div class="report-stats-parent p-1" style="width: 16%;">
                        <div class="report-stats-block p-3" style="border-color: #9358AC;">
                            <div class="d-flex align-items-end">
                                <img class="img-fluid" src="{{ asset('images/dis.png') }}" alt="">
                                <p class="font-weight-light mb-0 ml-2"
                                   style="font-size: 14px; color: #333333; line-height: 100%;">Скидки на сумму:</p>
                            </div>
                            <p class="font-weight-bold mb-0 mt-3"
                               style="font-size: 18px; color: #333333; line-height: 100%;">
                            <?php
                                $franchise_discount = 0;
                                $financial = \App\Financial::where('event_id', $event->id)->first();
                                if ($financial->discount != null){
                                foreach ($financial->discount as $discount)
                                    {
                                        if ($discount['franch_id'] == \Illuminate\Support\Facades\Auth::user()->id)
                                            {
                                                $franchise_discount = $franchise_discount + $discount['discount'];
                                            }
                                    }
                                }
                            ?>
                                {{ $franchise_discount }} {{$event_currency}}
                            </p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="d-flex report-header">
            <div class="col-lg-3 col-12">
                <p class="mb-0 title">ФИО слушателя</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Статус</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Способ оплаты</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0 title">Сумма</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Дата</p>
            </div>
        </div>
    @endif
@if($type == 1  && !count($tickets->whereIn('type',['buy','done'])) || $type == 2 && !count($tickets->where('type','reserve')) || $type == 3 && !count($tickets->where('type','return')))
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>
@endif
@foreach($tickets as $ticket)
    @if($type == 1 && in_array($ticket->type,['buy','done']))
        <div class="d-flex special-card report-card" id="buy-{{$ticket->id}}">
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $ticket->client_name }}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $event->eventFormat($event, $ticket->ticket_format) }}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $ticket->ticket_price }} {{$event_currency}}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $ticket->created_at }}
                </p>
            </div>

            <?php 
                $franch_name = \App\User::find(\App\User::find($ticket->user_id)->franchise_id);

            ?>      
            
           <div class="col-lg-2 col-12">
                <p class="mb-0">
                    @if(isset($ticket->seller_name))
                        {{$ticket->seller_name}}-
                        {{\App\Role::find($ticket->seller_role_id)->display_name}}
                    @else
                        Не прикреплен    
                    @endif
                </p>
            </div>

            @if($type == 1 && $ticket->type == 'buy')
                <div class="col-lg-2 col-12 pt-lg-0 pt-3 text-lg-center text-left return-confirm" style="cursor: pointer;" data-id="{{ $ticket->id }}">
                    @if(!$agent->isPhone())
                    <i class="far fa-times-circle fa-lg" data-toggle="tooltip" title="Совершить возврат"></i>
                    @else
                        <i class="far fa-times-circle fa-lg"></i>
                    @endif
                </div>

            @elseif($ticket->type == 'done')
                <div class="col-lg-2 col-12 pt-lg-0 pt-3 text-lg-center text-left" style="cursor: pointer;">
                    <p class="mb-0">Участвовал</p>
                </div>
            @endif

        </div>

    @elseif($type == 2 && $ticket->type == 'reserve')
        <div class="d-flex special-card report-card" id="buy-{{$ticket->id}}">
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ \App\User::find($ticket->user_id)->fullName() }}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $event->eventFormat($event, $ticket->ticket_format) }}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $ticket->ticket_price }} {{$event_currency}}
                </p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0">
                    {{ $ticket->created_at }}
                </p>
            </div>

            <?php 
                $franch_name = \App\User::find(\App\User::find($ticket->user_id)->franchise_id);

            ?>



                    @if(isset($franch_name))
                    <div class="col-lg-2 col-12">
                        <p class="mb-0">
                            {{$franch_name->fullname()}}

                        </p>
                    </div>
                    <div class="col-lg-2 col-12">
                        <p class="mb-0">
                            {{\App\Role::find($franch_name->role_id)->display_name}}

                        </p>
                    </div>
                    @else
                    <div class="col-lg-2 col-12">
                        <p class="mb-0">
                            Не прикреплен 
                        </p>
                    </div>
                    <div class="col-lg-2 col-12">
                        <p class="mb-0">
                            - 
                        </p>
                    </div>      
                    @endif

            @if($type == 1)
                <div class="col-lg-2 col-12 pt-lg-0 pt-3 text-lg-center text-left return-confirm" style="cursor: pointer;" data-id="{{ $ticket->id }}">
                    @if(!$agent->isPhone())
                    <i class="far fa-times-circle fa-lg" data-toggle="tooltip" title="Совершить возврат"></i>
                    @else
                        <i class="far fa-times-circle fa-lg"></i>
                    @endif
                </div>
            @endif
        </div>

        @elseif($type == 3 && $ticket->type == 'return')
            <div class="d-flex special-card report-card">
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ \App\User::find($ticket->user_id)->fullName() }}
                    </p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ $event->eventFormat($event, $ticket->ticket_format) }} {{$event_currency}}
                    </p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ $ticket->ticket_price }} {{$event_currency}}
                    </p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0" data-toggle="tooltip" title="{{$ticket->comment}}">
                        {{ \Illuminate\Support\Str::limit($ticket->comment, 10, $end='...')  }}
                    </p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ $ticket->updated_at }}
                    </p>
                </div>

               <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        <?php 
                            $franch_name = \App\User::find(\App\User::find($ticket->user_id)->franchise_id);

                        ?>
                        @if(isset($franch_name))
                            {{$franch_name->fullname()}}
                        @else
                            Не прикреплен    
                        @endif
                    </p>
                </div>
            </div>
        @elseif($type == 4)
            <div class="d-flex special-card report-card">
                <div class="col-lg-3 col-12">
                    <p class="mb-0">
                        {{ \App\User::find($ticket->user_id)->fullName() }}
                    </p>
                </div>
                <div class="col-lg-2 col-12 text-center">
                    @if($ticket->type != 'done' && $ticket->type != 'return' && $ticket->type != 'false' && $ticket->type != 'reserve')
                        <div class="client_status" style="background-color: #E1EEFF;color:#189DDF">
                            Купленный
                        </div>
                    @elseif($ticket->type == 'done')
                        <div class="client_status" style="background-color: #96D98B;color: #FFFFFF;">
                            Пришел
                        </div>
                    @elseif($ticket->type == 'return')
                        <div class="client_status" style="background-color: #F2D3D3;color: #EF6E6E;">
                            Возврат
                        </div>
                    @elseif($ticket->type == 'reserve')
                        <div class="client_status" style="background-color: #E1E8F3;color: #1E2433;">
                            Бронь
                        </div>    
                    @elseif($ticket->type == 'false')
                        <div class="client_status" style="background-color: #FFCAA4;color: #FFFFFF;">
                            Не пришел
                        </div>
                    @endif
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ $ticket->pay_type=="cart" ? "Карта" :  $ticket->pay_type}}
                    </p>
                </div>
                <div class="col-lg-3 col-12">
                    <p class="mb-0">
                        {{ $ticket->ticket_price }} {{$event_currency}}
                    </p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">
                        {{ $ticket->updated_at }}
                    </p>
                </div>
            </div>
        @endif
@endforeach
</div>