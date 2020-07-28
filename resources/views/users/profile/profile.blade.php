@extends('layouts.app')
@section('content')
<?php
        if(!isset($user))
            {
                 $user = \Illuminate\Support\Facades\Auth::user();
            }
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @if($user->role_id==2 and $user->confirmed_at==Null)
                    <div class="p-4" style="color:red;font-weight: bolder;">
                        Ваша почта еще не была подтверждена!!! На Вашу почту было отправлено письмо с данными для доступа в учетную запись и ссылкой для подтверждения почты. Пока Вы не подтвердите Вашу почту Вы не сможете приобрести билет на мероприятие!!!
                    </div>
                @endif
                    <div class="row">
                        <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12 p-1">
                            <div class="text-black text-uppercase te h2 py-3" style="font-size: 20px;">личный кабинет слушателя</div>
                            <div class="profile_main_info">
                                <div class="p-3 client_prof_card">
                                    <div class="d-flex align-items-center justify-content-start">
                                    <p class="font-weight-bold text-left mb-1">Личные данные</p>
                                    </div>
                                        <div class="d-flex mt-2 mb-4">
                                         <div>

                                        @if($user->avatar=="users/default.png")
                                            <div class="d-flex justify-content-start">
                                                <img src="{{asset('images/default-user.svg')}}" alt="">
                                            </div>
                                        @elseif($user->avatar)
                                            <div style="width:67px; height:67px; border-radius:50px; background-image: url({{asset('storage/avatars/'.$user->avatar)}}); background-size: cover; background-position:center;">
                                            </div>
                                        @endif

                                         </div>
                                         <div class="pt-3 mb-2 {{isset($user->avatar) ? 'ml-3' : ''}}">
                                         </div>
                                         <div class="d-flex flex-column justify-content-center">
                                           <div class="font-weight-medium w-100">{{ $user->name }} {{ $user->last_name }}</div>
                                           <div class="color-white">{{$user->role->display_name}}</div>
                                         </div>
                                     </div>
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/pin1.svg')}}" alt="" style="color:white;">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->city }}</p>
                                     </div>
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/mail1.svg')}}" alt="">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->email }}</p>
                                     </div>
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/phone1.svg')}}" alt="">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->contacts }}</p>
                                     </div>
                                     @if($user->company)
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/vector1.svg')}}" alt="">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->company }}</p>
                                     </div>
                                     @endif
                                     @if($user->job)
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/bi_person1.svg')}}" alt="">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->job }}</p>
                                     </div>
                                     @endif
                                     @if($user->work_type)
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/settings2.svg')}}" alt="">
                                       <p class="font-weight-medium text-white profile-info-color mb-0 ml-2">{{ $user->work_type }}</p>
                                     </div>
                                     @endif
                                    @if($user->role_id != 2 and $user->role_id != 3)
                                        <div class="mt-3">
                                          @if(isset($user->contract))
                                          <a href="{{url('storage/images/contracts/'.$user->contract)}}" style="color:black;" download>Скачать контракт</a>
                                          {{-- <span data-toggle="modal" data-target="#contract_view" style="cursor: pointer;">Просмотреть</span> --}}
                                          <a href="{{url('storage/images/contracts/'.$user->contract)}}" target="_blank"><span>Просмотреть</span></a>
                                          @else
                                          <p class="info-text mb-0 text-black">Нет скана контракта</p>
                                          @endif
                                        </div>
                                    @endif
                                    <div class="mb-4"></div>
                                    <a href="{{ route('profile_edit',['id'=>$user->id]) }}">
                                        <img class="mr-1" style="vertical-align: sub;" src="{{ asset('images/edit-2.svg') }}" alt="">
                                        <span style="color:white;">Редактировать профиль</span>
                                    </a>
                                </div>
                                @if(isset($user->franchise_id) and $user->role_id != 3)
                                <div class="p-3 mt-2 curator_prof_card">

                                        <?php
                                            $franchise = \App\User::find($user->franchise_id);
                                        ?>
                                            <div class="d-flex align-items-left justify-content-left">
                                                <span class="main-title font-weight-bold text-black" style="    text-transform: none!important;">Ваш менеджер</span>
                                            </div>
                                     <div class="mt-2 mb-2 d-none">
                                         <div>
                                            <img src="{{url('storage/avatars/'.$franchise->avatar)}}" style="width: 100px;height: 100px;object-fit: cover; border-radius: 50px;" alt="">

                                         </div>
                                     </div>

                                    <!-- <p class="info-text mb-0 text-black">Имя и Фамилия</p> -->
                                    <div class="d-flex mt-10px mar-bot-client">
                                       <p class="font-weight-medium text-black profile-info-color mb-0">{{ $franchise->name }} {{ $franchise->last_name }}</p>
                                     </div>
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/mail.svg')}}" alt="" style="color:white;">
                                       <p class="font-weight-bold text-black profile-info-color mb-0 ml-2">{{ $franchise->email }}</p>
                                     </div>
                                     <div class="d-flex mt-10px">
                                       <img src="{{asset('images/phone.svg')}}" alt="" style="color:white;">
                                       <p class="font-weight-bold text-black profile-info-color mb-0 ml-2">{{ $franchise->contacts }}</p>
                                     </div>
                                    <!-- <p class="font-weight-bold text-black profile-info-color" style="font-size: 16px;">{{ $franchise->name }} {{ $franchise->last_name }}</p>
                                    <!-- <p class="info-text mb-0 text-black ">E-mail адрес:</p> -->
                                    <!-- <p class="font-weight-bold text-black profile-info-color" style="font-size: 16px;">{{ $franchise->email }}</p> -->
                                    <!-- <p class="info-text mb-0 text-black">Номер телефона</p> -->
                                    <!-- <p class="font-weight-bold text-black profile-info-color" style="font-size: 16px;">{{ $franchise->contacts }}</p> -->
                                </div>
                                @endif
                                @if(Auth::user()->role_id == 3 and $user->role_id != 3)
                                <button type="submit" class="select-button py-1 mt-3 p-2" data-toggle="modal" data-target="#change_franch_admin">Сменить куратора</button>
                                 
                                @endif
