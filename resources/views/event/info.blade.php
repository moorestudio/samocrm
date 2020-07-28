@extends('layouts.app')
@section('share_meta')

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url('info').'/'.$event->id }}">
<meta name="twitter:title" content="Мероприятие: {{ $event->title }}">
<meta name="twitter:description" content="Спикер: {{ $event->mentor }}">
<meta name="twitter:image" content="{{asset('storage/'.$event->image)}}">
{{-- <meta name="twitter:image" content="https://i.kym-cdn.com/entries/icons/original/000/028/861/cover3.jpg"> --}}

<meta property='og:locale' content='ru_RU' />
<!-- <meta property='og:site_name' content=''> -->
<!-- <meta property='og:type' content='article'> -->
<meta property='og:url' content="{{ url('info').'/'.$event->id }}">
<meta property='og:title' content='Мероприятие: {{ $event->title }}'>
<meta property='og:description' content="Спикер: {{ $event->mentor }}">

{{-- <meta property='og:image' content='https://i.kym-cdn.com/entries/icons/original/000/028/861/cover3.jpg'> --}}
<meta property='og:image' content="{{asset('storage/'.$event->image)}}">
@endsection
@section('content')

<?php
$event_currency = \App\CurrencyNames::find($event->currency)->currency;
?>
<div class="container-fluid pt-4">
    <div class="d-flex flex-wrap px-lg-5 justify-content-start">
        <div class="col-12 px-0">
          <p class="main-title text-uppercase mb-4">Информация</p>
        </div>
        <div class="col-12 d-flex flex-wrap px-0 white-bg-round p-lg-5 p-2">
        <div class="col-lg-4 col-12 px-0">
            <img class="w-100" src="{{asset('storage/'.$event->image)}}" alt="">

            {{--@include('_partials.social')--}}
        </div>
        <div class="col-lg-6 col-12 pt-lg-0 pt-4">
            <div class="p-lg-5 p-2 about-course border-0" style="background: #FBFBFB;">
            <p class="name">
                {{ $event->title }}
            </p>
<?php
    
    $sum = collect();

    foreach ($event->rate as $value) {
        $sum->push($value[2]);
        if(isset($value[4])){
            if(isset($value[3]) && $value[3] >= \Carbon\Carbon::now()){
                $sum->push($value[4]);
            }
        };
    }

    // dd(count($sum));
