@extends('layouts.app')
@section('content')
    <div class="container-fluid p-lg-5 p-0">
        <form action="{{ route('buy') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p class="h2 text-uppercase font-weight-bold pt-3 pb-3 mx-5">Оформление заказа</p>
            <div class="row p-lg-5 p-3">
                <div class="col-lg-5 col-12">
                        <div class="p-4" style="background-color: #F1F1F1; border-radius:10px;">
                            <p class="h4 text-uppercase font-weight-bold pt-3 pb-3">Личные данные</p>
                            <input class="form-control mb-3" type="text" name="name" placeholder="ФИО*" required>
                            <input class="form-control mb-3" type="text" name="city" placeholder="Город" required>
                            <input class="form-control mb-3" type="text" name="country" placeholder="Страна" required>
                            <input class="form-control mb-3" type="text" name="email" placeholder="Почта" required>
                            <input class="form-control mb-3" type="text" name="contact" placeholder="Контакты" required>
                            <input class="form-control mb-3" type="text" name="message" placeholder="Откуда узнали?" required>
                            <input class="form-control mb-3" type="text" name="position" placeholder="Должность" required>
                            <input class="form-control mb-3" type="text" name="company" placeholder="Компания" required>
                            <input type="hidden" value="{{ $row }}" name="row_number">
                            <input type="hidden" value="{{ $place }}" name="place_number">
                            <input type="hidden" value="{{ $event->id }}" name="event_id">
                        </div>
                </div>
                <div class="col-lg-7 col-12 pt-lg-0 pt-4">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-8 col-9 p-lg-4 p-3 d-lg-block d-none" style="border:1px solid #C6C6C6; border-radius: 10px 0px 0px 10px;">
                                <p class="h4 text-uppercase font-weight-bold pb-3" style="font-size:16px;">{{ $event->title }}</p>
                                <p class="" style="font-size:13px;">Место проведения</p>
                                <p class="font-weight-bold text-uppercase h5 pb-2" style="font-size:14px;">{{ $event->address }}</p>
                                <div class="row">
                                <span class="col-4" style="font-size:13px;">
                                    Дата
                                </span>
                                <span class="col-3" style="font-size:13px;">
                                    Время
                                </span>
                                <span class="col-5" style="font-size:13px;">
                                    Место
                                </span>
                                    <span class="col-4 font-weight-bold" style="font-size:13px;">
                                        {{ $event->date }}
                                </span>
                                    <span class="col-3 font-weight-bold" style="font-size:13px;">
                                    18.30
                                </span>
                                    <span class="col-5 font-weight-bold" style="font-size:13px;">
                                    Ряд: {{ $row }}, Место: {{ $place }}
                                </span>
                                </div>
                                <p class="mb-0 mt-lg-5 mt-1" style="font-size:13px">Итого</p>
                                <span class="font-weight-bold" style="font-size:13px;"><span style="font-size:15px;">2500</span>сом</span>
                            </div>
                            <div class="col-lg-4 col-3 px-0 d-lg-block d-none" style="border-radius: 0px 10px 10px 0px;">
                                <img class="w-100 h-100" src="{{ asset('images/ticket.png') }}" alt="">
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-4 col-12 p-4 mt-4" style="background-color: #F1F1F1; border-radius:10px;">
                                <p class="h5 text-uppercase font-weight-bold pb-1">Способы Оплаты</p>
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" name="bank">
                                    <img class="w-25" src="{{ asset('images/pay.png') }}" alt="">
                                    <input class="ml-3 mr-2" type="checkbox" name="cash">
                                    <span style="font-size:10px;">Наличные</span>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-5 px-0 pt-lg-3 pt-5">
                                <button type="submit" class="btn w-100 py-3" style="background-color: #F6B91B; border-radius:3px;">Купить</button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection