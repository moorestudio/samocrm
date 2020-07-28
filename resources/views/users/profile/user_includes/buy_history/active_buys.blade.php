<div class="row pb-4 justify-content-start">
    <?php
    $count = 0;
    ?>
    @foreach($user->tickets as $ticket)
        @if($ticket->event->date > \Carbon\Carbon::now() && $ticket->type == 'buy')
            <?php
            $event = \App\Event::find($ticket->event_id);
            ?>
                <div class="p-2 h-100 col-lg-4 col-12">
                    <div class="event-card position-relative" style="box-shadow: 0px 4px 50px rgba(0, 0, 0, 0.05);">
                        @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                        <div class="event-image" style="background-image: url({{ asset($img) }});">
                        </div>
                        <div class="p-4 event-info w-100">
                            <p class="font-weight-bold event-card-header mb-2 of-elipsis">{{ $event->title }}</p>
                            <p class="mb-1 font-weight-normal" style="font-size:11px; color:rgba(30,36,51,0.6);">
                            <img src="{{asset('images/pin-g.svg')}}" alt=""></i>{{ $event->city }}
                            </p>
                            <p class="mb-3 font-weight-normal" style="font-size:11px; color: rgba(30,36,51,0.6);">
                            <img src="{{asset('images/calendar-g.svg')}}" alt=""> </i>{{ $event->date }}
                            </p>
                            @if(\Illuminate\Support\Facades\Auth::id() == $user->id)
                            <button event_id="{{$event->id}}"  ticket_id="{{$ticket->id}}" id="resend_ticket_to_email" class="mt-1 pl-3 pr-3 pt-1 pb-1 w-100 resend_btn_class" style="width:80%!important;">Отправить на почту</button>
                            <a target="_blank" href="ticket_show/{{ $ticket->id }}"><button class="print_ev"><img src="{{asset('images/print21.svg')}}" alt=""><span class="tooltiptext">Распечатать</span></button></a>
                            
                            @endif
                        </div>
                    </div>
                </div>
                <?php
                $count = $count + 1;
                ?>
        @endif
    @endforeach
        @if($count == 0)
            <div class="text-center col-12">
            <img class="mr-0 padd7-top" style="float: center;padding-top: 1%;" src="{{ asset('images/none2.svg') }}" alt="">
                <p class="main-title color-grey font-weight-bold text-center py-3">Нет активных билетов</p>
            </div>
        @endif
</div>