@extends('layouts.admin_app')
@section('content')
    
    <div class="d-none financial-id" data-id="{{$financial->id}}"></div>
    <div class="container">
    <div class="col-12 text-center pb-4 px-0 d-flex align-items-center">
        <p class="main-title mb-0">Отчет по мероприятию</p>
    </div>
    <div class="row ">
            <div class="col">
                <ul class="nav nav-tabs row" id="myTab" role="tablist">
                    <li class="nav-item col-lg-auto col-12 tabber">
                        <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="home" aria-selected="true">
                        <img style="vertical-align: text-top;" src="{{ asset('images/report/rep_info.png') }}">  
                        Информация</a>
                    </li>
                    <li class="nav-item col-lg-auto col-12 tabber">
                        <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab" aria-controls="profile" aria-selected="false">
                        <img style="vertical-align: text-top;" src="{{ asset('images/report/rep_fin.svg') }}">  
                        Финансы</a>
                    </li>
                    <li class="nav-item col-lg-auto col-12 tabber">
                        <a class="nav-link" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="contact" aria-selected="false">
                        <img style="vertical-align: text-top;" src="{{ asset('images/report/rep_clients.png') }}">  
                        Слушатели</a>
                    </li>
                    <li class="nav-item col-lg-auto col-12 tabber">
                        <a class="nav-link" id="reff-tab" data-toggle="tab" href="#reff_links" role="tab" aria-controls="contact" aria-selected="false">
                            <img style="vertical-align: text-top;" src="{{ asset('images/person1.png') }}">  
                            Рефералы
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="pb-3 mt-0 hor_line">
               <?php
                $event_currency = \App\CurrencyNames::find($event->currency)->currency;
               ?>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                <div class="row m-0">
                    <div class="col-lg-4 col-12 p-0 pr-4">
                        <img class="w-100 report_event_img" src="{{ asset('storage/'.$event->image) }}" alt="">
                    </div>
                    <div class="col-lg-6 col-12 p-4 rep_info_block">
                        <p class="font-weight-bold report_event_title">
                            {{$event->title}}
                        </p>
                        <div class="d-flex mt-4">
                          <p class="mb-1 w-50">
                              <span class="d-block">Тренер:</span>
                              <span class="font-weight-bold">{{$event->mentor}}</span> 
                          </p>
                          <p class="mb-1">
                              <span class="d-block">Город </span>
                              <span class="font-weight-bold">{{$event->city}}</span> 
                          </p>

                        </div>

                        <div class="d-flex mt-4 ">
                        <p class="mb-1 w-25">
                            <span class="d-block">Дата:</span>
                            <span class="font-weight-bold">{{\Carbon\Carbon::parseFromLocale($event->date, 'ru')->format('d M Y')}}</span> 
                        </p>                          
                        <p class="mb-1 w-25">
                            <span class="d-block">Время:</span>
                            <span class="font-weight-bold">{{\Carbon\Carbon::parseFromLocale($event->date, 'ru')->format('H:i')}}</span> 
                        </p>

                        <p class="mb-1 w-25">
                            <span class="d-block">Адрес:</span>
                            <span class="font-weight-bold">{{$event->address}}</span>
                        </p>

                        </div>
                    </div>

                    @if($event->scheme == 1)

                    <div class="col-12 py-3 secondary-title">Схема зала</div>
                    <div class="col-lg-8 col-12 pt-2" style="overflow-x: auto;">
                        <div class="d-flex flex-wrap" style="width: min-content;">
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
                                            if (isset($halls[$i-1]['column'][$j]['show']))
                                                {
                                                    $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['ticket_id']);
                                                    $user = \App\User::find($ticket->user_id);
                                                }
                                        ?>
                                        @if(isset($halls[$i-1]['column'][$j]['show']))
                                                <div class="m-1 buied scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                                        @else
                                                <div class="m-1 buied scene-place"></div>
                                        @endif

                                  @elseif(isset($halls[$i-1]['column'][$j]['reserve_id']))
                                          <?php
                                          if (isset($halls[$i-1]['column'][$j]['show']))
                                          {
                                              $ticket = \App\Ticket::find($halls[$i-1]['column'][$j]['reserve_id']);
                                              $user = \App\User::find($ticket->user_id);
                                          }
                                          ?>
                                          @if(isset($halls[$i-1]['column'][$j]['show']))
                                              <div class="m-1 place reserve scene-place" data-toggle="tooltip" title="ФИО: {{$user->fullname()}}, Деятельность: {{$user->work_type}}"></div>
                                          @else
                                              <div class="m-1 place reserve scene-place"></div>
                                          @endif
                                  @else

                                     @if($halls[$i-1]['column'][$j]['status']!=-1)
                                          @foreach($event->rate as $rate)
                                              @if ($halls[$i-1]['column'][$j]['status'] == $loop->index)
                                                <?php
                                                  $current_rate_price = $rate[3] >= \Carbon\Carbon::now() ? $rate[4] : $rate[2];
                                                ?>


                                              <div class="m-1 place scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" data-parent3="{{$current_rate_price}}" data-parent4="3"  id="{{$i}}-{{$j}}" data-toggle="tooltip" title="{{$j}} место,{{$rate['0']}},{{$current_rate_price}} {{$event_currency}}"
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
                                  <div class="m-1 font-weight-bold scene-place" style="font-size: 16px;">{{$i}}</div>
                                  </div>
                                <div class="col-12"></div>
                            @endfor
                            </div>
                        </div>
                    <div class="col-4 d-lg-block d-none pt-2">
                       <div class="d-flex" style="">
                           <div class="py-2  second-title" style="font-size: 16px;">
                               Категории билетов:
                           </div>
                       </div>


                      @foreach($event->rate as $rate)
                        <div class="d-flex align-items-center my-3">
                           <div  style="height:30px; width:30px;border-radius:3px; background-color: {{$rate[1]}};"></div> <span class="event-place-price-list ml-2">  Места - {{$rate[0]}}
                              @if($rate[3] >= \Carbon\Carbon::now())
                                <br><span class="font-weight-bold" style="color:{{$rate[1]}}">По акции {{$rate[4]}} {{$event_currency}}, до {{$rate[3]}}, </span><span class="font-weight-bold" style="color:{{$rate[1]}}">после {{$rate[2]}} {{$event_currency}}</span>
                              @else
                                <br><span class="font-weight-bold" style="color:{{$rate[1]}}">{{$rate[2]}} {{$event_currency}}</span>
                              @endif
                             </span>
                       </div>

                      @endforeach

                      <div class="d-flex" style="">
                           <div class="py-2  second-title" style="font-size: 16px;">
                               Инфо:
                           </div>
                       </div>


                        <div class="d-flex align-items-center my-3">
                           <div  style="height:30px; width:30px;border-radius:3px; background-color: #A6ACBE;"></div> 
                           <span class="event-place-price-list ml-2" style="font-size: 16px;max-width: 100px;">
                              Выкупленные места
                             </span>
                       </div>

                        <div class="d-flex align-items-center my-3" >
                           <div  style="height:30px; width:30px;border-radius:3px; background-color: #189DDF;"></div> 
                             <span class="event-place-price-list ml-2" style="font-size: 16px;max-width: 100px;">
                              Забронированные места
                             </span>
                       </div>
                    </div>
                    @endif
                    {{--</div>--}}

                </div>
            </div>
            <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">

                <div class="money_report">
                @include('admin.reports.event_admin.event_includes.money_report')
                </div>
                <div class="py-4">
                    <p class="report_block_title">Итоговые показатели по продажам</p>
                </div>
                <?php
                  {{ $total_event_sales = $EventSales + $SamoSales + $PartnerSales + $FranchiseSales;}}
                ?>
                <div class="d-flex flex-wrap">
                    <div class="report-stats-parent_sale p-1">
                        <div class="report-stats-block px-3 py-3 mr-lg-3 mr-0" style="border-color: #2CBFED;">
                            <div class="d-flex align-items-end">
                                <img src="{{ asset('images/report/rep_perc.png') }}" alt="">
                                <div class="ml-2">
                                    <p class="font-weight-light mb-1 report_stat_title">Личные продажи организатора:</p>
                                    <p class="report_stat_value mb-0">{{ $EventSales }} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">{{ $total_event_sales>0 ? number_format(($EventSales/$total_event_sales)*100,2) : 0}} %</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-stats-parent_sale p-1">
                        <div class="report-stats-block px-3 py-3 mr-lg-3 mr-0" style="border-color: #16C206;">
                            <div class="d-flex align-items-end">
                                <img src="{{ asset('images/report/rep_samo.png') }}" alt="">
                                <div class="ml-2">
                                    <p class="font-weight-light mb-1 report_stat_title" style="min-height: 34px;">Продажи САМО:</p>
                                    <p class="report_stat_value mb-0">{{ $SamoSales }} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">{{ $total_event_sales>0 ? number_format(($SamoSales/$total_event_sales)*100,2) : 0}} %</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-stats-parent_sale p-1">
                        <div class="report-stats-block px-3 py-3 mr-lg-3 mr-0" style="border-color: #2CBFED;">
                            <div class="d-flex align-items-end">
                                <img src="{{ asset('images/report/rep_part.png') }}" alt="">
                                <div class="ml-2">
                                    <p class="font-weight-light mb-1 report_stat_title">Продажи партнеров:</p>
                                    <p class="report_stat_value mb-0">{{ $PartnerSales }} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">{{$total_event_sales ? number_format(($PartnerSales/$total_event_sales)*100,2) : 0}} %</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-stats-parent_sale p-1">
                        <div class="report-stats-block px-3 py-3 mr-lg-3 mr-0" style="border-color: #FFA451;">
                            <div class="d-flex align-items-end">
                                <img src="{{ asset('images/report/rep_fra.png') }}" alt="">
                                <div class="ml-2">
                                    <p class="font-weight-light mb-1 report_stat_title">Продажи других франчайзи:</p>
                                    <p class="report_stat_value mb-0">{{ $FranchiseSales }} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">{{$total_event_sales ? number_format(($FranchiseSales/$total_event_sales)*100,2) : 0}} %</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-stats-parent_sale p-1">
                        <div class="report-stats-block px-3 py-3" style="border-color: #2CBFED;">
                            <div class="d-flex align-items-end">
                                <img src="{{ asset('images/report/rep_sum.png') }}" alt="">
                                <div class="ml-2">
                                    <p class="font-weight-light mb-1 report_stat_title" style="min-height: 34px;">Сумма продаж:</p>
                                    <p class="report_stat_value mb-0">{{ $EventSales + $SamoSales + $PartnerSales + $FranchiseSales}} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">100%</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <p class="report_block_title">Добавить расход</p>
                </div>
                <div class="d-flex flex-wrap mb-4">
                    <div class="col-lg-12 col-12">
                        <div class="row consuptions">
                            <div class="col-lg-12 col-12 p-0">
                                <label for="event_name">Название расхода</label>
                                <input placeholder="Введите название расхода*" type="text" name="name" id="consuption_name" class="form-control consuption_name input-style white-bg" required>
                            </div>
                            <div class="col-lg-9 col-6 p-0 mt-4">
                                <label for="event_name">Сумма расхода</label>
                                <input placeholder="Введите сумму расхода*" type="number" name="sum" id="consuption_sum" class="form-control consuption_sum input-style white-bg min-zero-val" required>
                            </div>
                            <div class="col-lg-3 col-6 d-flex align-items-end pr-0  mt-4">
                                <button class="select-button consuption w-100" style="height:60%;">ДОБАВИТЬ</button>
                            </div>
                        </div>
                    </div>
                  </div>
                <div class="py-4">
                    <p class="report_block_title">Добавить прибыль</p>
                </div>
                <div class="d-felx flex-wrap mb-4">
                <div class="col-lg-12 col-12">
                    <div class="row consuptions">
                        <div class="col-lg-12 col-12 p-0">
                            <label for="event_name">Название прибыли</label>
                            <input placeholder="Введите название прибыли*" type="text" name="name" id="income_name" class="form-control income_name input-style white-bg" required>
                        </div>
                        <div class="col-lg-9 col-6 p-0 mt-4">
                            <label for="event_name">Сумма прибыли</label>
                            <input placeholder="Введите сумму прибыли*" type="number" name="sum" id="income_sum" class="form-control income_sum input-style white-bg min-zero-val" required>
                        </div>
                        <div class="col-lg-3 col-6 d-flex align-items-end pr-0 mt-4">
                            <button class="add_raw_income select-button w-100" style="height:60%;">ДОБАВИТЬ</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-12 parts_report px-0">
                    @include('admin.reports.event_admin.event_includes.parts_report')
                </div>
                </div>

              <div class="py-4">
                  <p class="report_block_title">% Бонусов на это мероприятие</p>
              </div>
                <div class="d-flex flex-wrap justify-content-lg-start justify-content-center white-bg-round py-3">
                  @if($event->financial->franch_perc > 0)  
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">Франчайзи</p>
                      <p class="mb-0 title">{{$event->financial->franch_perc}} %</p>
                    </div>
                  @else
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">Франчайзи</p>
                      <p class="mb-0 title">Не менялась</p>
                    </div>
                  @endif

                  @if($event->financial->franch_perc > 0)  
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">Партнер</p>
                      <p class="mb-0 title">{{$event->financial->partner_perc}} %</p>
                    </div>
                  @else
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">Партнер</p>
                      <p class="mb-0 title">Не менялась</p>
                    </div>
                  @endif
                  @if($event->financial->franch_perc > 0)  
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">САМО Sales</p>
                      <p class="mb-0 title">{{$event->financial->samo_sales_perc}} %</p>
                    </div>
                  @else
                    <div class="col-lg-4 col-md-4  col-6">
                      <p class="mb-0 title">САМО Sales</p>
                      <p class="mb-0 title">Не менялась</p>
                    </div>
                  @endif

                </div>
                <div class="py-4">
                  <p class="report_block_title">Детальные показатели</p>
                </div>
                <div class="d-flex flex-wrap justify-content-lg-start justify-content-center pt-5">
                    <div class="col-lg-2 col-6" style="position: relative;">
                        <button class="second-button prof_button second-button-active w-100" data-parent="1">
                            Доходы
                        <span class="blueLine blueLine-active"></span>    
                        </button>
                    </div>
                    <div class="col-lg-2 col-6" style="position: relative;">
                        <button class="second-button prof_button w-100" data-parent="7">
                            Доп прибыль
                        <span class="blueLine"></span>     
                        </button>
                    </div>
                    <div class="col-lg-2 col-6" style="position: relative;">
                        <button class="second-button prof_button w-100" data-parent="2">
                            Расходы
                        <span class="blueLine"></span>     
                        </button>
                    </div>
                    <div class="col-lg-2 col-6 mt-lg-0 mt-3" style="position: relative;">
                        <button class="second-button prof_button w-100" data-parent="3">
                            Выплаты продавцам
                        <span class="blueLine"></span>     
                        </button>
                    </div>
                </div>

                <div class="row whiteLine">
                  
                </div>

                <div class="d-felx flex-wrap mt-5 report_block" style="height:500px; overflow-y: auto;">
                    @include('admin.reports.event_admin.event_includes.financial_income')
                </div>
            </div>
            <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
              <div style="text-align: right;">
                  <a href="{{route('export_c_event',['id'=>$event->id])}}">
                    <button  class="p-2 mr-2 select-button">
                      Экспорт в слушателей
                    </button>
                  </a>
              </div>

              
                <div class="container-fluid">
                    <div class="row justify-content-lg-start justify-content-center pt-4">
                        <div class="col-lg-2 col-6 position-relative">
                            <button class="second-button client_button second-button-active w-100 prof_button" data-parent="1">
                                Выкуплено
                                {{count(\App\Ticket::where('event_id', $event->id)->whereIn('type',['buy','done'])->get())}}
                                <span class="blueLine blueLine-active"></span>    
                            </button>
                        </div>
                        <div class="col-lg-2 col-6 position-relative">
                            <button class="second-button client_button w-100 prof_button" data-parent="2">
                                Забронировано
                                {{count(\App\Ticket::where('event_id', $event->id)->where('type','reserve')->get())}}
                                <span class="blueLine"></span>    
                            </button>
                        </div>
                        <div class="col-lg-2 col-6 position-relative mt-lg-0 mt-3">
                            <button class="second-button client_button w-100 prof_button" data-parent="3">
                                Возврат
                                {{count(\App\Ticket::where('event_id', $event->id)->where('type','return')->get())}}
                                <span class="blueLine"></span>    
                            </button>
                        </div>
                        <div class="col-lg-2 col-6 position-relative mt-lg-0 mt-3">
                            <button class="second-button client_button w-100 prof_button" data-parent="4">
                                История билетов
                                <span class="blueLine"></span>    
                            </button>
                        </div>
                        <div class="col-lg-2 d-flex col-6 position-relative mt-lg-0 mt-3 px-0" style="pointer-events: none;margin-left:auto;">
                            <button class="btn btn-warning second-button client_button w-100" data-parent="3" style="text-transform:none;height: 33px;">
                                Свободных мест:
                                {{$event->free_seats()}}
                            </button>
                        </div>
                    </div>
                    <div class="d-flex whiteLine">
                    
                    </div>
                    <div class="row client_block mt-5" id="client_block_id" style="height:500px; overflow-y: auto;">
                        @include('admin.reports.event_admin.event_includes.clients')
                    </div>

                    </div>
                    </div>


            <div class="tab-pane fade" id="reff_links" role="tabpanel" aria-labelledby="reff_links">
                <div class="container-fluid">
                  @include('admin.reports.event_admin.event_includes.reff_links_body')
                </div>
            </div>
        </div>
    </div>
