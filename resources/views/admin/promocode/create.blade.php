@extends('layouts.app')
@section('content')
<div class=container>
    <div class = "col-12 pl-0">
     <h2 class="text-uppercase text-lg-left promo-text">{{isset($promo) ? "Редактирование промокода" : "Создание промокода"}}</h2>
    </div>
        <div class="my-4 promo-bg">
            <form class="mt-5 promo-form" action="{{ isset($promo) ? route('promo_update') : route('promo_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{isset($promo) ? $promo->id : ''}}">
                <label for="name">Название промокода</label>
                <div class="md-form mt-0">
                    <input placeholder="Название промокода*" type="text" id="name" name="name" class="form-control promo-input" value="{{ isset($promo) ? $promo->name : '' }}" required>
                </div>

                <label for="promo">Промокод</label>
                <div class="md-form mt-0">
                    <input placeholder="Промокод*" type="text" id="promo" name="promo" class="form-control promo-input" value="{{ isset($promo) ? $promo->promo : '' }}" required>
                </div>

                <label for="discount">Скидка</label>
                <div class="md-form mt-0">
                    <input placeholder="Скидка %" type="number" id="discount" name="discount" class="form-control promo-input max-100-value" value="{{ isset($promo) ? $promo->discount : '' }}" required>
                </div>

                <div class="custom-control custom-checkbox promo-check">
                    <input type="checkbox" class="custom-control-input" name="active" id="active" {{ isset($promo) && $promo->active == 1 ? 'checked' : ''}}>
                    <label class="custom-control-label" for="active">Активировать</label>
                </div>
                <div class= "d-flex">
                    <button class="btn btn-succees mt-4 promo-btn" type="submit">
                        Сохранить
                    </button>
                    <a class="btn btn-warning mt-4 promo-btn-cancel" href = "/promo_list">
                        Отмена
                    </a>
                </div>
            </form>
    </div>
</div>

@endsection
