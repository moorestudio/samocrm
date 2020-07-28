@extends('layouts.app')
@section('content')
    <?php
        $request = \Illuminate\Support\Facades\Session::get('request');
//        dd($request);
    ?>
    <div class="container">
        <form class="row" action="{{ route('multiple_buy') }}" method="POST" multiple="">
            @csrf
            <input type="hidden" name="count" value="{{count($row)}}">
            <input type="hidden" name="event" value="{{$event->id}}">
            <input type="hidden" name="type" value="{{$type}}">
            @for($i = 0; $i < count($row); $i++ )
                <div class="col-6 p-4 ">
                    <input type="hidden" name="row{{$i}}" value="{{$row[$i]}}">
                    <input type="hidden" name="column{{$i}}" value="{{$column[$i]}}">
                    <input type="hidden" name="price{{$i}}" value="{{$price[$i]}}">
                    <div class="bg-white p-3">
                    <p class="profile-info font-weight-bold text-center">
                        ряд: {{$row[$i]}} место: {{ $column[$i] }} цена: {{ $price[$i] }} сом
                    </p>
                        <p class="text-center profile-info">
                            Анкета покупателя
                        </p>
                        <div class="px-3">
                            <label for="event_name mb-0">Имя </label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите имя*" type="text" name="name{{$i}}" id="name{{$i}}" class="form-control pt-0" value="{{isset($request) ? $request['name'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Фамилия</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите фамилия*" type="text" name="last_name{{$i}}" id="last_name{{$i}}" class="form-control pt-0" value="{{isset($request) ? $request['last_name'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Город</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите город*" type="text" name="city{{$i}}" id="city{{$i}}" class="form-control pt-0" value="{{isset($request) ? $request['city'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Email</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите Email*" type="text" name="email{{$i}}" id="email{{$i}}" class="form-control pt-0" value="{{isset($request) ? $request['email'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Телефон</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите номер телефона*" type="text" name="phone{{$i}}" id="phone{{$i}}" class="form-control pt-0"  value="{{isset($request) ? $request['phone'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Должность</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите должность*" type="text" name="job{{$i}}" id="job{{$i}}" class="form-control pt-0"  value="{{isset($request) ? $request['job'.$i]: ''}}" required>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="event_name mb-0">Компания</label>
                            <div class="md-form mt-0">
                                <input placeholder="Введите компанию*" type="text" name="company{{$i}}" id="company {{$i}}" class="form-control pt-0"  value="{{isset($request) ? $request['company'.$i]: ''}}" required>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            <div class="col-12 p-4 bg-white">
                <div class="agree_info p-3" style="height:auto; max-height: 200px; overflow-y: auto; border:1px solid rgba(0,0,0,0.25); border-radius:5px;">
                    <p>
                        1.1. Настоящее Соглашение заключается между Покупателем и Интернет-магазином в момент оформления заказа. Покупатель подтверждает свое согласие с условиями, установленными настоящим Соглашением, путём установления отметки (галочки) в графе «Я прочитал Условия соглашения и согласен с ними» при оформлении заказа или регистрации на Сайте.

                        1.2. Настоящие Соглашение, а также информация о товаре, представленная на Сайте, являются публичной офертой в соответствии со ст.435 и ч.2 ст.437 ГК РФ.

                        1.3. К отношениям между Покупателем и Интернет-магазином применяются положения ГК РФ о розничной купле-продаже (§ 2 глава 30), а также Закон РФ «О защите прав потребителей» от 07.02.1992 № 2300-1 и иные правовые акты, принятые в соответствии с ними.

                        1.4. Покупателем может быть любое физическое или юридическое лицо, способное принять и оплатить заказанный им товар в порядке и на условиях, установленных настоящим Соглашением.

                        1.5. Интернет-магазин оставляет за собой право вносить изменения в настоящее Соглашение.

                        1.6. Настоящее Соглашение должно рассматриваться в том виде, как оно опубликовано на Сайте и должно применяться, и толковаться в соответствии с законодательством Российской Федерации.
                    </p>
                </div>
                <div class="custom-control custom-checkbox pt-2">
                    <input type="checkbox" class="custom-control-input" name="agree" id="agree">
                    <label class="custom-control-label" for="agree" style="cursor:pointer; user-select: none;">я принимаю условия соглашения</label>
                </div>
                <div class="text-left">
                    <p class="profile-info font-weight-bold py-3">
                         <?php
                             $total = 0;

                             foreach($price as $item){
                                $total = $total + $item;
                             }
                         ?>
                         Итого к оплате : {{ $total }} сом
                    </p>
                </div>
                <button class="btn btn-warning mt-4">Отправить</button>
            </div>
        </form>
    </div>

@endsection