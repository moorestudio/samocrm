@forelse($current_user_events as $event)
    @if(\Carbon\Carbon::parseFromLocale($event->date, 'ru') > \Carbon\Carbon::now())
        <div class="p-2 h-100 col-lg-3 col-9">
            <div class="event-card position-relative">
            @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                <div class="event-image" style="background-image: url({{ asset($img) }});">
                    
                </div>
                <div class="p-4 event-info w-100">
                    <p class="font-weight-medium event-card-header mb-2 of-elipsis">{{ $event->title }}</p>
                    <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                    <img src="{{asset('images/pin-g.svg')}}" alt="">{{ $event->city }}
                    </p>
                    <p class="mb-1" style="font-size:13px; color: rgba(30,36,51,0.6);">
                    <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                    </p>
                    @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6)
                        @if(isset($event->link_new))
                        <div class="d-flex mt-3">
                            <div class="text-left">
                                <p class="reff_link" style="font-size:13px; line-height:13px; color:rgba(30,36,51,0.6);"><i
                                            style="color:#F38230;"
                                            class="fas fa-share-square mr-1"></i>{{$event->link_new}}
                                </p>
                            </div>
                            <div>
                                <div class="d-flex align-items-center justify-content-center position-relative dropmenu-action" style="    z-index: 999999;
                            width: 39px;
                            height: 40px;
                            border-radius: 14%;
                            float: right;
                            margin-left: 8px;
                            background: #E1EEFF;">
                        <i class="fas fa-ellipsis-h fa-lg" style="color:#0055A7;"></i>
                        <div class="position-absolute bg-white dropmenu" style="top:100%; right:10%;">
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('event_create',['event' => $event->id]) }}">
                                <i class="fas fa-edit" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Редактировать</p>
                            </a>
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('create_certificate',['event' => $event->id]) }}">
                                <i class="fas fa-medal" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. сертификата</p>
                            </a>
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('create_ticket',['event' => $event->id]) }}">
                                <i class="fas fa-ticket-alt" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. билета</p>
                            </a>
                            @if($event->scheme == 1)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('hall.create', ['id' => $event->id]) }}">
                                    <i class="fas fa-th-large" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Схема зала</p>
                                </a>
                            @endif
                            @if(auth()->user()->role_id == 3 || auth()->user()->role_id == 6)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('tilda_create', ['id' => $event->id]) }}">
                                    <i class="fas fa-th-large" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Tilda</p>
                                </a>
                            @endif
                            @if($event->info == 1)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('event_info_create',['event' => $event->id]) }}">
                                    <i class="fas fa-info-circle" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Информация</p>
                                </a>
                            @endif
                        </div>
                    </div>
                            </div>

                        </div>
                        @else
                            <div class="text-left my-3">
                                                <button class="text-white reff_link_btn reff_link_btn2"
                                                      style="font-size:13px; cursor:pointer;color:rgba(30,36,51,0.6);"
                                                      event_id="{{$event->id}}">Создать реферальную ссылку</button>
                        <div class="d-flex align-items-center justify-content-center position-relative dropmenu-action" style="    z-index: 999999;
                            width: 39px;
                            height: 40px;
                            border-radius: 14%;
                            float: right;
                            margin-left: 8px;
                            background: #E1EEFF;">
                        <i class="fas fa-ellipsis-h fa-lg" style="color:#0055A7;"></i>
                        <div class="position-absolute bg-white dropmenu" style="top:100%; right:10%;">
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('event_create',['event' => $event->id]) }}">
                                <i class="fas fa-edit" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Редактировать</p>
                            </a>
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('create_certificate',['event' => $event->id]) }}">
                                <i class="fas fa-medal" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. сертификата</p>
                            </a>
                            <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('create_ticket',['event' => $event->id]) }}">
                                <i class="fas fa-ticket-alt" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. билета</p>
                            </a>
                            @if($event->scheme == 1)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('hall.create', ['id' => $event->id]) }}">
                                    <i class="fas fa-th-large" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Схема зала</p>
                                </a>
                            @endif
                            @if(auth()->user()->role_id == 3 || auth()->user()->role_id == 6)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('tilda_create', ['id' => $event->id]) }}">
                                    <i class="fas fa-th-large" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Tilda</p>
                                </a>
                            @endif
                            @if($event->info == 1)
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('event_info_create',['event' => $event->id]) }}">
                                    <i class="fas fa-info-circle" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Информация</p>
                                </a>
                            @endif
                        </div>
                    </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
@empty
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Нет действующих <br> мероприятий</span>
    </div>
@endforelse
