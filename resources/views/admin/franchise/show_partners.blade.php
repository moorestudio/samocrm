@extends('layouts.admin_app')
@section('content')

<div class="container">
	<p class="h4 font-weight-bold mb-4">Партнеры</p>
    <div class="col-12">
        <div class="row report-header mb-3">
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">ФИО</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Город</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Номер телефона</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Сумма</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Дата подключения</p>
            </div>
            <div class="col-lg-2 col-12">
                <p class="mb-0 title">Дата истечения</p>
            </div>
        </div>
        @forelse(\App\User::all()->where('franchise_id',Auth::user()->id)->where('role_id', 5); as $partner)
            <div class="row align-items-center report-card special-card">
                <div class="col-lg-2 col-12">
                    <a href="{{ route('partner_clients',['id'=>$partner->id]) }}" >
                        <p class="mb-0">{{$partner->fullName() }}</p>
                    </a>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">{{ $partner->city }}</p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">{{ $partner->contacts }}</p>
                </div>
                <div class="col-lg-2 col-12">
                    <?php 
                        $all_cur_b=$partner->getSumFromFinancial($all_financial);
                        
                    ?>
                    @foreach($all_cur_b as $id=> $bonus)
                        <span>{{$bonus}}-{{\App\CurrencyNames::find($id)->currency}}</span><br>
                    @endforeach
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">{{ $partner->contract_date }}</p>
                </div>
                <div class="col-lg-2 col-12">
                    <p class="mb-0">{{ $partner->contract_date_end }}</p>
                </div>
            </div>
        @empty 
            <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
                <img src="{{asset('images/disabled.svg')}}" alt="">
                <div class="w-100"></div>
                <span class="second-title mt-2 empty-element">У вас отсутствуют <br> партнеры</span>
            </div>
        @endforelse
    </div>
</div>

@endsection