<!--                                   @if(Auth::user()->role_id == 3 and $user->id == auth()->user()->id)
                                    <form action="{{route('partner_sell_data')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <p class="main-title font-weight-bold text-transform-none my-3">Продажа партнерства</p>
                                        <input placeholder="Яндекс shopid" type="text" id="yandex_shop_id" name="yandex_shop_id" class="form-control input-style mt-2" value="{{ isset($user) ? $user->yandex_shop_id : '' }}" required>
                                        <input placeholder="Яндекс секретный ключ" type="text" id="yandex_secret_key" name="yandex_secret_key" class="form-control input-style mt-2" value="{{ isset($user) ? $user->yandex_secret_key : '' }}" required>
                                        <input placeholder="Paybox merchantid" type="text" id="paybox_merchant_id" name="paybox_merchant_id" class="form-control input-style mt-2" value="{{ isset($user) ? $user->paybox_merchant_id : '' }}" required>
                                        <input placeholder="Paybox секретный ключ" type="text" id="paybox_secret_key" name="paybox_secret_key" class="form-control input-style mt-2" value="{{ isset($user) ? $user->paybox_secret_key : '' }}" required>
                                        <input placeholder="Введите сумму в рублях" type="text" id="partner_sell_price" name="partner_sell_price" class="form-control input-style mt-2" value="{{ isset($user) ? $user->partner_sell_price : '' }}" required>
                                        <div>
                                            <button type="submit" class="w-100 select-button py-1 mt-3">
                                                сохранить
                                            </button>
                                        </div>
                                    </form>
                                  @endif -->

                                @if($user->role_id == 2)
                                    <!-- <span type="button" class="btn btn-default" id="change_franch_btn">Поменять продавца</span> -->
                                    <button type="button" class="btn btn-default w-100 mt-2 ml-0" id="client_feedback_btn" style="    border-radius: 5px;    box-shadow: none;     background: #2bcb71!important;">Обратная связь</button>
                                     @include('modals.profile.change_franch')
                                     @include('modals.profile.client_feedback')
                                @endif
                            </div>


                            </div>

                            <div class="profile_check d-none" data-id="{{$user->id}}"></div>
                            @if($user->role_id == 2)

                            <div class="col-lg-9 col-md-7 col-sm-12 col-xs-12 p-3 padd7-top">
                                <p class="h5 text-left font-weight-medium pb-2" style="text-transform: none;font-size: 21px">
                                    История покупок
                                </p>
                                <div class="row" style="border-bottom: 1px solid #E1E8F3;">
                                    <div class="text-center" style="    width: 33%;">
                                        <button class="profile_button profile_button_active profile_button_first" data-parent="1">
                                            <img class="mr-0" style="float: left;padding-top: 1%;" src="{{ asset('images/receipt3.svg') }}" alt="">
                                            <div class="text-black">Выкупленные билеты</div>
                                        </button>
                                    </div>
                                    <div class="text-center" style="    width: 33%;">
                                        <button class="profile_button color-black" data-parent="2">
                                            <img class="mr-0" style="float: left;padding-top: 1%;width: 17px;" src="{{ asset('images/Calendar2.svg') }}" alt="">
                                            <span class="color-black">Забронированные билеты</span>
                                        </button>
                                    </div>
                                    <div class="text-center" style="    width: 33%;">
                                        <button class="profile_button" data-parent="3">
                                            <img class="mr-0" style="float: left;padding-top: 1%;" src="{{ asset('images/History2.svg') }}" alt="">
                                            <span>Прошедшие мероприятия</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3 profile_history_block color-white">
                                    @include('users.profile.user_includes.buy_history.active_buys')
                                </div>
                            </div>
                            @elseif(Auth::user()->role_id == 3 and $user->id == auth()->user()->id)
                                <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 p-3 padd7-top" >
                                    <div class="p-3" style="border-radius: 7px;background-color: white;">
                                        <form action="{{route('partner_sell_data')}}" method="POST" >
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <p class="main-title font-weight-bold text-transform-none" style="margin-top: 0.5%;">Продажа партнерства</p>
                                            <div class="d-flex">
                                                <input placeholder="Яндекс shopid" type="text" id="yandex_shop_id" name="yandex_shop_id" class="form-control input-style mt-2 mr-2" value="{{ isset($user) ? $user->yandex_shop_id : '' }}" required style="background-color: #F5F6FA;">
                                                <input placeholder="Paybox merchantid" type="text" id="paybox_merchant_id" name="paybox_merchant_id" class="form-control input-style mt-2" value="{{ isset($user) ? $user->paybox_merchant_id : '' }}" required style="background-color: #F5F6FA;">

                                            </div>
                                            <div class="d-flex">
                                                <input placeholder="Яндекс секретный ключ" type="text" id="yandex_secret_key" name="yandex_secret_key" class="form-control input-style mt-2 mr-2" value="{{ isset($user) ? $user->yandex_secret_key : '' }}" required style="background-color: #F5F6FA;">
                                                <input placeholder="Paybox секретный ключ" type="text" id="paybox_secret_key" name="paybox_secret_key" class="form-control input-style mt-2" value="{{ isset($user) ? $user->paybox_secret_key : '' }}" required style="background-color: #F5F6FA;">
                                            </div>
                                            <input placeholder="Введите сумму в рублях" type="text" id="partner_sell_price" name="partner_sell_price" class="form-control input-style mt-2" value="{{ isset($user) ? $user->partner_sell_price : '' }}" required style="background-color: #F5F6FA;">
                                            <div>
                                                <button type="submit" class="w-25 select-button py-1 mt-3 w-25">
                                                    сохранить
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                 </div>


                            @else
                            <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 p-3 padd7-top">



                                <p class="h5 text-left font-weight-medium pb-2" style="text-transform: none;font-size: 21px">
                                    Список мероприятий с реферельными ссылками
                                </p>
                                @php
                                    $curr_user_ref_events_ids = \App\ReferralLink::where('user_id',$user->id)->pluck('referral_event_id');
                                    $curr_user_ref_events = \App\Event::whereIn('id',$curr_user_ref_events_ids)->get();

                                @endphp

                                <div class="row">
                                @if(count($curr_user_ref_events)>0)
                                @foreach($curr_user_ref_events as $event)
                                    <div class="p-2 h-100 col-lg-4 col-9">
                                        <div class="event-card position-relative">
                                            <div class="event-image" style="background-image: url({{ asset('storage/'.$event->image) }});">
                                            </div>
                                            <div class="p-4 event-info w-100">
                                                <p class="font-weight-bold event-card-header mb-2 of-elipsis">{{ $event->title }}</p>
                                                <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                                                    <i class="fas fa-map-marker-alt mr-2" style="color: #F38230;"></i>{{ $event->city }}
                                                </p>
                                                <p class="mb-3" style="font-size:13px; color: rgba(30,36,51,0.6);">
                                                    <i class="fas fa-calendar-alt mr-2" style="color: #F38230;"></i>{{ $event->date }}
                                                </p>
                                                @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6 || Auth::user()->role_id == 8)
                                                    @php
                                                        $event_link = \App\ReferralLink::where('user_id',$user->id)->where('referral_event_id',$event->id)->first();
                                                    @endphp
                                                    @if(isset($event_link))
                                                        <div class="text-left py-1 mb-3">
                                                            <p class="reff_link" style="font-size:13px; line-height:13px; color:rgba(30,36,51,0.6);"><i style="color:#F38230;"  class="fas fa-share-square mr-1"></i>{{$event_link->url_link}}
                                                            </p>
                                                        </div>
                                                    @else

                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                    НЕТ Мероприятий с реферельными ссылками
                                @endif
                                </div>
                            </div>
                            @endif

                    </div>
                </div>
        </div>
    </div>

