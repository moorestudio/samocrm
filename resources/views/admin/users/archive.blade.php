@extends('layouts.admin_app')
@section('content')
    <?php
    $agent = new \Jenssegers\Agent\Agent();
    ?>

    <div class="container">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 px-0">
                <p class="main-title text-uppercase text-lg-left text-center">Архив пользователей</p>
            </div>
            <div class="col-12 py-4 px-0 d-flex">
                <span class="second-title">Выбрано:<span class="second-title ml-2" style="font-size: 14px;" id="show_checked_blocked">0</span></span>
                @if(!$agent->isPhone())
                    <button class="user_list_action_btn btn my-0 ml-4 p-0" id="unblock_user_btn"><img src="{{asset('images/unlock.svg')}}" alt="" class="mr-2">Разблокировать</button>
                    <button class="user_list_action_btn btn my-0 ml-4 p-0" id="delete_user_btn"><img src="{{asset('images/delete-b.svg')}}" alt="" class="mr-2">Удалить</button>
                @endif
            </div>
            <div class="col-12 py-4 px-0 d-flex">
                <div class="p-4" style="color:red;background-color: white;border-radius: 5px;">
                    При удалении Организатора мероприятия, организатором его мероприятий станет Админ.<br>
                    При удалении Франчайзи, все его партнеры перейдут под Офис САМО, и все его слушатели станут не прикрепленными.<br>
                    При удалении партнера франчайзи, партнера Офиса САМО, Sales все их слушатели станут не прикрепленными.<br>
                </div>
            </div>
            @if($agent->isPhone())
                <div class="col-12">
                    <button class="user_list_action_btn btn my-0  p-0" id="unblock_user_btn"><img src="{{asset('images/unlock.svg')}}" alt="" class="mr-2">Разблокировать</button>
                    <button class="user_list_action_btn btn my-0 ml-3 p-0" id="delete_user_btn"><img src="{{asset('images/delete-b.svg')}}" alt="" class="mr-2">Удалить</button>
                </div>
            @endif
        </div>

        <div class="row py-3 mb-1 sticky-top report-header mt-4">
            <div style="width:55px;"></div>
            <div class="col-lg-2 col-12"><p class="mb-0 title">ФИО</p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 title">Тип аккаунта</p></div>
            <div class="col-lg-3 col-12"><p class="mb-0 title">Номер телефона</p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 title">Email</p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 title">Дата блокировки</p></div>
        </div>
        @if(count($data['blocked_users']))
            @foreach($data['blocked_users'] as $blocked_user)
                <div class="row report-card special-card">
                    @if(!$agent->isPhone())
                        <div class="custom-checkbox custom-control text-center d-lg-flex d-none" style="width:50px;" user_id="{{$blocked_user->id}}">
                            <input type="checkbox" class="custom-control-input" name="blocked_user" id="blocked_{{$blocked_user->id}}">
                            <label class="custom-control-label" for="blocked_{{$blocked_user->id}}"></label>
                        </div>
                        <div class="col-2 d-lg-flex d-none of-elipsis"><p class="mb-0">{{ $blocked_user->fullname()}}</p></div>
                        <div class="col-2 d-lg-flex d-none of-elipsis"><p class="mb-0">{{ $blocked_user->role->display_name }}</p></div>
                        <div class="col-3 d-lg-flex d-none of-elipsis"><p class="mb-0">{{ $blocked_user->contacts }}</p></div>
                        <div class="col-2 d-lg-flex d-none of-elipsis"><p class="mb-0">{{ $blocked_user->email }}</p></div>
                        <div class="col-2 d-lg-flex d-none of-elipsis"><p class="mb-0">{{ $blocked_user->dateFormat('d.m.y') }}</p></div>
                    @else
                        <div class="col-10">
                            <div><p class="mb-0">{{ $blocked_user->fullname()}}</p></div>
                            <div><p class="mb-0">{{ $blocked_user->role->display_name }}</p></div>
                            <div><p class="mb-0">{{ $blocked_user->contacts }}</p></div>
                            <div><p class="mb-0">{{ $blocked_user->email }}</p></div>
                            <div><p class="mb-0">{{ $blocked_user->dateFormat('d.m.y') }}</p></div>
                        </div>
                        <div class="custom-checkbox custom-control text-center col-2 d-flex justify-content-center align-items-center" style="width:50px;" user_id="{{$blocked_user->id}}">
                            <input type="checkbox" class="custom-control-input" name="blocked_user" id="blocked_{{$blocked_user->id}}">
                            <label class="custom-control-label" for="blocked_{{$blocked_user->id}}"></label>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
                <img src="{{asset('images/server.svg')}}" alt="">
                <div class="w-100"></div>
                <span class="second-title mt-2 empty-element">Архив пуст</span>
            </div>
        @endif

{{--<div class="container">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-12">--}}
                        {{--<h3>Архив</h3>--}}
                        {{--<h4 style="display: inline-block;">Выбрано</h4>--}}


                    {{--</div>--}}
                    {{--<div class="col-12">--}}
                    {{--<div class="user_show_list">--}}
                        {{--<!-- <h3>Архив</h3> -->--}}
                            {{--<table class="table">--}}
                              {{--<thead>--}}
                                {{--<tr>--}}
                                  {{--<th scope="col">Тип</th>  --}}
                                  {{--<th scope="col">Имя</th>--}}
                                  {{--<th scope="col">Фамилия</th>--}}
                                  {{--<th scope="col"></th>--}}
                                {{--</tr>--}}
                              {{--</thead>--}}
                              {{--<tbody id="blocked_users_table">--}}
                                {{--@foreach($data['blocked_users'] as $blocked_user)--}}
                                {{--<tr>--}}
                                  {{--<td>--}}

                                      {{--{{ $blocked_user->role->display_name }}--}}

                                  {{--</td>  --}}
                                  {{--<td>{{ $blocked_user->name }}</td>--}}
                                  {{--<td>{{ $blocked_user->last_name}}</td>--}}
                                  {{--<td user_id="{{$blocked_user->id}}"><input id="blocked_{{$blocked_user->id}}" type="checkbox" name="blocked_user"></td>--}}
                                {{--</tr>--}}
                                {{--@endforeach--}}

                              {{--</tbody>--}}
                            {{--</table>--}}
                    {{--</div>    --}}

                    {{--</div>--}}
                {{--</div>--}}
 {{----}}
</div>

@endsection
