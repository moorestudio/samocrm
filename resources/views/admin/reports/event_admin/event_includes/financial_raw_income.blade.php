@if(isset($financial->raw_income))
<div class="col-12">
    <div class="row report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Название прибыли</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Пользователь</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Значение</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Дата внесения прибыли</p>
        </div>
        <div class="col-lg-1 col-12">
            <p class="mb-0 title">Действие</p>
        </div>
    </div>
    @foreach($financial->raw_income as $raw_income)
        <div class="row align-items-center report-card" id="raw_income-{{$raw_income['name']}}">
            <div class="col-lg-3 col-12">
                <p class="mb-0 raw_i_title">{{$raw_income['name']}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $raw_income['user_name'] }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 raw_i_sum">{{$raw_income['value']}} {{$event_currency}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{\Carbon\Carbon::parse($raw_income['date'])}}</p>
            </div>
            <div class="col-lg-1 col-3 d-flex align-items-center justify-content-between mt-lg-0 mt-3">
                <button class="icon-button edit-raw_income_confirm" data-name="{{$raw_income['name']}}">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="icon-button delete_income-confirm" data-name="{{$raw_income['name']}}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>
@else
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">На это мероприятие расходов нет</span>
    </div>
@endif
@include('modals.report.delete_confirm_raw_income')
@include('modals.report.edit_raw_income')