<form class="d-none" action="{{route('buy_page')}}" id="BuyForm" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="send_id" name="id" value="">
    <input type="hidden" id="send_type" name="type" value="buy">
    <input type="hidden" id="send_row" name="row">
    <input type="hidden" id="send_column" name="column">
    <input type="hidden" id="send_price" name="price">
    <input type="hidden" id="send_ticket" name="ticket">
</form>

<div class="modal" id="contract_view">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" style="height: 80vh;">
{{--         <iframe id="contract_pdf" style="display: none;"
            src="{{url('storage/images/contracts/'.$user->contract)}}"
            frameBorder="0"
            scrolling="auto"
            height="100%"
            width="100%"
            max-width = "500px"
            position= "relative"
            >

        </iframe>
 --}}

<object id="contract_pdf" type="application/pdf" style="display: none;"  width="100%" height="400px" data="{{url('storage/images/contracts/'.$user->contract)}}"></object>
{{--         <embed id="contract_pdf" style="display: none;"
            src="{{url('storage/images/contracts/'.$user->contract)}}"
            type="application/pdf"
            frameBorder="0"
            scrolling="auto"
            height="100%"
            width="100%"
        ></embed>
 --}}

        <div  id="contract_img"  style="display: none;>
            <img src="{{url('storage/images/contracts/'.$user->contract)}}" style="width: 100%">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
