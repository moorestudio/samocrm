<?php 
    $event_currency = \App\CurrencyNames::find($event->currency)->currency;
 ?>

<div class="py-4">
    <p class="report_block_title">Распределение чистой прибыли <img data-toggle="tooltip" title="Общая сумма должна составлять 100%" data-placement="right" src="{{ asset('images/report/rep_q.png') }}" alt=""></p>
</div>
<div class="d-flex flex-wrap pl-0">
    <div class="col-lg-3 col-6 pl-0">
        <label for="samo-percent" style="font-size:13px;">САМО в %</label>
        <input class="input-style form-control white-bg" id="samo-percent" name="samo-percent" type="number" value="{{isset($financial) ? $financial->samo_percent : ''}}" placeholder="0%">
    </div>
    <div class="col-lg-3 col-6">
        <label for="event-percent" style="font-size:13px;">Организатору в %</label>
        <input class="input-style form-control white-bg" id="event-percent" name="event-percent" type="number" value="{{isset($financial) ? $financial->event_percent : ''}}" placeholder="0%">
    </div>
    <div class="col-lg-3 col-6 mt-lg-0 mt-3">
        <label for="speaker-percent" style="font-size:13px;">Тренеру в %</label>
        <input class="input-style form-control white-bg" id="speaker-percent" name="speaker-percent" type="number" value="{{isset($financial) ? $financial->speaker_percent : ''}}" placeholder="0%">
    </div>
    <div class="col-lg-3 col-6 d-flex align-items-end mt-lg-0 mt-3 pr-0">
        <button class="select-button w-100" id="part_complete" style="height:60%;">РАССЧИТАТЬ</button>
    </div>
</div>
<div class="d-flex flex-wrap mt-5">
    <div class="col-lg-3 col-12 mt-lg-0 mt-3 pl-0">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #16C206;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/report/rep_samo.png') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Доля САМО</p>
                   <p class="mb-0 report_stat_value">{{ isset($financial->samo_percent) ? ($financial->total / 100 * $financial->samo_percent) : '0' }} {{$event_currency}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 mt-lg-0 mt-3">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/report/rep_perc.png') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Доля организатора</p>
                   <p class="mb-0 report_stat_value">{{ isset($financial->event_percent) ? ($financial->total / 100 * $financial->event_percent) : '0' }} {{$event_currency}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 mt-lg-0 mt-3">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #FFA451;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/coin3.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Доля тренера</p>
                   <p class="mb-0 report_stat_value">{{ isset($financial->speaker_percent) ? ($financial->total / 100 * $financial->speaker_percent) : '0' }} {{$event_currency}}</p>
                </div>
            </div>
        </div>
    </div>
</div>