<div class="row pb-4">
    <?php
    $count = 0;
    ?>
    @foreach($user->tickets as $ticket)
        @if($ticket->event->date > \Carbon\Carbon::now() && $ticket->type == 'reserve')
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
                                <i class="fas fa-map-marker-alt mr-2" style="color: #F38230;"></i>{{ $event->city }}
                            </p>
                            <p class="mb-3 font-weight-normal" style="font-size:11px; color: rgba(30,36,51,0.6);">
                                <i class="fas fa-calendar-alt mr-2" style="color: #F38230;"></i>{{ $event->date }}
                            </p>
                            @if(\Illuminate\Support\Facades\Auth::id() == $user->id)
                            <div class="d-flex">
                            <button class=" w-100 py-1 buy-reserve-ticket resend_btn_class" data-id="{{$ticket->event_id}}" data-parent="{{$ticket->row}}" data-parent2="{{$ticket->column}}" data-parent3="{{$ticket->ticket_price}}" data-parent4="{{$ticket->id}}" style="width:80%!important;">Выкупить бронь</button>
                            <button class=" w-100 removeReserve print_ev " data-id="{{ $ticket->id }}" style="width: 50px!important;    margin-left: 3px;"><img src="{{asset('images/bx_bx-block.svg')}}" alt="" ><span class="tooltiptext">Удалить бронь</span></button>
                            @endif
                            </div>
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
            <p class="main-title color-grey font-weight-bold text-center py-3">Нет забронированных билетов</p>
        </div>
    @endif
</div>
