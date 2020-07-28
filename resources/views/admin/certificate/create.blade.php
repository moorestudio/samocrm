@extends('layouts.app')
@section('content')
    <div class="loading-preload" style="display: none;">
        <div style="top:0%; left:0%; width:100vw; height:100vh; position: fixed; z-index: 9999999999; background: rgba(0,0,0,0.3); display:flex; align-items: center;justify-content: center;">
            <img style="width:200px; height:140px;" src="{{asset('images/loading.gif')}}" alt="">
        </div>
    </div>
{{--    @dd($cerfDesign->name_style['font_size'])--}}
    <div class="container mb-5">
        <div class="row px-0 my-4">
          <p class="main-title text-uppercase text-lg-left text-center mb-0">Конструктор сертификата</p>
        </div>
        <div class="row white-bg-round pt-2 pb-4">
            <div class="col-lg-8 col-12 position-relative">
                <div class="cerf position-relative" style="top:10%; border: 1px solid #1e2433; background:white; background-image:url({{ asset('images/certificate.jpg') }}); background-size:100% 100%; width:80%; height:385px;">
                    <div style="position: absolute; left:0%; top:45%; width:100%;">
                        <div style="position: relative; text-align: center;">
                            <p id="cerf_user" style="margin-bottom:0px; color:#333333; line-height: 100%; font-size: {{isset($cerfDesign) ? $cerfDesign->name_style['font_size'].'px' : '18px' }}; font-weight:bold;">Мамытовой Мээрим</p>
                        </div>
                    </div>
                    <p id="cerf_date" style="margin-bottom:0px; color:#333333; position: absolute; left:48%; top:64%; font-size: {{isset($cerfDesign) ? $cerfDesign->date_style['font_size'].'px' : '12px' }}; font-weight:bold;">{{$event->normalDate()}} - {{$event->normalEndDate()}}</p>
                    <div style="position: absolute; left:0%; top:50%; width:100%;">
                        <div class="d-flex align-items-center justify-content-center" style="position: relative; text-align: center; height:58px;">
                        <p id="cerf_title" style="margin-bottom:0px; color:#1e2433; font-size: {{isset($cerfDesign) ? $cerfDesign->title_style['font_size'].'px' : '12px' }}; font-weight: bold;">{{isset($cerfDesign) ? $cerfDesign->title_style['content'] : $event->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 p-3 pr-5">
                <p class="h5 font-weight-bold text-uppercase mt-3">Мероприятие</p>
                <div class="mt-3">
                    <label for="title_content">Текст заголовка</label>
                    <input class="w-100 input-style form-control" type="text" id="title_content" value="{{isset($cerfDesign) ? $cerfDesign->title_style['content'] : $event->title}}">
                </div>
                <div class="mt-3">
                    <label for="title_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="title_font_size" value="{{isset($cerfDesign) ? $cerfDesign->title_style['font_size'] : '12'}}">
                </div>

                <p class="h5 font-weight-bold text-uppercase mt-4">Получатель</p>
                <div class="mt-3">
                    <label for="user_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="user_font_size" value="{{isset($cerfDesign) ? $cerfDesign->name_style['font_size'] : '18'}}">
                </div>


                <p class="h5 font-weight-bold text-uppercase mt-4">Дата мероприятия</p>
                <div class="mt-3">
                    <label for="date_font_size">Размер шрифта</label>
                    <input class="w-100 input-style form-control" type="number" id="date_font_size" value="{{isset($cerfDesign) ? $cerfDesign->date_style['font_size'] : '12'}}">
                </div>
                <div class="mt-5 d-flex">
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
        $('#title_content').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            $('#cerf_title').html(input.val());
        })
    </script>
    <script>
        $('#title_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('cerf_title').style.fontSize = input.val() + 'px';
        })
    </script>

    <script>
        $('#date_font_size').on('keyup click', function (e){
            var input = $(e.currentTarget);
            document.getElementById('cerf_date').style.fontSize = input.val() + 'px';
        })
    </script>

    <script>
        $('#user_font_size').on('keyup click', function (e) {
            var input = $(e.currentTarget);
            document.getElementById('cerf_user').style.fontSize = input.val() + 'px';
        })
    </script>


    <script>
        $('#save-construct').on('click', function (e) {
            var btn = $(e.currentTarget);
            btn.hide();
            $('.loading-preload').show(0);


            var id = btn.data('id');

            var title_content = $('#title_content').val();
            var title_font_size = $('#title_font_size').val();

            var date_font_size = $('#date_font_size').val();

            var user_font_size = $('#user_font_size').val();


            $.ajax({
                url: '{{ route('store_certificate') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "title_content": title_content,
                    "title_font_size": title_font_size,
                    "date_font_size": date_font_size,
                    "user_font_size": user_font_size,
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
