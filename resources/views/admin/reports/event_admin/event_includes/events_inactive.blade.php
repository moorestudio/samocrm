@foreach($events as $event)
    @if(\Carbon\Carbon::parseFromLocale($event->date, 'ru') < \Carbon\Carbon::now())
        <div class="p-2 h-100 col-lg-3 col-9">
            <a href="{{route('eventAdmin/report/single/event',$event)}}" style="font-decoration:none; color:#000000;">
                <div class="event-card position-relative">
                    <div class="event-image" style="background-image: url({{ asset('storage/'.$event->image) }});">
                    </div>
                    <div class="p-4 event-info w-100">
                        <p class="font-weight-bold event-card-header mb-2">{{ $event->title }}</p>
                        <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                            <i class="fas fa-map-marker-alt mr-2" style="color: #F38230;"></i>{{ $event->city }}
                        </p>
                        <p class="mb-1" style="font-size:13px; color: rgba(30,36,51,0.6);">
                            <i class="fas fa-calendar-alt mr-2" style="color: #F38230;"></i>{{ $event->date }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endforeach