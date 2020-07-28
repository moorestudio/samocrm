<div class="py-4">
    <p class="report_block_title">Итоговые показатели</p>
</div>
<div class="d-flex flex-wrap justify-content-center">

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/report/rep_income.png') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Выручка:</p>
                   <p class="mb-0 report_stat_value">{{$financial->total_income}} {{$event_currency}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #FFA451;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/report/rep_extra.png') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">доп прибыль:</p>
                   <p class="mb-0 report_stat_value">{{$total_raw_income}} {{$event_currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block spec_padding px-3 mr-lg-1 mr-0" style="border-color: #F85449;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/report/rep_expenses.png') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Расходы:</p>
                <p class="mb-0 report_stat_value">{{$financial->total_consuption}} {{$event_currency}}, <br> <span style="font-size: 12px; color: #333333; line-height: 100%;">{{ $financial->total_income>0 ?  number_format(($financial->total_consuption/$financial->total_income)*100,2) : 0}} %</p>
            </div>
        </div>
    </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block px-3 spec_padding mr-lg-1 mr-0" style="border-color: #2CBFED;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/report/rep_bonus.png') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Бонусы продавцов:</p>
                <p class="mb-0 report_stat_value">{{$financial->franch_total}} {{$event_currency}}</p>
            </div>
        </div>
    </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block px-3 spec_padding mr-lg-1 mr-0" style="border-color: #D35252;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/report/rep_disc.png') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Всего скидок на сумму:</p>
                <p class="mb-0 report_stat_value">{{$financial->total_discount != null ? $financial->total_discount : '0'}} {{$event_currency}}</p>
            </div>
        </div>
    </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block px-3 spec_padding" style="border-color: #2CBFED;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/report/rep_net.png') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Чистая прибыль:</p>
                <p class="mb-0 report_stat_value">{{$financial->total }} {{$event_currency}}</p>
            </div>
        </div>
    </div>
    </div>
    {{--<p class="h5 font-weight-bold mr-4">Доход: {{$financial->total_income}} {{$event_currency}}</p>--}}
    {{--<p class="h5 font-weight-bold mr-4">Расходы: {{$financial->total_consuption}} {{$event_currency}}</p>--}}
    {{--<p class="h5 font-weight-bold mr-4">Доля продавцов: {{$financial->franch_total}} {{$event_currency}}</p>--}}
    {{--<p class="h5 font-weight-bold">Чистый доход: {{$financial->total}} {{$event_currency}}</p>--}}
</div>

