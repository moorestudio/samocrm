@foreach($events as $event)
    @if(\Carbon\Carbon::parseFromLocale($event->date, 'ru') > \Carbon\Carbon::now())
        <div class="p-2 h-100 col-lg-3 col-9">
            <div class="event-card position-relative">
                @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                <div class="event-image" style="background-image: url({{ asset($img) }});">
                </div>
                <div class="p-3 event-info w-100">
                    <p class="font-weight-bold event-card-header mb-2">{{ $event->title }}</p>
                    <div class="d-flex">
                      <img src="{{asset('images/pin-g.svg')}}" alt="">
                      <p class="mb-1 desc">
                          {{ $event->city }}
                      </p>
                    </div>
                    <div class="d-flex">
                      <img src="{{asset('images/calendar-g.svg')}}" alt="">
                      <p class="mb-1 desc">
                          {{ $event->date }}
                      </p>
                    </div>
                </div>
                @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6)
                  <div class="d-flex pb-3 px-2">
                    <a class="btn btn-success p-0" href="{{route('buy_for_client_one_event',$event)}}" style="height:35px;">Выкупить новый</a>
                    <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{route('event_details_view',$event)}}">
                      <img src="{{asset('images/google-docs.svg')}}" alt="">
                    </a>
                  </div>
                @endif
            </div>
        </div>
    @endif
@endforeach