?>

            <div class="mt-3 d-flex flex-wrap">
                <div class="col-lg-6 col-12 px-0">
                  <p class="info-text mb-0">Спикер:</p>
                  <p class="font-weight-bold" style="font-size: 16px;">{{ $event->mentor }}</p>
                </div>
                <div class="col-lg-6 col-12 px-0">
                  <p class="info-text mb-0">Город: </p>
                  <p class="font-weight-bold" style="font-size: 16px;">{{ $event->city }}</p>
                </div>
                <div class="col-lg-4 col-12 px-0">
                  <p class="info-text mb-0">Дата: </p>
                  <p class="font-weight-bold" style="font-size: 16px;">{{ $event->normalDate() }}</p>
                </div>
                <div class="col-lg-4 col-12 px-0">
                  <p class="info-text mb-0">Время: </p>
                  <p class="font-weight-bold" style="font-size: 16px;">{{ $event->normalTime() }}</p>
                </div>
                <div class="col-lg-4 col-12 px-0">
                  <p class="info-text mb-0">Стоимость обучения: </p>
                  <p class="font-weight-bold" style="font-size: 16px;">
                    @if(count($sum)>1)
                        от
                    @endif

                    {{ $sum->min() }} {{$event_currency}}</p>
                </div>
                <div class="col-lg-4 col-12 px-0">
                      <?php
                          // $col = 0;
                          // $counts = \App\Count_buys::where('event_id',$event->id)->get();
                          // foreach ($counts as $count)
                          //     {
                          //         $col = $col + $count->count;
                          //     }
                          //     $ticket_left = $event->ticket_count - $col;

                         $tickets = $event->ticket_count - count($event->tickets()->whereIn('type',['done','buy','reserve'])->get());   
                      ?>

                  @if($tickets > 0)
                      <p class="info-text mb-0">Осталось:</p>
                      <p class="font-weight-bold" style="font-size: 16px;">{{$tickets}} билет (-ов)</p>
                      @else
                      <p class="font-weight-bold" style="font-size: 18px;">Билеты проданы</p>
                  @endif
                </div>
            </div>

                <div class="d-flex flex-wrap pt-3">
                    @if($event->scheme == 1)
                        @if(isset($tickets) && $tickets > 0)
                        <a href="{{ route('buy', ['id' => $event->id]) }}" class="btn btn-success ml-0">
                            Приобрести билет
                        </a>
                        @endif
                    @else
                        @if(isset($tickets) && $tickets > 0)
                            @auth
                                @if(Auth::user()->role_id == 2)
                                    @if(isset($event->buy_deadline) && $event->buy_deadline > \Carbon\Carbon::now())
                                        <div class="col-6 pl-0">
                                            <button class="select-button w-100 BuyTicket" data-parent="buy" data-id="{{$event->id}}">
                                                Купить билет
                                            </button>
                                        </div>
                                    @endif
                                    @if(isset($event->reserve_date) && \Carbon\Carbon::parseFromLocale($event->reserve_date, 'ru') > \Carbon\Carbon::now())
                                        <div class="col-6">
                                            <button class="btn btn-warning m-0 BuyTicket" data-parent="reserve" data-id="{{$event->id}}">
                                                Забронировать билет
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            @else
                                @if(isset($event->buy_deadline) && $event->buy_deadline > \Carbon\Carbon::now())
                                    <div class="col-6 pl-0">
                                        <button class="select-button w-100" data-toggle="modal" data-target="#UnregBuy">
                                            Купить билет
                                        </button>
                                    </div>
                                @endif
                                @if(isset($event->reserve_date) && \Carbon\Carbon::parseFromLocale($event->reserve_date, 'ru') > \Carbon\Carbon::now())
                                    <div class="col-6">
                                        <button class="btn btn-warning m-0" data-toggle="modal"
                                                data-target="#UnregBuy">
                                            Забронировать билет
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        @endif
                    @endif
                    <form class="d-none" action="{{route('buy_page')}}" id="BuyForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="send_id" name="id" value="{{$event->id}}">
                        <input type="hidden" id="send_type" name="type">
                        <input type="hidden" id="send_row" name="row" value="{{null}}">
                        <input type="hidden" id="send_column" name="column" value="{{null}}">
                        <input type="hidden" id="send_price" name="price" value="{{ isset($event->rate[0][3]) && \Carbon\Carbon::now() < \Carbon\Carbon::parse($event->rate[0][3]) ? $event->rate[0][4] : $event->rate[0][4] }}">
                    </form>
                </div>
            </div>
        </div>
        @if($event->latitude && $event->longtitude)
              <div class="col-12 px-0 py-5">
                <p class="main-title mb-0">Мероприятие на карте</p>
              </div>
            <div class="col-lg-9 px-0 col-12 pb-5">
                <div id="map" style="width: 100%; height: 270px;"></div>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row px-lg-5 p-1">
                <div class="col-lg-12 col-12">
            <div class=" pb-4">
                {!! isset($information->first_block) ? $information->first_block : '' !!}
            </div>
                </div>
            </div>
        </div>
            <div class="container-fluid">
                <div class="row p-lg-5 p-1">
                    <div class="col-lg-12 col-12">
                        {!! isset($information->second_block) ? $information->second_block : '' !!}
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row p-lg-5 p-1">
                    <div class="col-lg-6 col-12">
                        {!! isset($information->third_block) ? $information->third_block : '' !!}
                    </div>
                    <div class="col-lg-6 col-12">
                        {!! isset($information->fourth_block) ? $information->fourth_block : '' !!}
                    </div>
                </div>
            </div>

            <div class="container-fluid px-lg-5 px-1 mt-lg-0 mt-4">
                {!! isset($information->fifth_block) ? $information->fifth_block : '' !!}
            </div>
      </div>
    </div>
</div>





{{--@include('modals.scene')--}}
@auth
    @include('modals.buy.order_modal')
@else
    @include('modals.buy.buy_modal')
@endauth

@push('scripts')
@auth
<?php
    if (Session::get('check_sales')!=null && Auth::user()->role_id == 2 && Auth::user()->franchise_id != null){
        echo "<script> $('#checkSales_modal').modal('show') </script>";
    }

 ?>
