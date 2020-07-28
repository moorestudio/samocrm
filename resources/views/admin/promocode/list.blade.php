@extends('layouts.app')
@section('content')

<div class="container">  
    <div class = "col-12 pl-0">
     <h2 class="text-uppercase text-lg-left promo-text">Промокоды</h2>
     <div class="row ">
            <div class="col d-flex justify-content-between">
            <div>
                <ul class="nav nav-tabs row" id="myTab" role="tablist">
                    <li class="nav-item tabber">
                        <a class="nav-link active" id="" data-toggle="tab" href="#main-tab" role="tab" aria-controls="home" aria-selected="true">
                        <img style="vertical-align: text-top;" src="{{ asset('images/chek-pro.svg') }}">  
                        Активные</a>
                    </li>
                    <li class="nav-item tabber">
                        <a class="nav-link" id="" data-toggle="tab" href="#no-active" role="tab" aria-controls="profile" aria-selected="false">
                        <img style="vertical-align: text-top;" src="{{ asset('images/block-pro.svg') }}">  
                        Не активные</a>
                    </li>
                    </ul>
            </div>
            <div>
            <ul class="nav nav-tabs row" id="myTab" role="tablist">
                    <li class="nav-item tabber">
                    <div class="text-right">
                         <a href="{{ route('promo_create') }}">
                             <button class="btn btn-warning promo-btn promo-btn-1">
                                Создать промокод
                              </button>
                              </a>
            </div>
                    </li>
                  </div>
                </ul>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="main-tab" role="tabpanel" aria-labelledby="financial-tab">
            <div class="row justify-content-lg-start justify-content-center mt-5">

@foreach($promos as $promo)
    @if($promo->active == true)
        <div class="col-4 p-3">
            <div class="promo col-12 p-3 promo-list">
                <p class="h5 font-weight-bold">
                    {{ $promo->name }}
                </p>
                <div class="col-12 d-flex mx-0 px-0 justify-content-between">
                <label class="promo-label">Промокод</label>
                <label class="promo-label">Скидка</label>
                </div>
                <div class="d-flex justify-content-between">
                   
                    <p class="promo-profile">{{ $promo->promo }}</p>
                    <p class="promo-profile">{{ $promo->discount }}%</p>
                </div>

                <a href="{{ route('promo_edit',['id' => $promo->id]) }}">
                    
                    <button class="btn btn-warning promo-edit-btn"><img src="images/pencil.svg" style="    margin-right: 11px;">Редактировать</button>
                </a>
            </div>
        </div>
    @endif
@endforeach

</div>
             </div>



<div class="tab-pane fade" id="no-active" role="tabpanel" aria-labelledby="financial-tab1">
    <div class="row justify-content-lg-start justify-content-center mt-5">
    @foreach($promos as $promo)
    @if($promo->active == false)
        <div class="col-4 p-3">
            <div class="promo p-3 promo-list">
                <p class="h5 font-weight-bold"  style="opacity: 0.6;">
                    {{ $promo->name }}
                </p>
                <div class="col-12 d-flex mx-0 px-0 justify-content-between" style="opacity: 0.6;">
                <label class="promo-label">Промокод</label>
                <label class="promo-label">Скидка</label>
                </div>
                <div class="d-flex justify-content-between">

                    <p class="promo-profile" style="opacity: 0.6;">{{ $promo->promo }}</p>
                    <p class="promo-profile" style="opacity: 0.6;">{{ $promo->discount }}%</p>
                </div>
                
                <a href="{{ route('promo_edit',['id' => $promo->id]) }}">
                <button class="btn btn-warning promo-edit-btn"><img src="images/pencil.svg" style="    margin-right: 11px;">Редактировать</button>
                </a>
            </div>
        </div>
    @endif
@endforeach
</div>
                </div>
        </div>


      
    </div>
</div>

@endsection