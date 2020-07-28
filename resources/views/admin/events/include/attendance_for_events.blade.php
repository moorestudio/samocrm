@foreach($events as $event)
  <div class="row align-items-center mb-1 event report-card">
    <div class="col-2 d-lg-block d-none">
      <a href="{{ route('attendance_events_clients',['id' => $event->id]) }}">
        <p class="mb-0 list-link of-elipsis">{{$event->title}}</p>
      </a>
    </div>
    <div class="col d-lg-block d-none">
        <p class="mb-0 list-link">{{$event->normalDate()}}</p>
    </div>
    <div class="col d-lg-block d-none">
        <p class="mb-0 list-link">{{$event->normalendDate()}}</p>
    </div>
    <div class="col-2 d-lg-block d-none">
        <p class="mb-0 list-link of-elipsis">{{$event->mentor}}</p>
    </div>
    <div class="col-2 d-lg-block d-none">
        <p class="mb-0 list-link">{{$all_ticket = intval($event->client_count)}}</p>
    </div>
    <div class="col-1 d-lg-block d-none">
        <p class="mb-0 list-link">{{$sold_ticket = $event->soldTicketsQuantity()}}</p>
    </div>
    <div class="col-1 d-lg-block d-none">
        <p class="mb-0 list-link">{{$all_ticket - $sold_ticket}}</p>
    </div>
    <div class="col-1 d-lg-block d-none">
        <p class="mb-0 list-link">{{$event->soldTicketsPrice()}}</p>
    </div>
  </div>
@endforeach
