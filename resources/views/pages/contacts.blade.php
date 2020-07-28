@extends('layouts.app')

@section('content')

<div class="container py-4">
    <p class="main-title text-uppercase">Наши контакты</p>

    <div class="d-flex flex-wrap mt-4 white-bg-round p-lg-5 p-2">
        <div class="col-lg-3 col-12">
            <div class="font-weight-bold">
                <span>Наш адрес</span>
            </div>
            <div class="contact mt-2">
                <p class="font-weight-light mb-1 scene-place-title">
                    Кыргызстан, г.Бишкек
                </p>
                <p class="font-weight-light scene-place-title">
                    ул. Ибраимова 113/2 720000
                </p>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="font-weight-bold">
                <span>Наш контакты</span>
            </div>
            <div class="phones mt-2">
                <p class="mb-1">
                  <img src="{{asset('images/ru.svg')}}" alt=""> <span class="ml-2 scene-place-title">+7 495 108 1225</span>
                </p>
                <p class="mb-1">
                  <img src="{{asset('images/kz.svg')}}" alt=""> <span class="ml-2 scene-place-title">+7 727 349 6960</span>
                </p>
                <p class="mb-1">
                  <img src="{{asset('images/kg.svg')}}" alt=""> <span class="ml-2 scene-place-title">+996 555 891 322</span>
                </p>
            </div>
          </div>
          <div class="col-lg-4 col-12" style="padding-top:29px;">
            <p class="mb-1">
                <img src="{{asset('images/whatsapp.svg')}}" alt=""> <span class="ml-2 scene-place-title">+7 727 349 6960</span>
            </p>
            <p class="mb-1">
                <img src="{{asset('images/email.svg')}}" alt=""> <span class="ml-2 scene-place-title"><a
                            href="mailto:info@worldsamo.com">info@worldsamo.com</a></span>
            </p>
          </div>
        <div class="col-lg-8 col-12 mt-4">
            <div class="font-weight-bold mb-5">
                <span>Заявка</span>
            </div>
            <div class="p-0 mt-2">
                <div class="row">
                    <div class="col-lg-6 col-12 mt-lg-0 mt-3">
                        <label for="name">ФИО</label>
                        <input class="form-control input-style grey-bg" type="text" placeholder="Введите ФИО" id="name" required>
                    </div>
                    <div class="col-lg-6 col-12 mt-lg-0 mt-3">
                        <label for="name">Номер телефона</label>
                        <input class="form-control input-style grey-bg" type="tel" placeholder="Введите Номер телефона" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                    </div>
                    <div class="col-lg-6 col-12 mt-3">
                        <label for="name">Тип заявки</label>
                        <select class="form-control input-style  grey-bg" id="type"  required>
                            <option value="Рекомендация по улучшению">Рекомендация по улучшению</option>
                            <option value="Претензия">Претензия</option>
                            <option value="Обратная связь">Обратная связь</option>
                            <option value="Сотрудничество по франшизе">Сотрудничество по франшизе</option>
                            <option value="Сотрудничество по партнеру">Сотрудничество по партнеру</option>
                            <option value="Поменять своего продавца">Поменять своего продавца</option>
                            <option value="Другое">Другое</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-12 mt-3">
                        <label for="name">Ваш E-mail</label>
                        <input class="form-control input-style grey-bg" type="email" placeholder="Введите email" id="email"  required>
                    </div>
                    <div class="col-12 mt-3 mb-lg-0 mb-3">
                        <label for="name">Комментарий</label>
                        <textarea class="form-control input-style grey-bg" type="text" rows="10" placeholder="Ваш комментарий" id="comment"  required></textarea>
                    </div>
                </div>
            </div>
            <div class="text-left my-3">
                <button class="select-button px-5 send">
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
        $('.send').click(function (e) {
            var btn = $(e.currentTarget);
            var name = $('#name');
            var phone = $('#phone');
            var type = $('#type');
            var email = $('#email');
            var comment = $('#comment');
            var comment_length = comment.val();

            if (name.val() == ''){
                
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Заполните ФИО!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });

            }else if (phone.val() == ''){
                
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Заполните номер телефона!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });

            }else if (email.val() == ''){
                
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Заполните Email!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });

            }else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.val()))){
                
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Введите правильный Email!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });

            }else{
                btn.addClass('loading');
                $.ajax({
                    url: '{{ route('contact_send') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name.val(),
                        "phone": phone.val(),
                        "type": type.val(),
                        "email": email.val(),
                        "comment": comment.val(),
                    },
                    success: data => {

                        name.val('');
                        phone.val('');
                        type.val('');
                        email.val('');
                        comment.val('');

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Заявка отправлена!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: true,
                            });
                        btn.removeClass('loading');
                    },
                    error: () => {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Произошла ошибка!',
                            // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                            showConfirmButton: true,
                        });
                        btn.removeClass('loading');
                    }
                })   
            }
        })
    </script>
@endpush
