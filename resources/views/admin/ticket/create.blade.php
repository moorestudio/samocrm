@extends('layouts.app')
@section('content')
    <div class="loading-preload" style="display: none;">
    <div style="top:0%; left:0%; width:100vw; height:100vh; position: fixed; z-index: 9999999999; background: rgba(0,0,0,0.3); display:flex; align-items: center;justify-content: center;">
        <img style="width:200px; height:140px;" src="{{asset('images/loading.gif')}}" alt="">
    </div>
    </div>
{{--@dd($ticketDesign->image_style)--}}
    <div class="container my-4">
        <div class="row px-0 mb-4">
          <p class="main-title text-uppercase text-lg-left text-center mb-1">Конструктор билета</p>
        </div>
        <div class="row white-bg-round p-4">
            <div class="col-lg-8 col-12 position-relative">
                <div class="col-lg-12 md-form px-0">
                    <textarea class="tiny_area ticket_text_block" name="ticket_text_block" id="tiny_area" cols="30" rows="10">{{ isset($ticketDesign) ? $ticketDesign->text_block : '' }}</textarea>
                </div>
                <div class="ticket d-flex ticket-style">
                  <div class="ticket-first-b d-flex flex-column justify-content-between pb-4 position-relative">
                    <img class="position-absolute  w-100" src="{{ asset('images/left-blue.png') }}" alt="" style="z-index:1;">
                    <div class="d-flex justify-content-center h-25 position-relative" style="background: transparent;z-index:2;"><img src="{{asset('images/bg-samo.svg')}}" alt=""></div>
                    <div class="d-flex justify-content-center align-items-end h-75 position-relative" style="background: transparent;z-index:2;"><img src="{{asset('images/samo-text.svg')}}" alt=""> </div>
                  </div>
                  <div class="ticket-second-b d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between flex-wrap">
                        <p class="title">Номер билета: № 8927473987</p>
                        <p class="title">билет на имя <span class="ticket-owner-name">Барыктабасов В.Э.</span></p>
                        <p id="ticket_title" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->title_style['font_size'].'px;' : ''}}" class="w-100 mb-0">{{isset($ticketDesign) ? $ticketDesign->title_style['content'] : $event->title }}</p>
                    </div>
                    <div class="d-flex">
                        <div class="description d-flex flex-wrap align-items-center py-4 px-2">
                            <div class="col-5 px-1 h-100">
                                <p class="title mb-1">место проведения</p>
                                <p id="ticket_address" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->address_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->address_style['content'] : $event->address}}</p>
                            </div>
                            <div class="col-3 px-1 h-100">
                                <p class="title mb-1">город</p>
                                <p id="ticket_city" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->city_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->city_style['content'] :$event->city}}</p>
                                @if($event->scheme)
                                    <p class="mb-0 default mt-2">10 <span class="title">ряд</span></p>
                                @endif
                            </div>
                            <div class="col-4 px-1 h-100">
                                <p class="title mb-1">категория</p>
                                <p id="ticket_tarif" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->city_style['font_size'].'px;' : '' }}">Standart</p>
                                @if($event->scheme)
                                    <p class="mb-0 default mt-2">8 <span class="title">место</span></p>
                                @endif
                            </div>
                        </div>
                        <div class="qr-code d-flex justify-content-center">
                            <img src="{{ asset('images/test-qr.png') }}" alt="">
                        </div>
                    </div>
                    <div class="d-flex">
                        <p id="ticket_date" style="{{isset($ticketDesign) ? 'font-size:'.$ticketDesign->date_style['font_size'].'px;' : '' }}">{{isset($ticketDesign) ? $ticketDesign->date_style['content'] :$event->date}}</p>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 p-3 bg-white">
                <p class="h5 font-weight-bold mt-3">Заголовок</p>
                <div class="mt-3">
                <label for="title_content">Текст заголовка</label>
                <input class="w-100 input-style form-control" type="text" id="title_content" value="{{isset($ticketDesign) ? $ticketDesign->title_style['content'] : $event->title}}">
                </div>
                <div class="mt-3">
                    <label for="title_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="title_font_size" value="{{isset($ticketDesign) ? $ticketDesign->title_style['font_size'] : '17'}}">
                </div>

                <p class="h5 font-weight-bold mt-4">Адрес</p>
                <div class="mt-3">
                    <label for="address_content">Текст адреса</label>
                    <input class="w-100 input-style form-control" type="text" id="address_content" value="{{isset($ticketDesign) ? $ticketDesign->address_style['content'] : $event->address}}">
                </div>

                <div class="mt-3">
                    <label for="address_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="address_font_size" value="{{isset($ticketDesign) ? $ticketDesign->address_style['font_size'] : '12'}}">
                </div>


                <p class="h5 font-weight-bold mt-4">Дата мероприятия</p>
                <div class="mt-3">
                    <label for="date_content">Текст даты</label>
                    <input class="w-100 input-style form-control" type="text" id="date_content" value="{{isset($ticketDesign) ? $ticketDesign->date_style['content'] : $event->date}}">
                </div>

                <div class="mt-3">
                    <label for="date_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="date_font_size" value="{{isset($ticketDesign) ? $ticketDesign->date_style['font_size'] : '12'}}">
                </div>


                <p class="h5 font-weight-bold mt-4">Город</p>
                <div class="mt-3">
                    <label for="city_content">Текст города</label>
                    <input class="w-100 input-style form-control" type="text" id="city_content" value="{{isset($ticketDesign) ? $ticketDesign->city_style['content'] : $event->city}}">
                </div>

                <div class="mt-3">
                    <label for="city_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="city_font_size" value="{{isset($ticketDesign) ? $ticketDesign->city_style['font_size'] : '12'}}">
                </div>
                <div class="d-flex mt-5">
                    <button class="btn btn-success ml-0" id="save-construct" data-id="{{$event->id}}">
                      Сохранить
                    </button>
                    <a class="btn btn-cancel li-btn mr-0" href="/admin">
                      Отмена
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $('#image_width').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_image').style.width = input.val() + '%';
        })
    </script>
    <script>
        $('#image_height').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_image').style.height = input.val() + '%';
        })
    </script>


    <script>
        $('#title_content').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            $('#ticket_title').html(input.val());
        })
    </script>
    <script>
        $('#title_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_title').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#title_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_title').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#title_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_title').style.paddingBottom = input.val() + 'px';
        })
    </script>



    <script>
        $('#address_content').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            $('#ticket_address').html(input.val());
        })
    </script>
    <script>
        $('#address_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_address').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#address_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_address').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#date_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_date').style.paddingBottom = input.val() + 'px';
        })
    </script>

    <script>
        $('#date_content').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            $('#ticket_date').html(input.val());
        })
    </script>
    <script>
        $('#date_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_date').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#date_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_date').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#address_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_address').style.paddingBottom = input.val() + 'px';
        })
    </script>

    <script>
        $('#city_content').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            $('#ticket_city').html(input.val());
        })
    </script>
    <script>
        $('#city_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_city').style.fontSize = input.val() + 'px';
            document.getElementById('ticket_tarif').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#city_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_city').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#city_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_city').style.paddingBottom = input.val() + 'px';
        })
    </script>



    <script>
        $('#price_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_price').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#price_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_price').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#price_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_price').style.paddingBottom = input.val() + 'px';
        })
    </script>

    <script>
        $('#places_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_places').style.fontSize = input.val() + 'px';
        })
    </script>
    <script>
        $('#places_font_weight').on('change', function (e) {
            var select = $(e.currentTarget);
            document.getElementById('ticket_places').style.fontWeight = select.val();
        })
    </script>
    <script>
        $('#places_padding_bottom').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('ticket_places').style.paddingBottom = input.val() + 'px';
        })
    </script>


    <script>
        tinymce.init({
            selector: '.tiny_area',
            mobile: {
                menubar: true
            }
        });
        $('#save-construct').on('click', function (e) {
            var btn = $(e.currentTarget);
            btn.hide();
            $('.loading-preload').show(0);


            var id = btn.data('id');
            var image_width = $('#image_width').val();
            var image_height = $('#image_height').val();

            var title_content = $('#title_content').val();
            var title_font_size = $('#title_font_size').val();
            var title_font_weight = $('#title_font_weight').val();
            var title_padding_bottom = $('#title_padding_bottom').val();

            var address_content = $('#address_content').val();
            var address_font_size = $('#address_font_size').val();
            var address_font_weight = $('#address_font_weight').val();
            var address_padding_bottom = $('#address_padding_bottom').val();

            var date_content = $('#date_content').val();
            var date_font_size = $('#date_font_size').val();
            var date_font_weight = $('#date_font_weight').val();
            var date_padding_bottom = $('#date_padding_bottom').val();

            var city_content = $('#city_content').val();
            var city_font_size = $('#city_font_size').val();
            var city_font_weight = $('#city_font_weight').val();
            var city_padding_bottom = $('#city_padding_bottom').val();

            var price_font_size = $('#price_font_size').val();
            var price_font_weight = $('#price_font_weight').val();
            var price_padding_bottom = $('#price_padding_bottom').val();
            var ticket_text_block = tinymce.get('tiny_area').getContent();

            $.ajax({
                url: '{{ route('store_ticket') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "image_width": image_width,
                    "image_height": image_height,
                    "title_content": title_content,
                    "title_font_size": title_font_size,
                    "title_font_weight": title_font_weight,
                    "title_padding_bottom": title_padding_bottom,
                    "address_content": address_content,
                    "address_font_size": address_font_size,
                    "address_font_weight": address_font_weight,
                    "address_padding_bottom": address_padding_bottom,
                    "date_content": date_content,
                    "date_font_size": date_font_size,
                    "date_font_weight": date_font_weight,
                    "date_padding_bottom": date_padding_bottom,
                    "city_content": city_content,
                    "city_font_size": city_font_size,
                    "city_font_weight": city_font_weight,
                    "city_padding_bottom": city_padding_bottom,
                    "price_font_size": price_font_size,
                    "price_font_weight": price_font_weight,
                    "price_padding_bottom": price_padding_bottom,
                    "ticket_text_block":ticket_text_block,
                },
                success: data => {
                    $('.loading-preload').hide(0);
                    btn.show();
                },
                error: () => {
                }
            })
        })
    </script>
@endpush