@include('modals.report.return_confirm')
@endsection
@push('scripts')


        <script>


$(document).ready(function() {
  var triangles = document.querySelectorAll(".triangle_shape");
  triangles.forEach(t => t.classList.add('triangle_shape_scene'));

  var ovals = document.querySelectorAll(".oval_shape");

  ovals.forEach(o => o.classList.remove('oval_shape'));
  ovals.forEach(o => o.classList.add('oval_shape_scene'));

});
        $('.prof_button').on('click', function (e) {
            let btn = $(e.currentTarget);
            let parent = btn.parent().parent();
            let btn_span = $(e.target.querySelector('span'));
            $('.prof_button').removeClass('second-button-active');
            parent.find('.blueLine').removeClass('blueLine-active');
            btn_span.addClass('blueLine-active');
            btn.addClass('second-button-active');
            let financial_id = {{$financial->id}};
            let type = btn.data('parent');
            $('.report_block').addClass('disappear');
            $.ajax({
                url: '{{ route('switch_report') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "type": type,
                    "financial_id": financial_id,
                    "event":"{{$event->id}}"
                },
                success: data => {
                    $('.report_block').html(data.view).show('slide', {direction: 'left'}, 400);
                    $('.report_block').removeClass('disappear');
                },
                error: () => {
                    $('.report_block').removeClass('disappear');
                }
            })
        })
    </script>
        <script>
            $('.client_button').click(function (e) {
                let btn = $(e.currentTarget);
                $('.client_button').removeClass('second-button-active');
                btn.addClass('second-button-active');

                let financial_id = {{$financial->id}};
                let type = btn.data('parent');
                $('.client_block').addClass('disappear');
                document.getElementById('client_block_id').innerHTML = "";
                $.ajax({
                    url: '{{ route('switch_client') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "type": type,
                        "financial_id": financial_id,
                    },
                    success: data => {
                        $('.client_block').html(data.view).show('slide', {direction: 'left'}, 400);
                        $('.client_block').removeClass('disappear');
                    },
                    error: () => {
                        $('.client_block').removeClass('disappear');
                    }
                })
            })
        </script>
    <script>
        $('.consuption').on('click', function () {
            let name = $('.consuption_name').val();
            let sum = $('.consuption_sum').val();
            if ( isNaN(sum) || name == '' || sum == '')
            {

            }
            else
            {
                $.ajax({
                    url: '{{ route('add_consuption') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name,
                        "sum": sum,
                        "financial": {{$financial->id}},
                        "event":"{{$event->id}}"
                    },
                    success: data => {
                        $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                        $('.consuption_name').val('');
                        $('.consuption_sum').val('');
                    },
                    error: () => {
                    }
                })
            }


        })




        $('.add_raw_income').on('click', function () {
            let name = $('.income_name').val();
            let sum = $('.income_sum').val();
            if ( isNaN(sum) || name == '' || sum == '')
            {

            }
            else
            {
                $.ajax({
                    url: '{{ route('add_income') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name,
                        "sum": sum,
                        "financial": {{$financial->id}},
                        "event":"{{$event->id}}"
                    },
                    success: data => {
                        $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                        $('.income_name').val('');
                        $('.income_sum').val('');
                    },
                    error: () => {
                    }
                })


            }


        })












    </script>
        <script>
            $(document).on('click', '.delete-confirm', function (e) {
                var btn = $(e.currentTarget);
                var id = btn.data('id');

                $('.btn-delete').attr('data-id',id);
                $('#DeleteConfirmConsuption').modal('show');
            })

            $(document).on('click', '.delete_income-confirm', function (e) {
                var btn_inc = $(e.currentTarget);
                var name = btn_inc.data('name');

                $('.btn-delete_raw_income').attr('data-name',name);
                $('#DeleteConfirmRawIncome').modal('show');
            })
        </script>
        <script>
            $(document).on('click', '.btn-delete', function (e) {
                $('#DeleteConfirmConsuption').modal('hide');
                var btn = $(e.currentTarget);
                var id = btn.data('id');
                let del_id = $(this).attr('data-id');
                
                $.ajax({
                    url: '{{ route('delete_consuption') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": del_id,
                        "event":"{{$event->id}}",
                        "financial": {{$financial->id}},
                    },
                    success: data => {
                        $('#consuption-' + del_id).hide(200);
                        $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                    },
                    error: () => {
                    }
                })

            })

            $(document).on('click', '.btn-delete_raw_income', function (e) {
                $('#DeleteConfirmRawIncome').modal('hide');
                let name_del = $(this).attr('data-name');
                $.ajax({
                    url: '{{ route('remove_raw_income') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name_del,
                        "financial": {{$financial->id}},
                        "event":"{{$event->id}}"
                    },
                    success: data => {
                        $('#raw_income-' + name_del).hide(200);
                        $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);

                    },
                    error: () => {
                    }
                })

            })

        </script>
        <script>
            $(document).on('click', '.edit-confirm', function (e) {
                var btn = $(e.currentTarget);
                var id = btn.data('id');

                $.ajax({
                    url: '{{ route('get_consuption') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "financial": {{$financial->id}},
                        "event":"{{$event->id}}",
                    },
                    success: data => {
                        $('#edit_consuption_name').val(data.name);
                        $('#edit_consuption_sum').val(data.sum);
                        $('#EditConsuption').modal('show');
                        $('.btn-edit').attr('data-id',id);
                    },
                    error: () => {
                    }
                })


            })

              $(document).on('click', '.edit-raw_income_confirm', function (e) {
                var btn_edit_raw = $(e.currentTarget);
                var name_edit_raw = btn_edit_raw.data('name');
                $.ajax({
                    url: '{{ route('get_raw_income') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name_edit_raw,
                        "financial": {{$financial->id}},
                        "event":"{{$event->id}}",
                    },
                    success: data => {
                        $('#edit_raw_income_name').val(data.name);
                        $('#edit_raw_income_sum').val(data.sum);
                        $('#EditRawIncome').modal('show');
                        $('.btn_income_edit').attr('data-name',name_edit_raw);
                    },
                    error: () => {
                    }
                })
            })
        </script>
    <script>
        $(document).on('click', '.btn-edit', function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var name = $('#edit_consuption_name').val();
            var sum = $('#edit_consuption_sum').val();


            $.ajax({
                url: '{{ route('edit_consuption') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "financial": {{$financial->id}},
                    "event":"{{$event->id}}",
                    "name": name,
                    "sum": sum,
                },
                success: data => {
                    $('#consuption-' + id).find('.consuption_title').html(name);
                    $('#consuption-' + id).find('.consuption_sum').html(sum);
                    $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                    $('#EditConsuption').modal('hide');
                    // $('.report_block').removeClass('disappear');
                },
                error: () => {
                    // $('.report_block').removeClass('disappear');
                }
            })
        })



        $(document).on('click', '.btn_income_edit', function (e) {
            var btn_edit_ = $(e.currentTarget);
            var raw_old_name = btn_edit_.data('name');
            var name_new_name = $('#edit_raw_income_name').val();
            var val_new_val = $('#edit_raw_income_sum').val();

            $.ajax({
                url: '{{ route('raw_income_edit') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "event":"{{$event->id}}",
                    "financial": {{$financial->id}},
                    "name": name_new_name,
                    "sum": val_new_val,
                    "old_name":raw_old_name,
                },
                success: data => {
                    $('#raw_income-' + raw_old_name).find('.raw_i_title').html(name_new_name);
                    $('#raw_income-' + raw_old_name).find('.raw_iF_sum').html(val_new_val);
                    $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                    $('#EditRawIncome').modal('hide');

                },
                error: () => {
                    // $('.report_block').removeClass('disappear');
                }
            })
        })



    </script>
        <script>
            $(document).on('click', '#part_complete', function (e) {
                var btn = $(e.currentTarget);
                var samo = parseInt(document.getElementById('samo-percent').value);
                var event = parseInt(document.getElementById('event-percent').value);
                var speaker = parseInt(document.getElementById('speaker-percent').value);

                if(samo + event + speaker == 100)
                {
                    $.ajax({
                        url: '{{ route('change_parts') }}',
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "financial": {{$financial->id}},
                            "samo": samo,
                            "event": event,
                            "speaker": speaker,
                            "event_id":{{$event->id}}
                        },
                        success: data => {
                            $('.parts_report').html(data.view).show('slide', {direction: 'left'}, 400);
                            // $('#consuption-' + id).find('.consuption_title').html(name);
                            // $('#consuption-' + id).find('.consuption_sum').html(sum);
                            // $('.money_report').html(data.view).show('slide', {direction: 'left'}, 400);
                            // $('#EditConsuption').modal('hide');
                            // $('.report_block').removeClass('disappear');
                        },
                        error: () => {
                            // $('.report_block').removeClass('disappear');
                        }
                    })
                }
                else
                {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Некорректно введены данные!',
                        // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                        showConfirmButton: false,
                    });
                }

            })
        </script>
        <script>
            $(document).on('click', '.return-confirm',function (e) {
                var btn = $(e.currentTarget);
                var id = btn.data('id');
                $('.btn-return').attr('data-id',id);
                $('#ReturnConfirm').modal('show');
            })
        </script>
        <script>
            $(document).on('click','.btn-return',function (e) {
                var btn = $(e.currentTarget);
                var id = btn.data('id');
                var comment = $('#return_comment');

                if(comment.val() != '' && comment.val().length > 19)
                {
                    $('.comment_error').hide(200);

                    $.ajax({
                        url: '{{ route('return_ticket') }}',
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "comment": comment.val(),
                        },
                        success: data => {
                            comment.val('');
                            $('#ReturnConfirm').modal('hide');
                            document.getElementById('buy-' + id).style.display = "none";
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Статус билета изменен!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: false,
                            });
                        },
                        error: () => {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Ошибка!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: false,
                            });
                        }
                    })

                }
                else
                {
                    $('.comment_error').show(200);
                }
            })
        </script>
@endpush
