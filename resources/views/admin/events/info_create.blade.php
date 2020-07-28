@extends('layouts.admin_app')
@section('content')

<div class="container-fluid pt-4">
    <form class="row p-lg-5 p-1" action="{{ route('event_info_store') }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <div class="col-lg-4 col-12">
            <img class="w-100" style="box-shadow: 0px 4px 50px rgba(247, 185, 27, 0.4);" src="{{asset('storage/'.$event->image)}}" alt="">
            <div class="d-flex pt-3">
                {{--<a class="w-50 mr-2" href="/buy">--}}
                <a href="{{ route('buy', ['id' => $event->id]) }}" class="btn w-100 py-3" style="background-color: #ffbb33; pointer-events: none;">
                    Приобрести билет
                </a>
                {{--</a>--}}
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <h2 class="info-header font-weight-bold">
                {{ $event->title }}
            </h2>

            <div class="mt-3">
                <p class="info-text">Тренер: <span class="font-weight-bold">{{ $event->title }}</span></p>
                <p class="info-text">Город: <span class="font-weight-bold">{{ $event->city }}</span></p>
                <p class="info-text">Дата: <span class="font-weight-bold">{{ $event->date }}</span></p>
                <p class="info-text">Стоимость обучения: <span class="font-weight-bold">{{ $event->price }}</span></p>
                <p class="info-text">Стоимость организационных расходов: <span class="font-weight-bold">500 долларов США</span></p>
            </div>

        </div>
        <div class="col-12">
            <p class="h5 font-weight-bold text-center pt-3">
                Первый блок информации
            </p>
            <div class="md-form">
                <textarea class="tiny_area" name="first_block" id="tiny_area" cols="30" rows="10" >{{ isset($information) ? $information->first_block : '' }}</textarea>
            </div>
        </div>
<!--         <div class="col-12">
            <p class="h5 font-weight-bold text-center pt-4">
                Второй блок информации
            </p>
            <div class="md-form">
                <textarea class="tiny_area" name="second_block" id="tiny_area" cols="30" rows="10">{{ isset($information) ? $information->second_block : '' }}</textarea>
            </div>
        </div> -->
        <div class="col-6">
            <p class="h5 font-weight-bold text-center pt-4">
                Третий блок информации
            </p>
            <div class="md-form">
                <textarea class="tiny_area" name="third_block" id="tiny_area" cols="30" rows="10">{{ isset($information) ? $information->third_block : '' }}</textarea>
            </div>
        </div>
        <div class="col-6">
            <p class="h5 font-weight-bold text-center pt-4">
                Четвертый блок информации
            </p>
            <div class="md-form">
                <textarea class="tiny_area" name="fourth_block" id="tiny_area" cols="30" rows="10">{{ isset($information) ? $information->fourth_block : '' }}</textarea>
            </div>
        </div>
<!--         <div class="col-12">
            <p class="h5 font-weight-bold text-center pt-4">
                Пятый блок информации
            </p>
            <div class="md-form">
                <textarea class="tiny_area" name="fifth_block" id="tiny_area" cols="30" rows="10">{{ isset($information) ? $information->fifth_block : '' }}</textarea>
            </div>
        </div> -->

        <div class="text-center py-4">
            <button class="btn btn-warning" type="submit">
                Отправить
            </button>
        </div>
    </form>

</div>


@endsection