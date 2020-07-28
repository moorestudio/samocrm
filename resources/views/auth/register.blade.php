@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="padding-top: 5%;">
            <div class="pb-4 card register-form" style="margin-top:3%;">

                {{-- <div class="card-header reg_title">Регистрация</div> --}}

                <div class="d-flex justify-content-between">
                    <div class="reg_title" style="width:280px;">Регистрация</div>
                    <div class="title-reg2">
                        <span class="reg_step reg_step-dop">Шаг 01 из 02</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                <div class="card-body reg_card_wrapper" id="reg_page_1">


                        @csrf

                            <div class="md-form form-group">
                                <label for="name">Имя*</label>
                                <input type="text" placeholder="Имя пользователя" name="name" id="name" class="form-control form-control2 text-dark @error('name') is-invalid @enderror" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="md-form">
                                <label for="last_name">Фамилия*</label>
                                <input type="text" placeholder="Фамилия пользователя" name="last_name" id="last_name" class="form-control form-control2 text-dark @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"  autocomplete="last_name" autofocus>
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="md-form">
                                <label for="middle_name">Отчество*</label>
                                <input type="text" placeholder="Отчество пользователя" name="middle_name" id="middle_name" class="form-control form-control2 text-dark @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') }}"  autocomplete="middle_name" autofocus>
                                @error('middle_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                @enderror
                            </div>
                                <div class="md-form">
                                    <label for="city">Город*</label>
                                    <input type="text" placeholder="Город" name="city" id="city" class="form-control form-control2 text-dark @error('city') is-invalid @enderror" value="{{ old('city') }}"  autocomplete="city" autofocus>
                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="city_reg_error_message"></strong>
                                        <script>
                                        document.getElementById("city_reg_error_message").innerHTML+='{{ $message }}'.replace("city", "город")
                                        </script>
                                    </span>
                                    @enderror
                                </div>
                                <div class="md-form">
                                    <label for="country">Страна*</label>
                                    <input type="text" name="country" id="country" class="form-control form-control2 text-dark @error('country') is-invalid @enderror" value="{{ old('country') }}"  autocomplete="country" autofocus>
                                    @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div style="padding-top:5%;">

                                    <button onclick="display_next()" type="button" class="reg_btn3">Далее</button>
                                </div>
                        </div>
                        <div class="card-body reg_card_wrapper" id="reg_page_2" style="display: none;">
                            <div class="md-form">
                                <label for="email">Ваш email*</label>
                                <input type="text" placeholder="Ваш email" name="email" id="email" class="form-control form-control2 text-dark @error('email') is-invalid @enderror" value="{{ old('email') }}"  autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="md-form">
                                <label for="contacts">Номер телефона*</label>
                                <input type="text" placeholder="Номер телефона" name="contacts" id="telephone" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 43' class="tel_input form-control form-control2 text-dark @error('contacts') is-invalid @enderror" value="{{ old('contacts') }}"  autocomplete="contacts" placeholder="Например: +996 500 000 000" autofocus style="width: 88%;">
                                @error('contacts')
                                <span class="invalid-feedback" role="alert" style="display: block;">
                                        <strong id="contacts_reg_error_message"></strong>
                                        <script>
                                        document.getElementById("contacts_reg_error_message").innerHTML+='{{ $message }}'.replace("contacts", "# телефона")
                                        </script>

                                    </span>
                                @enderror
                            </div>



                                <div class="md-form">
                                    <label for="job">Ваша должность*</label>
                                    <input type="text" placeholder="Ваша должность" name="job" id="job" class="form-control form-control2 text-dark @error('job') is-invalid @enderror" value="{{ old('job') }}"  autocomplete="job" autofocus>
                                    @error('job')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="md-form">
                                    <label for="company">Ваша компания*</label>
                                    <input type="text" placeholder="Ваша компания" name="company" id="company" class="form-control form-control2 text-dark @error('company') is-invalid @enderror" value="{{ old('company') }}"  autocomplete="company" autofocus>
                                    @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="md-form mt-0">
                                <label for="company">Деятельность</label>
                                    <select class="browser-default custom-select" name="work_type" id="work_type">
                                        <option selected>Ваша деятельность</option>
                                        <option value="Строительство" {{ old('work_type') == "Строительство" ? 'selected' : '' }}>Строительство</option>
                                        <option value="Бизнес" {{ old('work_type') == "Бизнес" ? 'selected' : '' }}>Бизнес</option>
                                        <option value="Маркетинг" {{ old('work_type') == "Маркетинг" ? 'selected' : '' }}>Маркетинг</option>
                                        <option value="IT сфера" {{ old('work_type') == "IT сфера" ? 'selected' : '' }}>IT сфера</option>
                                        <option value="Хозяйственная отрасль" {{ old('work_type') == "Хозяйственная отрасль" ? 'selected' : '' }}>Хозяйственная отрасль</option>
                                        <option value="Сфера услуг" {{ old('work_type') == "Хозяйственная отрасль" ? 'selected' : '' }}>Сфера услуг</option>
                                        <option value="Творческая деятельность" {{ old('work_type') == "Творческая деятельность" ? 'selected' : '' }}>Творческая деятельность</option>
                                        <option value="СМИ" {{ old('work_type') == "СМИ" ? 'selected' : '' }}>СМИ</option>
                                        <option value="6" {{ old('work_type') == "6" ? 'selected' : '' }}>Другое (ввести)</option>
                                    </select>
                                </div>
                                <div id="other_work_type_form" class="md-form" style="display: none;">
                                    <label for="other_work_type">Введите Вашу деятельность</label>
                                    <input type="text" name="other_work_type" id="other_work_type" class="form-control text-dark @error('other_work_type') is-invalid @enderror" value="{{ old('other_work_type') }}"  autocomplete="other_work_type" autofocus>
                                    @error('other_work_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>это поле обязательно для заполнения.</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-check" style="    padding-top: 4%;padding-bottom: 2%;">

                                  <input style="width: unset;    margin-top: 1%;" class="form-check-input agree" type="checkbox" value="">
                                  <a href="{{ asset('/doc/user_agree.pdf') }}" target="_blank" style="cursor:pointer; color:black;">
                                    Согласен с <span style="color:#189ddf;font-size: 15px;">пользовательским соглашением
                                  </span></a>
                                </div>

                                <div class="d-flex">
                                    <img class="reg_step reg_step_back" onclick="display_prev()" style="width: 30px;padding-top: 1.5%;padding-bottom: 1.5%;" src="{{ asset('images/left12.svg') }}" alt="">

                                     <button onclick="display_prev()" type="button" class="btn-prev-reg btn reg_btn reg-btn2 .text-nowrap" style="width:34%;">Назад</button>
                                    <button class="btn reg_btn agree_check agree_block" type="submit" style="width:64%;box-shadow: none;">Зарегистрироваться</button>

                                </div>
                                <p style="color:#A6ACBE;    padding-top: 5%;    font-size: 13px;">Пароль будет автоматически сгенерирован и отправлен в Вашу почту вместе с ссылкой для подтверждения почты. После регистрации Ваши действия будут ограничены пока почта не будет подтверждена!</p>

                    </div>
                </form>

            </div>
        </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="user_agreement_modal" tabindex="-1" role="dialog" aria-labelledby="user_agreement_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content pl-5 pr-5 pb-4 pt-4">

          <div class="modal-header pl-0 pr-0 pt-0 pb-4"  style="border: none;">
            <h5 class="modal-title" id="exampleModalLongTitle">Условия соглашения</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">X</span>
            </button>
          </div>
          <div class="modal-body user_agreement_modal_body p-3" style="border:1px solid grey;border-radius: 10px;max-height: 300px;overflow: auto;">
            Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития. Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития. Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития.
            Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития. Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития. Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект однозначно фиксирует необходимость соответствующих условий активизации. В своём стремлении улучшить пользовательский опыт мы упускаем, что независимые государства освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, ограничены исключительно образом мышления. Идейные соображения высшего порядка, а также разбавленное изрядной долей эмпатии, рациональное мышление предопределяет высокую востребованность дальнейших направлений развития.
          </div>
          <div class="modal-footer d-block pl-0 pr-0" style="border: none;">
            <div class="form-check">

              <input style="width: unset;" class="form-check-input agree" type="checkbox" value="">
              <span  data-toggle="modal" data-target="#user_agreement_modal" style="cursor:pointer;">
                Я принимаю условия соглашения
              </span>
            </div>
        </div>

    </div>
  </div>
</div>




</div>
@push('scripts')
<script>
$('.reg_btn3').click(function(){
    $('.reg_step').html('Шаг 02 из 02');
  })
  $('.reg-btn2').click(function(){
    $('.reg_step').html('Шаг 01 из 02');
  })
</script>
<script type="text/javascript">
    document.getElementById('work_type').addEventListener('change', function() {
    if (this.value == 6){
        document.getElementById('other_work_type_form').style.display = "block"
    }
    else{
        document.getElementById('other_work_type_form').style.display = "none"
    }
    });
    if (document.getElementById("work_type").value == 6){
        document.getElementById('other_work_type_form').style.display = "block"
    }
    else{
        document.getElementById('other_work_type_form').style.display = "none"
    }
</script>
@if ($errors->any())
 <script>
    ////
    var reg_la_la_la_name = '{{ $errors->has('name') }}';
    var reg_la_la_la_last_name = '{{ $errors->has('last_name') }}';
    var reg_la_la_la_middle_name = '{{ $errors->has('middle_name') }}';
    var reg_la_la_la_city = '{{ $errors->has('city') }}';
    var reg_la_la_la_country = '{{ $errors->has('country') }}';

    if(reg_la_la_la_name || reg_la_la_la_last_name || reg_la_la_la_middle_name || reg_la_la_la_city || reg_la_la_la_country ){
        ///если ошибка на первой стр остаемся
    }
    else{
        //если нет то след стр
        document.getElementById('reg_page_1').style.display = "none";
        document.getElementById('reg_page_2').style.display = "block";
    }

 </script>
@endif
@endpush
    @push('scripts')
     <script>
    (function($){
        $(window).on("load",function(){
        $(".user_agreement_modal_body").mCustomScrollbar({
            theme:"my-theme"
        });
        });
    })(jQuery);
        function display_next(){
            document.getElementById('reg_page_1').style.display = "none";
            document.getElementById('reg_page_2').style.display = "block";
        }
        function display_prev(){
            document.getElementById('reg_page_1').style.display = "block";
            document.getElementById('reg_page_2').style.display = "none";
        }
    </script>
    @endpush
    @push('scripts')
    <script>
        $('.agree').on('click', function (e) {
            let btn = $(e.currentTarget);
            if (btn.prop("checked") == true) {
                $('.agree_check').removeClass('agree_block')
            }
            else {
                $('.agree_check').addClass('agree_block');
            }
        })
    </script>
@endpush
@endsection