</div>


<!-- s -->
<!-- The Modal -->
<div class="modal fade" id="change_franch_admin">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal body -->

      <div class="modal-body mx-2" style="max-height: 500px;overflow: auto;">
    <div class="row user_create_modal_head mb-1">
        <div class="col">
            <span style="font-size: 13px;font-weight: 600;">Нынешний Куратор</span>
        </div>

        <div class="col">
            @if(isset($franchise))
                <span style="font-size: 13px;font-weight: 600;">{{$franchise->fullName()}} dd</span>
            @else
                <span style="font-size: 13px;font-weight: 600;">Не закреплен</span>
            @endif
        </div>



    </div>

    <div class="row user_create_modal_head mb-1">
        <div class="col">
            <span style="font-size: 13px;font-weight: 600;">Изменить на: </span>
        </div>

        <div class="col">
            <span id="admin_chosen_franch" style="font-size: 13px;font-weight: 600;color: brown;"></span>
        </div>
    </div>

        <form action="{{route('user_change_franch')}}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="franch_id" id="franch_id_hidden_input" value>
            <div>
                <button type="submit" class="select-button change_franch_admin_class py-1 px-3 agree_block">
                    Изменить
                </button>
            </div>
        </form>
    <div class="row mt-1">
        <div class="col-6">
            <div class="form-group mb-1" style="position: relative;">
                <img id="user_create_search_icon" src="{{ asset('images/user_create/user_create_search.png') }}" alt="">
                <input type="text" class="form-control form-control2 create_user_inputs" placeholder="Поиск по кураторам по имени, email" id="search_fra" name="search" style="padding-left: 15%;">
            </div>  
        </div>
    </div>

          <div class="row">
            <div class="col">
                <div>
                    <div class="row user_create_modal_head">
                        <div class="col-3">ФИО</div>
                        <div class="col-3">Роль</div>
                        <div class="col-2">Город</div>
                        <div class="col-4">E-mail</div>
                    </div>

                    <div id="tbody_franch">
                        @foreach(\App\User::whereIn('role_id',[4,5,6,8])->where('status',null)->get() as $franch_)
                            <div class="row create_user_inputs my-2 p-2" onclick="get_franch(this)" class="franch_row_class" franch_id="{{$franch_->id}}" franch_name="{{$franch_->fullName()}}">
                                <div class="col-3">{{$franch_->fullName()}}</div>
                                <div class="col-3">{{ \App\Role::find($franch_->role_id)->display_name}}</div>
                                <div class="col-2">{{$franch_->city}}</div>
                                <div class="col-4">{{$franch_->email}}</div>

                            </div>
                        @endforeach
                    </div>
                
                </div>

            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- s -->
