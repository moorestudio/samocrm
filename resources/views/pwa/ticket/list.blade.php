@extends('layouts.app')
@section('content')

    <div class="container" style="margin-top: 29px;">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 pb-3">
                <div>
                    <p class="main-title text-uppercase text-lg-left text-center">Выберите мероприятие</p>
                </div>
            </div>

            @foreach($events as $event)
                @if(\Carbon\Carbon::parseFromLocale($event->date,'ru') > \Carbon\Carbon::now())
                    <div class="p-2 h-100 col-lg-3 col-9">
                        <a href="/scanner/{{$event->id}}" style="text-decoration: none; color: #000000;">
                        <div class="event-card position-relative">
                        @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                            <div class="event-image" style="background-image: url({{ asset($img) }});">
                            </div>
                            <div class="p-4 event-info w-100">
                                <p class="font-weight-medium event-card-header mb-2">{{ $event->title }}</p>
                                <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                                <img src="{{asset('images/pin-g.svg')}}" alt="">{{ $event->city }}
                                </p>
                                <p class="mb-3 font-weight-normal" style="font-size:13px; color: rgba(30,36,51,0.6);">
                                <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

@endsection
