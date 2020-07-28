<div class="py-4">
        <p class="report_block_title">Общие показатели</p>
</div>
<div class="d-flex justify-content-center">
    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #FFA05B;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/cost2.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Общая выручка</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_inc_curr) ? $total_inc_curr[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 spec_padding mr-lg-1 mr-0" style="border-color: #E95A5A;">
            <div class="d-flex align-items-end">
                <img src="{{ asset('images/sale3.svg') }}" alt="">
                <div class="ml-2">
                    <p class="font-weight-light mb-1 report_stat_title">Скидки</p>
                    <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_disc_curr) ? $total_disc_curr[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #1FBE06;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/money3.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Доп. прибыль</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_r_income_curr) ? $total_r_income_curr[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block spec_padding px-3 mr-lg-1 mr-0" style="border-color: #E95A5A;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/cost4.svg') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Расходы</p>
                <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_exp_curr) ? $total_exp_curr[$index] : 0}}{{$currency}}</p>
            </div>
        </div>
    </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block px-3 spec_padding mr-lg-1 mr-0" style="border-color: #2CBFED;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/report/rep_bonus.png') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Бонусы продавцов</p>
                <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_franch_curr) ? $total_franch_curr[$index] : 0}}{{$currency}}</p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
    <div class="report-stats-block px-3 spec_padding" style="border-color: #2B98E7;">
        <div class="d-flex align-items-end">
            <img src="{{ asset('images/chart.svg') }}" alt="">
            <div class="ml-2">
                <p class="font-weight-light mb-1 report_stat_title">Чистая прибыль</p>
                <p class="mb-0 report_stat_value">{{array_key_exists($index, $total_net) ? $total_net[$index] : 0}}{{$currency}}</p>
            </div>
        </div>
    </div>
    </div>
</div>
<div class="py-4">
    <p class="report_block_title">Распределение чистой прибыли</p>
</div>
<div class="d-flex justify-content-left">

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/adduser.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Организатор</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $net_Event) ? $net_Event[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/coin2.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">САМО</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $net_Samo) ? $net_Samo[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/usercheck.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Спикер</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $net_Speaker) ? $net_Speaker[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-4">
    <p class="report_block_title">Бонусы продавцов</p>
</div>
<div class="d-flex justify-content-left">

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/money.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Продавцы САМО</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $sales_rew) ? $sales_rew[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/coin3.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Франчайзи</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $franch_rew) ? $franch_rew[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-6 p-1">
        <div class="report-stats-block px-3 py-4 mr-lg-1 mr-0" style="border-color: #2CBFED;">
           <div class="d-flex align-items-end">
                <img src="{{ asset('images/coin4.svg') }}" alt="">
                <div class="ml-2">
                  <p class="font-weight-light mb-1 report_stat_title">Партнеры</p>
                   <p class="mb-0 report_stat_value">{{array_key_exists($index, $partners_rew) ? $partners_rew[$index] : 0}}{{$currency}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="py-4">
    <p class="report_block_title">Продажи</p>
</div>
<div class="d-flex">
    <div class="col-10 pr-4">
        <div class="row">
            <div class="col-6 p-1">
                <div class="main_rep_field d-flex justify-content-between">
                <span> Личные продажи организаторов</span> <span style="color: #189DDF;">{{array_key_exists($index, $partners_sales) ? $partners_sales[$index] : 0}}{{$currency}}</span>
                </div>
            </div>
            <div class="col-6 p-1">
                <div class="main_rep_field d-flex justify-content-between">
                    <span>Продажи САМО</span>  <span style="color: #189DDF;"> {{array_key_exists($index, $SAMO_sales) ? $SAMO_sales[$index] : 0}}{{$currency}}</span>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-6 p-1">
                <div class="main_rep_field d-flex justify-content-between">
                    <span>Продажи партнеров</span>   <span style="color: #189DDF;">{{array_key_exists($index, $EventAdmin_sales) ? $EventAdmin_sales[$index] : 0}}{{$currency}}</span>
                </div>
            </div>
            <div class="col-6 p-1">
                <div class="main_rep_field d-flex justify-content-between">
                <span>Продажи других франчайзи</span>    <span style="color: #189DDF;">{{array_key_exists($index, $franch_sales) ? $franch_sales[$index] : 0}}{{$currency}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2 p-1">
        <div class="main_rep_field d-flex flex-column h-100">
            <div class="d-block h-50">
                Сумма продаж
            </div>
            <div class="d-block h-50" style="color: #189DDF;">
               {{(array_key_exists($index, $partners_sales) ? $partners_sales[$index] : 0) + (array_key_exists($index, $SAMO_sales) ? $SAMO_sales[$index] : 0)+ (array_key_exists($index, $EventAdmin_sales) ? $EventAdmin_sales[$index] : 0) +(array_key_exists($index, $franch_sales) ? $franch_sales[$index] : 0)}}{{$currency}}
            </div>
        </div>
    </div>
</div>