@endauth
<script>


    $(".reff_link_btn_share").click(function() {

        let  event_id = this.getAttribute('event_id');
        $.ajax({
        type: 'POST',
        url: "/reff_gen",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'event_id': event_id,
        },
        success: function (data) {


            $(".social-buttons").css("display","block");

            let txt = "<a href='https://twitter.com/intent/tweet?url="+data.link+"'>";
            txt+="<i class='fab fa-twitter-square'></i></a>";

            txt+="<a href='https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&amp;st.shareUrl="+data.link+"'>";

            txt+="<i class='fab fa-odnoklassniki-square'></i></a>";
            txt+="<a href='https://www.facebook.com/sharer/sharer.php?u="+data.link+"'>";

            txt+="<i class='fab fa-facebook-square'></i></a>";


            txt+="<a href='http://vk.com/share.php?url="+data.link+"'>";

            txt+="<i class='fab fa-vk'></i></a>";


            $(".social-buttons").html(txt);
        },
    });

    })

    var popupSize = {
        width: 780,
        height: 550
    };

    $(document).on('click', '.social-buttons > a', function(e){

        var
            verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

        var popup = window.open($(this).prop('href'), 'social',
            'width='+popupSize.width+',height='+popupSize.height+
            ',left='+verticalPos+',top='+horisontalPos+
            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

        if (popup) {
            popup.focus();
            e.preventDefault();
        }

    });
</script>
@endpush
@endsection
@push('scripts')
    @if($event->scheme == 0)
        <script>
            $('.BuyTicket').on('click', function (e) {

                    var btn = $(e.currentTarget);
                    var type = btn.data('parent');

                    $('#send_type').val(type);

                    $('#BuyForm').submit();
            })
        </script>
    {{--<script>--}}
        {{--$('.buy-ticket').on('click', function (e) {--}}
            {{--var btn = $(e.currentTarget);--}}
            {{--var event_id = btn.data('id');--}}
            {{--var type = btn.data('parent');--}}
            {{--var price = btn.data('parent2');--}}
            {{--$('.buy-ticket').addClass('loading');--}}

                {{--if(type == 'buy') {--}}
                    {{--$.ajax({--}}
                        {{--url: '{{ route('buy_ticket') }}',--}}
                        {{--method: 'POST',--}}
                        {{--data: {--}}
                            {{--"_token": "{{ csrf_token() }}",--}}
                            {{--"price": price,--}}
                            {{--"event_id": event_id,--}}
                        {{--},--}}
                        {{--success: data => {--}}
                            {{--if (data.status == 'success') {--}}
                                {{--$('#OrderModal').modal('hide');--}}
                                {{--Swal.fire({--}}
                                    {{--position: 'center',--}}
                                    {{--icon: 'success',--}}
                                    {{--title: 'Покупка совершена!',--}}
                                    {{--// description: ' Каждый пользователь может купить лишь 1 билет ' ,--}}
                                {{--});--}}
                                {{--$('.buy-ticket').removeClass('loading');--}}
                            {{--}--}}
                            {{--else if (data.status == 'error') {--}}
                                {{--if (data.check == 0) {--}}
                                    {{--Swal.fire({--}}
                                        {{--position: 'center',--}}
                                        {{--icon: 'info',--}}
                                        {{--title: 'Вы уже покупали билет!',--}}
                                        {{--// description: ' Каждый пользователь может купить лишь 1 билет ' ,--}}
                                        {{--showConfirmButton: true,--}}
                                    {{--});--}}
                                {{--}--}}
                                {{--else if (data.check = 1) {--}}
                                    {{--Swal.fire({--}}
                                        {{--position: 'center',--}}
                                        {{--icon: 'info',--}}
                                        {{--title: 'У вас есть забронированный билет!',--}}
                                        {{--description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',--}}
                                        {{--showConfirmButton: true,--}}
                                    {{--});--}}
                                {{--}--}}
                                {{--$('.buy-ticket').removeClass('loading');--}}
                            {{--}--}}


                        {{--},--}}
                        {{--error: () => {--}}
                            {{--$('.buy-ticket').removeClass('loading');--}}
                        {{--}--}}
                    {{--})--}}
                {{--}--}}
                {{--else if(type == 'reserve')--}}
                {{--{--}}
                    {{--$.ajax({--}}
                        {{--url: '{{ route('reserve_ticket') }}',--}}
                        {{--method: 'POST',--}}
                        {{--data: {--}}
                            {{--"_token": "{{ csrf_token() }}",--}}
                            {{--"event_id": event_id,--}}
                            {{--"price": price,--}}
                        {{--},--}}
                        {{--success: data => {--}}
                            {{--if (data.status == 'success')--}}
                            {{--{--}}
                                {{--$('#OrderModal').modal('hide');--}}
                                {{--Swal.fire({--}}
                                    {{--position: 'center',--}}
                                    {{--icon: 'success',--}}
                                    {{--title: 'Билет забронирован!',--}}
                                    {{--// description: ' Каждый пользователь может купить лишь 1 билет ' ,--}}
                                {{--});--}}
                                {{--$('.buy-ticket').removeClass('loading');--}}

                            {{--}--}}
                            {{--else if (data.status == 'error')--}}
                            {{--{--}}
                                {{--if (data.check == 0) {--}}
                                    {{--Swal.fire({--}}
                                        {{--position: 'center',--}}
                                        {{--icon: 'info',--}}
                                        {{--title: 'Вы уже покупали билет!',--}}
                                        {{--// description: ' Каждый пользователь может купить лишь 1 билет ' ,--}}
                                        {{--showConfirmButton: true,--}}
                                    {{--});--}}
                                {{--}--}}
                                {{--else if (data.check = 1) {--}}
                                    {{--Swal.fire({--}}
                                        {{--position: 'center',--}}
                                        {{--icon: 'info',--}}
                                        {{--title: 'У вас есть забронированный билет!',--}}
                                        {{--description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',--}}
                                        {{--showConfirmButton: true,--}}
                                    {{--});--}}
                                {{--}--}}
                                {{--$('.buy-ticket').removeClass('loading');--}}
                            {{--}--}}
                        {{--},--}}
                        {{--error: () => {--}}
                        {{--}--}}
                    {{--})--}}
                {{--}--}}
        {{--})--}}
    {{--</script>--}}
    {{--<script>--}}
        {{--$('.reserve-ticket').on('click', function (e) {--}}
            {{--var btn = $(e.currentTarget);--}}
            {{--var place = $('.place-choice');--}}
            {{--var row = place.data('parent');--}}
            {{--var event_id = btn.data('id');--}}
            {{--var column = place.data('parent2');--}}

            {{--$.ajax({--}}
                {{--url: '{{ route('reserve_ticket') }}',--}}
                {{--method: 'POST',--}}
                {{--data: {--}}
                    {{--"_token": "{{ csrf_token() }}",--}}
                    {{--"row": row,--}}
                    {{--"column": column,--}}
                    {{--"event_id": event_id,--}}
                {{--},--}}
                {{--success: data => {--}}
                    {{--if (data.status == 'success')--}}
                    {{--{--}}
                        {{--place.addClass('reserve');--}}

                    {{--}--}}
                    {{--else if (data.status == 'error')--}}
                    {{--{--}}
                        {{--Swal.fire({--}}
                            {{--position: 'center',--}}
                            {{--icon: 'info',--}}
                            {{--title: 'У вас уже есть бронь!',--}}
                            {{--// description: ' Каждый пользователь может купить лишь 1 билет ' ,--}}
                            {{--showConfirmButton: true,--}}
                        {{--});--}}
                        {{--place.removeClass('place-choice');--}}
                    {{--}--}}


                {{--},--}}
                {{--error: () => {--}}
                {{--}--}}
            {{--})--}}
        {{--})--}}
    {{--</script>--}}
    {{--<script>--}}
        {{--$('#agree').on('click', function (e) {--}}
            {{--let btn = $(e.currentTarget);--}}
            {{--if(btn.prop("checked") == true)--}}
            {{--{--}}
                {{--$('.agree_check').removeClass('agree_block')--}}
            {{--}--}}
            {{--else {--}}
                {{--$('.agree_check').addClass('agree_block');--}}
            {{--}--}}
        {{--})--}}
    {{--</script>--}}
    @endif
    <script type="text/javascript">
        // Функция ymaps.ready() будет вызвана, когда
        // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
        ymaps.ready(init);
        function init(){
            // Создание карты.
            var myMap = new ymaps.Map("map", {
                // Координаты центра карты.
                // Порядок по умолчанию: «широта, долгота».
                // Чтобы не определять координаты центра карты вручную,
                // воспользуйтесь инструментом Определение координат.
                center: [{{ $event->latitude ?? 42.865388923088396 }}, {{ $event->longtitude ?? 74.60104350048829 }}],
                // Уровень масштабирования. Допустимые значения:
                // от 0 (весь мир) до 19.
                zoom: 15
            });

            myMap.geoObjects.add(new ymaps.Placemark([{{ $event->latitude ?? 42.865388923088396 }}, {{ $event->longtitude ?? 74.60104350048829 }}], {
                balloonContent: '{{ $event->fullName }}'
            }, {
                preset: 'islands#icon',
                iconColor: '#0095b6'
            }))
        }
    </script>
@endpush
