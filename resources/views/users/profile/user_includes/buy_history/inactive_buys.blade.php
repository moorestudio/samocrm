<div class="row pb-4">
<?php
        $count = 0;
?>
    @foreach($user->tickets as $ticket)
        @if($ticket->event->date < \Carbon\Carbon::now() || $ticket->type != 'buy' && $ticket->type != 'reserve' && $ticket->type != 'return')
            <?php
            $event = \App\Event::find($ticket->event_id);
            ?>
                <div class="p-2 h-100 col-lg-4 col-12" id="reserve-{{$ticket->id}}">
                    <div class="event-card position-relative" style="box-shadow: 0px 4px 50px rgba(0, 0, 0, 0.05);">
                        @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                        <div class="event-image" style="background-image: url({{ asset($img) }});">
                        </div>
                        <div class="p-4 event-info w-100">
                            <p class="font-weight-bold event-card-header mb-2">{{ $event->title }}</p>
                            <p class="mb-1 font-weight-normal" style="font-size:11px; color:rgba(30,36,51,0.6);">
                            <img src="{{asset('images/pin-g.svg')}}" alt="">{{ $event->city }}
                            </p>
                            <p class="mb-3 font-weight-normal" style="font-size:11px; color: rgba(30,36,51,0.6);">
                            <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                            </p>
                            </div>
                    </div>
                </div>
            {{--<div class="col-6 p-4">--}}
                {{--<div class="w-100" style="background-image: url({{asset('storage/'.$event->image)}}); background-size:cover; background-position: center; max-height:150px; height:150px;">--}}
                {{--</div>--}}
                {{--<p class="profile_event_name font-weight-bold pt-2 mb-0">--}}
                    {{--{{$event->title}}--}}
                {{--</p>--}}
                {{--<p class="profile_info mb-0">--}}
                    {{--<i class="far fa-clock mr-1" style="color: #DB7070; width:15px;"></i> {{ $event->date }}--}}
                {{--</p>--}}
                {{--<p class="profile_info mb-0">--}}
                    {{--<i class="fas fa-map-marker-alt mr-1" style="color: #DB7070; width:15px;"></i> {{ $event->city }}--}}
                {{--</p>--}}
            {{--</div>--}}
                <?php
                    $count = $count + 1;
                ?>
        @endif
    @endforeach
    @if($count == 0)
        <div class="text-center col-12">
        <img class="mr-0 padd7-top" style="float: center;padding-top: 1%;" src="{{ asset('images/none2.svg') }}" alt="">
            <p class="main-title color-grey font-weight-bold py-3">Нет прошедших мероприятий</p>
        </div>
    @endif
</div>