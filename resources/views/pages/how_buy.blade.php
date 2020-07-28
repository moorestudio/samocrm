@extends('layouts.app')
@section('content')

    <div class="container">
        <p class="main-title text-uppercase my-4">Как купить билеты</p>
        <div class="white-bg-round p-4">
          <p class="second-title font-weight-light mb-4"><span class="font-weight-bold mr-1">1.</span> Выберите мероприятие, которое Вы бы хотели посетить</p>

          <img class="img-fluid" src="{{asset('images/select_event.svg')}}" alt="">

          <p class="second-title font-weight-light my-4"><span class="font-weight-bold mr-1">2.</span> Прочитайте полное описание мероприятия и переходите к следующему пункту</p>
          <img class="img-fluid" src="{{asset('images/event-desc.svg')}}" alt="">
          <p class="second-title font-weight-light my-4 align-items-start"><span class="font-weight-bold mr-1">3.</span> Выберите подходящее вам количество билетов (выбранные места будут выделены в соответствующим цветом выбранного тарифа), затем перейдите по кнопке «Купить»</p>

          <img class="img-fluid" src="{{asset('images/event-scene.svg')}}" alt="">

          <p class="second-title font-weight-light my-4 align-items-start"><span class="font-weight-bold mr-1">4.</span> Заполните свои данные. Будьте внимательны при заполнении данных. <br> Введите только настоящие данные.</p>

          <img class="img-fluid" src="{{asset('images/event-buy.svg')}}" alt="">

          <p class="second-title font-weight-light my-4"><span class="font-weight-bold mr-1">5.</span> Выберите один из способов оплаты</p>

          <img class="img-fluid" src="{{asset('images/event-choose.svg')}}" alt="">

          <p class="second-title font-weight-light my-4 align-items-start"><span class="font-weight-bold mr-1">6.</span> Далее вы перейдете на страницу оплаты выбранным вами способом. <br> Действуйте согласно инструкциям и оплатите билеты.</p>
          <p class="second-title font-weight-light my-4 align-items-start"><span class="font-weight-bold mr-1">7.</span> После успешной оплаты на указанный вами email вы получите электронную версию билета <br> (файлы формата PDF, которые нужно будет распечатать на любом обычном принтере).</p>
          <p class="second-title font-weight-light my-4 align-items-start"><span class="font-weight-bold mr-1">8.</span> Распечатайте ваши билеты на любом обычном принтере. Предъявите билеты на входе <br> (целые листы, не режьте их) </p>
          <a href="{{ route('main') }}" class="btn btn-success my-4">
              на главную
          </a>
        </div>
    </div>

@endsection