@include('modals.profile.delete_reserve')
@endsection

@push('scripts')
    <script>

        $('#search_fra').on('keyup',function(){
        $value=$(this).val();
        console.log($value);
        $.ajax({
        type : 'get',
        url : '{{URL::to('search_franch_admin')}}',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data:{'search':$value},
        success:function(data){
            $('#tbody_franch').html(data);
        }
        });
        })

        function get_franch(row) {
            console.log(row.getAttribute('franch_name'));
            $("#admin_chosen_franch").html(row.getAttribute('franch_name'));
            $("#franch_id_hidden_input").val(row.getAttribute('franch_id'));


            if($('#pay_type').val() == '')
            {
                $('.change_franch_admin_class').addClass('agree_block');
            }
            else
            {
                $('.change_franch_admin_class').removeClass('agree_block');
            }

        };
        $(document).on('click','.removeReserve', function(e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            $('.btn-reserve-delete').attr('data-id',id);
            $('#DeleteReserve').modal('show');
        })
        var contract_extension = '{{$user->contract}}';
        var contract_extension_arr = contract_extension.split('.');

        if(contract_extension_arr[contract_extension_arr.length-1] != 'pdf' ){
            $('#contract_img').show();
        }
        else{
            $('#contract_pdf').show();
        }

        $(document).on('click', '#change_franch_btn' ,function (e) {
            $('#change_franch_modal').modal('show');
        });
        $(document).on('click', '#client_feedback_btn' ,function (e) {
            $('#client_feedback_modal').modal('show');
        });

        $('.confirm_change_franch').click(function (e) {
            var name = '{{$user->name}}';
            var phone = '{{$user->contacts}}';
            var type = 'Поменять своего продавца';
            var email = '{{$user->email}}';
            var comment = $('#franch_change_comment').val();
            if (name != '' && phone != '' && type != '' && email != '')
            {
                $.ajax({
                    url: '{{ route('contact_send') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name,
                        "phone": phone,
                        "type": type,
                        "email": email,
                        "comment": comment,
                    },
                    success: data => {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Заявка отправлена!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: true,
                            });
                        // btn.removeClass('loading');
                        $('#change_franch_modal').modal('hide');
                    },
                    error: () => {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Произошла ошибка!',
                            // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                            showConfirmButton: true,
                        });
                        // btn.removeClass('loading');
                    }
                })
            }
            else
            {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Заполните все важные поля *!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });
            }
        });
        $('.confirm_client_feedback').click(function (e) {
            var name = '{{$user->name}}';
            var phone = '{{$user->contacts}}';
            var email = '{{$user->email}}';
            var type = 'Обратная свзяь';
            var comment = $('#client_feedback_comment').val();
            if (name != '' && phone != '' && comment != '' && email != '')
            {
                $.ajax({
                    url: '{{ route('feedback_send') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name,
                        "phone": phone,
                        "email": email,
                        "type": type,
                        "comment": comment,
                    },
                    success: data => {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Заявка отправлена!',
                                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                                showConfirmButton: true,
                            });
                        // btn.removeClass('loading');
                        $('#client_feedback_modal').modal('hide');
                    },
                    error: () => {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Произошла ошибка!',
                            // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                            showConfirmButton: true,
                        });
                        // btn.removeClass('loading');
                    }
                })
            }
            else
            {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Заполните описание!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: true,
                });
            }
        });

        $("#resend_ticket_to_email").click(function() {
            let event_id = this.getAttribute("event_id");
            
            let ticket_id = this.getAttribute("ticket_id");

            $.ajax({
            type: 'POST',
            url: "/resend_mail",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'event_id': event_id,
                'ticket_id': ticket_id,
            },
            success: function (data) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Отправлено!',
                    // description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
                    showConfirmButton: false,
                });
            },
            });
        });
    </script>









    <script>
        $(document).on('click', '.buy-reserve-ticket' ,function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var row = btn.data('parent');
            var column = btn.data('parent2');
            var price = btn.data('parent3');
            var ticket = btn.data('parent4');


            $('#send_id').val(id);
            $('#send_row').val(row);
            $('#send_column').val(column);
            $('#send_price').val(price);
            $('#send_ticket').val(ticket);

            $('#BuyForm').submit();
        })
    </script>
    <script>
        $('.profile_button').on('click', function (e) {
            let btn = $(e.currentTarget);
            $('.profile_button').removeClass('profile_button_active');
            btn.addClass('profile_button_active');

            let user_id = $('.profile_check').data('id');
            let type = btn.data('parent');
            $('.profile_history_block').addClass('disappear');

            $.ajax({
                url: '{{ route('switch_history') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "type": type,
                    "user_id": user_id,
                },
                success: data => {
                    $('.profile_history_block').html(data.view).show('slide', {direction: 'left'}, 400);
                    $('.profile_history_block').removeClass('disappear');
                },
                error: () => {
                    $('.profile_history_block').removeClass('disappear');
                }
            })
        })
    </script>
    <script>
        $(document).on('click','.removeReserve', function(e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');


            $('.btn-reserve-delete').attr('data-id',id);
            $('#DeleteReserve').modal('show');
        })
    </script>
    <script>
        $(document).on('click','.btn-reserve-delete', function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var comment = $('#delete_comment').val();
            if(comment != '')
            {
                $.ajax({
                    url: '{{ route('profile_delete_reserve') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "comment": comment,
                    },
                    success: data => {
                        $('#reserve-' + id).hide(200);
                        $('#DeleteReserve').modal('hide');
                    },
                    error: () => {
                    }
                })
            }
            else
            {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Напишите причину удаления брони!',
                    // description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
                    showConfirmButton: false,
                });
            }
        })
    </script>
    {{--<script>--}}
        {{--$(document).on('click','.buy-reserve-ticket',function (e) {--}}
            {{--var btn = $(e.currentTarget);--}}
            {{--var ticket = btn.data('id');--}}
            {{--var row = btn.data('parent');--}}
            {{--var column = btn.data('parent2');--}}
            {{--$('#BuyReserveModal').modal('show');--}}
            {{--$('.row_modal').html(row);--}}
            {{--$('.row_modal').attr('data-id',row);--}}
            {{--$('.collumn_modal').html(column);--}}
            {{--$('.collumn_modal').attr('data-id',column);--}}
            {{--$('#ticket_id').val(ticket);--}}
        {{--})--}}
    {{--</script>--}}
@endpush
