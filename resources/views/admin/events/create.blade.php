@extends('layouts.admin_app')
@push('styles')
    <style>
        label
        {
            color: rgba(0, 0, 0, 0.51);
        }
    </style>
@endpush
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <p class="main-title text-uppercase text-lg-left text-center mb-0">{{ isset($event) ? 'Редактирование мероприятия' : 'Создание мероприятия' }}</p>
                </div>
                @if(isset($event))
                  <?php
                      $tickets = count($event->tickets()->whereIn('type',['done','buy','reserve'])->get());
                  ?>
                  @if($tickets>0)
                      <span style="color: red;">НЕ редактируйте мероприятие, на это мероприятие уже было куплено/бронировано {{$tickets}} билет(a-ов)</span>
                  @endif
                @endif
                <div class="d-flex justify-content-start w-100">
                    <p class="main-title-description w-100"><img src="{{asset('images/calendar1.svg')}}" alt="" class="mr-2">Общая информация о мероприятии</p>
                </div>
            </div>
        <div class="col-12">
            <form action="{{ isset($event)?route('event.update',['event'=>$event]):route('event_store') }}" method="POST" enctype="multipart/form-data">
                @if (isset($event))
                    @method('PUT')
                @endif
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <label for="event_name" >Название мероприятия*</label>
                        <div class="mt-0">
                            <input placeholder="Название мероприятия*" type="text" name="name" id="event_name" class="form-control input-style" value="{{ isset($event) ? $event->title : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <label for="event_date" >Дата начала*</label>
                        <div class="mt-0">
                            <input placeholder="Дата проведения мероприятия*" type="text" id="event_date" name="date" class="form-control date-format input-style" value="{{ isset($event) ? $event->date : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <label for="end_event_date" >Дата окончания*</label>
                        <div class="mt-0">
                            <input placeholder="Дата окончание мероприятия*" type="text" id="end_event_date" name="end_date" class="form-control date-format input-style" value="{{ isset($event) ? $event->end_date : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3 {{Auth::user()->role_id == 6 ? 'd-none' : ''}}">
                        <label for="user_id" >Организатор*</label>
                        <div class="mt-0">

                            <select class="browser-default custom-select input-style" name="user_id" id="user_id" required="">
                                <option {{isset($event) ? '' : 'selected'}} disabled>Выберите организатора</option>
                                @if(Auth::user()->role_id == 3)
                                    <?php
                                        $admin = Auth::user();
                                    ?>
                                    <option value="{{$admin->id}}">{{$admin->fullName()}}</option>
                                    @foreach(\App\User::where('role_id',4)->orWhere('role_id',6)->get() as $franch)
                                        @if(isset($event) && $franch->id == $event->user_id)
                                            <option value="{{$franch->id}}" selected>{{$franch->fullName()}}</option>
                                        @else
                                            <option value="{{$franch->id}}">{{$franch->fullName()}}</option>
                                        @endif
                                    @endforeach

                                @else
                                    @foreach(\App\User::where('role_id',4)->orWhere('role_id',6)->get() as $franch)
                                        @if(isset($event) && $franch->id == $event->user_id)
                                            <option value="{{$franch->id}}" selected>{{$franch->fullName()}}</option>
                                        @else
                                            <option value="{{$franch->id}}">{{$franch->fullName()}}</option>
                                        @endif
                                    @endforeach
                                @endif

                           </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3">
                        <label for="mentor" >Спикер</label>
                        <div class="mt-0">
                            <input placeholder="Спикер мероприятия*" type="text" id="mentor" name="mentor" class="form-control input-style" value="{{ isset($event) ? $event->mentor : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3">
                        <label for="category_id" >Категория мероприятия*</label>
                        <div class="mt-0">
                            <select class="browser-default custom-select input-style" name="category_id" id="category_id">
                                <option value="">Выберите категорию</option>
                                @foreach(\App\Category::all() as $category)
                                    @if(isset($event) && $category->id == $event->category_id)
                                        <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                    @else
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3">
                        <label for="event_city" >Город*</label>
                        <div class="mt-0">
                            <input placeholder="Ваш город*" type="text" id="event_city" name="city" class="form-control input-style" value="{{ isset($event) ? $event->city : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3">
                        <label for="event_address" >Адрес*</label>
                        <div class="mt-0">
                            <input placeholder="Адрес проведения мероприятия*" type="text" id="event_address" name="address" class="form-control input-style" value="{{ isset($event) ? $event->address : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mt-3">
                        <label for="newsletter_time" >Время отправки сертификатов*</label>
                        <div class="mt-0">
                            <input placeholder="Время" type="time" id="newsletter_time" name="newsletter_time" class="form-control input-style" value="{{ isset($event) ? $event->newsletter_time : '' }}" required>
                        </div>
                    </div>
                    @php
                      $showOrNot = 'd-none';
                      if(isset($event)){
                        if(Auth::user()->role_id == 3 or Auth::user()->id == $event->user_id){
                          $showOrNot = '';
                        }
                      }else{
                        $showOrNot = '';
                      }
                    @endphp
                    <div class="form-group col-6 mt-3">
                        <input type="hidden" id="latitude" name="latitude" class="form-control" value="{{ isset($event) ? $event->latitude : '' }}">
                    </div>
                    <div class="form-group col-6 mt-3">
                        <input type="hidden" id="longtitude" name="longtitude" class="form-control" value="{{ isset($event) ? $event->longtitude : '' }}">
                    </div>
                    <div class="form-group col-12">
                        <div id="map" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
                  <div class="row">
                    <div class="d-flex justify-content-start col-lg-12">
                        <p class="main-title-description w-100 mt-3"><img src="{{asset('images/bill1.svg')}}" alt="" class="mr-2">Билеты</p>
                    </div>
                    <div class="col-lg-4 col-12 pt-3">
                        <label for="reserve_date" >Дедлайн бронирования билетов</label>
                        <div class="mt-0">
                            <input type="text" id="reserve_date" name="reserve_date" class="form-control date-format input-style" value="{{ isset($event) ? $event->reserve_date : '' }}" placeholder="Выбрать дату" required>
                        </div>
                    </div>
                      <div class="col-lg-4 col-12 pt-3">
                          <label for="buy_date" >Дедлайн покупки билетов</label>
                          <div class="mt-0">
                              <input type="text" id="buy_date" name="buy_date" class="form-control date-format input-style" value="{{ isset($event) ? $event->buy_deadline : '' }}" placeholder="Выбрать дату" required>
                          </div>
                      </div>
                      <div class="col-lg-4"></div>
                      <div class="active-scheme col-lg-4 col-12 pt-3">
                        <label for="client-count" >Общее кол-во билетов</label>
                        <div class="mt-0">
                            <input type="number" id="client-count" name="client_count" class="form-control input-style limit-zero max-value" data-max="100000" value="{{ isset($event) ? $event->client_count : ''}}" placeholder="Количество билетов" required min="0">
                        </div>
                      </div>
                      <div class="inactive-scheme col-lg-4 col-12 pt-3" style="display: none;">
                          <label for="inactive-places">Введите кол-во билетов на мероприятие без схемы зала</label>
                          <div class="mt-0">
                              <input type="number" id="inactive-places" name="ticket_count" class="form-control input-style limit-zero max-value" data-max="100000" value="{{ isset($event) ? $event->ticket_count : ''}}" required min="0">
                          </div>
                      </div>
                      <div class="col-lg-4 col-12 pt-3">
                        <label for="franch-count" >Кол-во билетов для продавцов</label>
                        <div class="mt-0">
                            <input type="number" id="franch-count" name="franch_count" class="form-control input-style limit-zero max-value" data-max="100000" value="{{ isset($event) ? $event->franch_count : ''}}" placeholder="Количество билетов" min="0">
                        </div>
                      </div>
                      <div class="col-lg-4"></div>
                      <div class="col-lg-12 col-12 pt-3">
                        <div class="custom-checkbox custom-control pb-4">
                            <input type="checkbox" class="custom-control-input" name="scheme" id="scheme" {{ isset($event) && $event->scheme == 0 ? 'checked' : ''}}>
                            <label class="custom-control-label" for="scheme">Отключить схему зала?</label>
                        </div>
                      </div>
                      <div class="d-flex justify-content-start col-lg-12">
                          <p class="main-title-description w-100 mt-3"><img src="{{asset('images/paper1.svg')}}" alt="" class="mr-2">Тарифы</p>
                      </div>
                      <div id="tarif_row_id" class="row col-lg-12">
                        <div class="col-lg-4 col-12">
                          <div class="tarif-card flex-wrap">
                            <div class="col-12 d-flex align-items-center">
                                <p class="second-title text-uppercase font-weight-bold mb-0">
                                    Параметры тарифа
                                </p>
                            </div>
                              <div class="col-12">
                                  <label for="event_rate1" >Название тарифа</label>
                                  <input type="text" id="event_rate1" name="rate_name1" class="form-control input-style" value="{{ isset($event) ? $event->rate[0][0] : '' }}" placeholder="Название тарифа" required>
                              </div>
                              <div class="col-7">
                                  <label for="event_rate1_price" >Цена</label>
                                  <input type="text" id="event_rate1_price" name="rate_price1" class="form-control input-style" value="{{ isset($event) ? $event->rate[0][2] : '' }}" placeholder="Цена тарифа" required>
                              </div>
                              <div class="col-5">
                                  <label for="event_rate1_color" >Цвет тарифа</label>
                                  <input type="color" id="event_rate1_color" name="rate_color1" class="form-control p-0 border-0 input-style" value="{{ isset($event) ? $event->rate[0][1] : '' }}" required>
                              </div>
                              <div class="col-12 d-flex align-items-center">
                                  <p class="second-title text-uppercase font-weight-bold mb-0">
                                      Акция
                                  </p>
                              </div>
                              <div class="col-7">
                                  <label for="event_stock1" >Дата окончания акции</label>
                                  <input type="date" id="event_stock1" name="promo_date1" class="form-control border-0 input-style discount_date_" value="{{ isset($event) ? $event->rate[0][3] : '' }}"  >
                              </div>
                              <div class="col-5">
                                  <label for="event_stock1_price" >Цена по акции</label>
                                  <input type="text" id="event_stock1_price" name="promo_price1" class="form-control  border-0 input-style" value="{{ isset($event) ? $event->rate[0][4] : '' }}" placeholder="Цена">
                              </div>
                          </div>
                        </div>
                        <script type="text/javascript">
                            var number_of_rates = 0;
                        </script>
                        @if (isset($event))
                          <script type="text/javascript">
                              var number_of_rates = {{count($event->rate)}}
                          </script>
                          @foreach($event->rate as $key => $rate)
                            @if($key > 0)
                              <div class="col-lg-4 col-12 position-relative">
                                <img src="{{asset('/images/x.svg')}}" class="remove_tarif" data-toggle="tooltip" title="Удалить">
                                <div class="tarif-card flex-wrap">
                                      <div class="col-12 d-flex align-items-center">
                                          <p class="second-title text-uppercase font-weight-bold mb-0">
                                              Параметры тарифа
                                          </p>
                                      </div>
                                      <div class="col-12">
                                          <label for="event_rate{{$key+1}}" >Название тарифа</label>
                                          <input type="text" id="event_rate{{$key+1}}" name="rate_name{{$key+1}}" class="form-control border-0 input-style" value="{{ $rate[0]}}" required>
                                      </div>
                                      <div class="col-7">
                                          <label for="event_rate{{$key+1}}_price" >Цена</label>
                                          <input type="text" id="event_rate{{$key+1}}_price" name="rate_price{{$key+1}}" class="form-control border-0 input-style" value="{{ $rate[2]}}" required>
                                      </div>
                                      <div class="col-5">
                                          <label for="event_rate{{$key+1}}_color" >Цвет тарифа</label>
                                          <input type="color" id="event_rate{{$key+1}}_color" name="rate_color{{$key+1}}" class="form-control p-0  border-0 input-style" value="{{ $rate[1]}}" required>
                                      </div>
                                      <div class="col-12 d-flex align-items-center">
                                          <p class="second-title text-uppercase font-weight-bold mb-0">
                                              Акция
                                          </p>
                                      </div>
                                      <div class="col-7">
                                          <label for="event_stock{{$key+1}}" >Дата окончания акции</label>
                                          <input type="date" id="event_stock{{$key+1}}" name="promo_date{{$key+1}}" class="form-control border-0 input-style discount_date_" value="{{ $rate[3]}}" >
                                      </div>
                                      <div class="col-5">
                                          <label for="event_stock{{$key+1}}_price" >Цена по акции</label>
                                          <input type="text" id="event_stock{{$key+1}}_price" name="promo_price{{$key+1}}" class="form-control border-0 input-style" value="{{ $rate[4]}}" >
                                      </div>
                                  </div>
                              </div>
                            @endif
                          @endforeach
                        @endif
                        <div class="col-lg-4 col-12 before" id="add_tarif">
                          <div class="container-fluid tarif-card px-1 align-items-center flex-column dashed-column">
                              <img src="{{asset('images/plus.svg')}}" alt="">
                              <p class="add-text">
                                Добавить новый <br> тариф
                              </p>
                          </div>
                        </div>
                        </div>
                        <div class="d-flex justify-content-start col-lg-12">
                            <p class="main-title-description w-100 mt-3"><img src="{{asset('images/pay1.svg')}}" alt="" class="mr-2">Оплата</p>
                        </div>
                        <div class="col-lg-2 col-12 {{$showOrNot}}">
                          <label for="currency" >Валюта  Тарифов</label>
                          <select class="form-control input-style" name="currency" required>
                            @php $currency_list = \App\CurrencyNames::all();
                             $currencyid = isset($event) ? $event->currency : '' @endphp
                            @foreach($currency_list as $currency)
                                @if($currency->id == $currencyid)
                                  <option value="{{$currency->id}}" selected>{{$currency->currency}}</option>
                                @else
                                  <option value="{{$currency->id}}">{{$currency->currency}}</option>
                                @endif
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4 col-12 {{$showOrNot}}">
                          <label for="convert_rub" >Сотношение к курсу рубля</label>
                          <div class="mt-0">
                              <input placeholder="Введите сотношение к курсу рубля" type="number" id="convert_rub" name="convert_rub" class="form-control input-style" value="{{ isset($event) ? $event->convert_rub : '' }}"  step="0.01" required>
                          </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6 col-12 mt-3 {{$showOrNot}}">
                          <label for="yandex_shop_id" >Яндекс.касса shopid</label>
                          <div class="mt-0">
                              <input placeholder="Яндекс.касса shopid" type="text" id="yandex_shop_id" name="yandex_shop_id" class="form-control input-style" value="{{ isset($event) ? $event->yandex_shop_id : '' }}" required>
                          </div>
                        </div>
                        <div class="col-lg-6 col-12 mt-3 {{$showOrNot}}">
                          <label for="yandex_secret_key" >Paybox merchant_id</label>
                          <div class="mt-0">
                              <input placeholder="Paybox merchant_id" type="text" id="paybox_merchant_id" name="paybox_merchant_id" class="form-control input-style" value="{{ isset($event) ? $event->paybox_merchant_id : '' }}" required>
                          </div>
                        </div>
                        <div class="col-lg-6 col-12 mt-3 {{$showOrNot}}">
                          <label for="yandex_secret_key" >Яндекс.касса Секретный ключ</label>
                          <div class="mt-0">
                              <input placeholder="Яндекс.касса Секретный ключ" type="text" id="yandex_secret_key" name="yandex_secret_key" class="form-control input-style" value="{{ isset($event) ? $event->yandex_secret_key : '' }}" required>
                          </div>
                        </div>
                        <div class="col-lg-6 col-12 mt-3 {{$showOrNot}}">
                          <label for="yandex_secret_key" >Paybox Секретный ключ</label>
                          <div class="mt-0">
                              <input placeholder="Paybox Секретный ключ" type="text" id="paybox_secret_key" name="paybox_secret_key" class="form-control input-style" value="{{ isset($event) ? $event->paybox_secret_key : '' }}" required>
                          </div>
                        </div>
                        <div class="d-flex justify-content-start col-lg-12">
                            <p class="main-title-description w-100 mt-3"><img src="{{asset('images/gift1.svg')}}" alt="" class="mr-2">Бонусы продавцов</p>
                        </div>
                        <div class="col-lg-4 col-12">
                            <label for="mentor" >% Франчайзи</label>
                            <div class="mt-0">
                                <input placeholder="% Франчайзи" type="text" id="franch_perc" name="franch_perc" class="form-control input-style" value="{{ isset($event) ? $event->financial->franch_perc : '' }}" >
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <label for="mentor" >% Партнеров</label>
                            <div class="mt-0">
                                <input placeholder="% Партнеров" type="text" id="partner_perc" name="partner_perc" class="form-control input-style" value="{{ isset($event) ? $event->financial->partner_perc : '' }}" >
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <label for="mentor" >% САМО Sales</label>
                            <div class="mt-0">
                                <input placeholder="% САМО Sales" type="text" id="samo_sales_perc" name="samo_sales_perc" class="form-control input-style" value="{{ isset($event) ? $event->financial->samo_sales_perc : '' }}" >
                            </div>
                        </div>
                        <div class="d-flex justify-content-start col-lg-12">
                            <p class="main-title-description w-100 mt-3"><img src="{{asset('images/adjust1.svg')}}" alt="" class="mr-2">Дополнительные настройки</p>
                        </div>
                        <div class="col-lg-6 col-12">
                            <label for="preview">Баннер (Превью)</label>
                            @if(isset($event))
                              <div>
                                <img class="pb-3" style="max-width: 250px;" id="previewImage" src="{{'storage/'.$event->image}}" alt="">
                              </div>
                            @endif
                            <label for="preview" class="mb-0 d-flex justify-content-between previewContent">
                                @php $img_name = isset($event) ? $event->image ? $event->image :'файл не выбран' : 'файл не выбран'; @endphp
                                <div class="w-100 eventFileName of-elipsis">{{$img_name}}</div>
                                <span class="getFileBtn ">Обзор...</span>
                            </label>
                            <input type="file" id="preview" name="preview" class="form-control" hidden="hidden" onchange="changeFileName(this)"  accept="image/x-png,image/gif,image/jpeg" >
                        </div>
                      </div>
                      <div class="col-lg-12 col-12 pt-3 d-flex flex-wrap px-0">

                          <div class="custom-checkbox custom-control col-lg-4">
                              <input type="checkbox" class="custom-control-input" name="info" id="inactive-info" {{isset($event) && $event->info == 0 ? 'checked' : ''}}>
                              <label class="custom-control-label" for="inactive-info">Отключить конструктор информации?</label>
                          </div>
                          <div class="custom-control custom-checkbox col-lg-4">
                              <input type="checkbox" class="custom-control-input" name="active" id="active" {{ isset($event) && $event->active == 1 ? 'checked' : ''}}>
                              <label class="custom-control-label" for="active">Опубликовать?</label>
                          </div>
                          <div class="col-lg-6 pl-0">
                            <p class="pt-3" style="font-size: 14px; color: #EF6E6E;">!Опубликовав мероприятие, все пользователи сайта будут видить данное мероприятие. Во избежание ошибок опубликуйте мероприятие, после создания схемы зала, создания билетов и составления информации о мероприятии!</p>
                          </div>
                      </div>
                      <div class="d-flex">
                      <button type="submit" class="btn btn-submit li-btn mr-3">
                          Сохранить
                      </button>
                      <a href="{{ route('main') }}" class="btn btn-cancel li-btn">
                          отмена
                      </a>
                      </div>
                    </div>
            </form>
        </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function changeFileName(myFile){
          var file = myFile.files[0];
          $('.eventFileName').html(file.name);
          if (myFile.files && myFile.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(myFile.files[0]);
          }
        };
        $('#tarif_row_id').on('click', '.remove_tarif', function(e) {
            e.currentTarget.parentNode.remove();

        });
        var tarifNum=1;
        number_of_rates>tarifNum?tarifNum=number_of_rates:tarifNum;
        $('#add_tarif').click( function () {
            tarifNum ++;
            let new_tarif_block = `
                    <div class="col-lg-4 col-12 position-relative">
                          <img src="/images/x.svg" class="remove_tarif" data-toggle="tooltip" title="Удалить">
                           <div class="tarif-card flex-wrap">
                                 <div class="col-12 d-flex align-items-center">
                                     <p class="second-title text-uppercase font-weight-bold mb-0">
                                         Параметры тарифа
                                     </p>
                                 </div>
                                <div class="col-12">
                                    <label  >Название тарифа</label>
                                    <input type="text"  name="rate_name`
                                    +tarifNum+
                                    `" class="form-control input-style" value="" placeholder="Название тарифа" required>
                                   </div>
                                <div class="col-7">
                                    <label  >Цена</label>
                                    <input type="text"  name="rate_price`+tarifNum+
                                    `" class="form-control input-style" value="" placeholder="Цена тарифа" required>
                                </div>
                                <div class="col-5">
                                    <label  >Цвет тарифа</label>
                                    <input type="color" name="rate_color`+tarifNum+
                                    `" class="form-control p-0  input-style" value="" >
                                </div>
                                <div class="col-12 d-flex align-items-center">
                                    <p class="second-title text-uppercase font-weight-bold mb-0">
                                        Акция
                                    </p>
                                </div>
                                <div class="col-7">
                                    <label  >Дата окончания акции</label>
                                    <input type="date"  name="promo_date`
                                    +tarifNum+
                                    `" class="form-control  input-style discount_date_" value=""  placeholder="Выбрать дату">
                                </div>
                                <div class="col-5">
                                    <label  >Цена по акции</label>
                                    <input type="text"  name="promo_price`+tarifNum+
                                    `" class="form-control  input-style" value=""  placeholder="Цена">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

            $('#add_tarif').before(new_tarif_block);




        });

        $('#scheme').click( function () {
            let status = $('#scheme').is(':checked') ? true : false;
            if(status == true)
            {
                $('#inactive-places').attr("required", "required");
                $('.active-scheme').hide();
                $('.inactive-scheme').show(400);
            }
            else
            {
                $('#inactive-places').removeAttr("required");
                $('.inactive-scheme').hide();
                $('.active-scheme').show(400);
            }
        });
        $(document).on('change','.limit-zero',function(){
          let val = $(this).val();
          if(val < 0){
            $(this).val(0);
          }
        });
        $(document).on('change','.max-value',function(){
          let btn = parseInt($(this).val());
          let max = parseInt($(this).attr('data-max'));
          if(btn >= max){
            $(this).val(max);
          }
        });
        $(document).ready( function () {
            let status = $('#scheme').is(':checked') ? true : false;
            if(status == true)
            {
                $('#inactive-places').attr("required", "required");
                $('.active-scheme').hide();
                $('.inactive-scheme').show(400);
            }
            else
            {
                $('#inactive-places').removeAttr("required");
                $('.inactive-scheme').hide();
                $('.active-scheme').show(400);
            }
        })
    </script>

    {{--<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_MAPS_API_KEY') }}&lang=ru_RU" type="text/javascript"></script>--}}
    <script>
        ymaps.ready(init);

        function init() {
            var myPlacemark,
                myMap = new ymaps.Map('map', {
                    center: [{{ $event->latitude ?? 42.865388923088396 }}, {{ $event->longtitude ?? 74.60104350048829 }}],
                    zoom: 12
                }, {
                    searchControlProvider: 'yandex#search'
                });

            // Слушаем клик на карте.
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');

                $('#latitude').val(coords[0]);
                $('#longtitude').val(coords[1]);

                // Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress(coords);
            });

            // Создание метки.
            function createPlacemark(coords) {
                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

            // Определяем адрес по координатам (обратное геокодирование).
            function getAddress(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    myPlacemark.properties
                        .set({
                            // Формируем строку с данными об объекте.
                            iconCaption: [
                                // Название населенного пункта или вышестоящее административно-территориальное образование.
                                firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                                // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                            ].filter(Boolean).join(', '),
                            // В качестве контента балуна задаем строку с адресом объекта.
                            balloonContent: firstGeoObject.getAddressLine()
                        });
                });
            }
        }

    </script>
@endpush
