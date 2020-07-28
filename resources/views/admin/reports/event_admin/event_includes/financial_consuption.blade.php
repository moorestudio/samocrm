@if(isset($financial->consuption))
<div class="col-12">
    <div class="row report-header">
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Название расхода</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Пользователь</p>
        </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 title">Стоимость расход</p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 title">Дата внесения расхода</p>
        </div>
        <div class="col-lg-1 col-12">
            <p class="mb-0 title">Действие</p>
        </div>
    </div>
    @foreach($financial->consuption as $key => $consuption)
        <div class="row align-items-center report-card" id="consuption-{{$key}}">
            <div class="col-lg-3 col-12">
                <p class="mb-0 consuption_title">{{$consuption['title']}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{ $consuption['user_name'] }}</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 consuption_sum">{{$consuption['sum']}} {{$event_currency}}</p>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0">{{\Carbon\Carbon::parse($consuption['date'])}}</p>
            </div>
            <div class="col-lg-1 col-3 d-flex align-items-center justify-content-between mt-lg-0 mt-3">
                <button class="icon-button edit-confirm" data-id="{{$key}}">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="icon-button delete-confirm" data-id="{{$key}}">
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
@include('modals.report.delete_confirm_consuption')
@include('modals.report.edit_consuption')