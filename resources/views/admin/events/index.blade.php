@extends('layouts.admin_app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5 pb-3">
                @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6)
                    <div class="d-flex justify-content-between">
                        <p class="h2 text-uppercase font-weight-bold">Мероприятия</p>
                    <!--                     <a href="{{ route('event_create') }}">
                    <button class="btn blue-gradient">Создать мероприятие Event</button>
                    </a> -->
                        <a href="{{ route('event_create') }}">
                            <button class="btn blue-gradient">Создать мероприятие</button></a>
                    </div>
                @endif

            </div>

            @foreach($events as $event)
                <div class="p-2 h-100" style="width:25%;">
                    <div class="event-card position-relative">
                        <div style="height: 220px; background-image: url({{ asset('storage/'.$event->image) }}); background-size: cover; background-position: center;">
                        </div>
                        <div class="p-4 event-info w-100" style="background-image: url({{ asset('images/event.svg') }})">
                            <p class="font-weight-bold event-card-header Arial mb-2 pt-4 mt-2">{{ $event->title }}</p>
                            @if(Auth::user()->role_id==4 || Auth::user()->role_id==3 || Auth::user()->role_id == 6)
                                <div class="text-left" style="border-bottom:1px solid #E0E0E0;">
                                    <a class="Arial text-dark mr-3" style="font-size:12px;" href="{{ route('event_create',['event' => $event->id]) }}"><i style="color: #DB7070;" class="fas fa-edit mr-1 fa-xl"></i>Редактировать</a>

                                    <a class="Arial text-dark" style="font-size:12px;" href="{{ route('hall.create', ['id' => $event->id]) }}"><i style="color: #DB7070;" class="fas fa-th-large mr-1 fa-xl"></i>Схема зала</a>
                                </div>
                            @endif
                            @if(Auth::user()->role_id==4 || Auth::user()->role_id==5 )
                                <div class="d-flex justify-content-between pt-2">
                                    <p class="Arial" style="font-size:12px;">
                                        Reff link
                                    </p>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between pt-2">
                                <p class="Arial" style="font-size:10px;">
                                    <i class="fas fa-map-marker-alt mr-1" style="color: #DB7070;"></i>{{ $event->city }}
                                </p>
                                <p class="Arial" style="font-size:10px;">
                                    <i class="far fa-clock mr-1" style="color: #DB7070;"></i> {{ $event->date }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection