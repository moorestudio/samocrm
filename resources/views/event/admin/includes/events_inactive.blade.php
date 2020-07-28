@forelse($events as $event)
    @if(\Carbon\Carbon::parseFromLocale($event->date, 'ru') < \Carbon\Carbon::now())
        <div class="p-2 h-100 col-lg-3 col-9">
            <div class="event-card position-relative">
            @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                <div class="event-image" style="background-image: url({{ asset($img) }});">
                    @if(Auth::user()->role_id == 3)
                    
                    @endif
                </div>
                <div class="p-4 event-info w-100">
                    <p class="font-weight-medium event-card-header mb-2 of-elipsis">{{ $event->title }}</p>
                    <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                    <img src="{{asset('images/pin-g.svg')}}" alt=""> {{ $event->city }}
                    </p>
                    <p class="mb-3" style="font-size:13px; color: rgba(30,36,51,0.6);">
                    <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                    </p>
                    <div class="d-flex w-100" style="margin: 0!important;">
                    @if(auth()->user()->role_id == 3 || auth()->user()->role_id==$event->user_id)
                        <a class="btn btn-success p-0" href="{{ route('event_create',['event' => $event->id]) }}" style="height:35px;margin: 0!important;">Редактировать</a>
                        <div class="d-flex align-items-center justify-content-center position-relative dropmenu-action"
                            style="z-index: 9;width:46px; height:35px; border-radius:14%; float: right;margin-left: 8px;    background: #E1EEFF;">
                            <i class="fas fa-ellipsis-h fa-lg" style="color:#0055A7;"></i>
                            <div class="position-absolute bg-white dropmenu" style="top:100%; right:10%; box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.05);padding-top: 20%;">
                                <!-- <a class="px-2 py-1 d-flex align-items-center dropmenu-point"
                                href="{{ route('event_create',['event' => $event->id]) }}">
                                    <i class="fas fa-edit" style="color:#F38230;"></i>
                                    <p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Редактировать</p>
                                </a> -->
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('create_certificate',['event' => $event->id]) }}">
                                    <i class="fas fa-medal" style="color:#F38230;"></i>
                                    <p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. сертификата</p>
                                </a>
                                <a class="px-2 py-1 d-flex align-items-center dropmenu-point"
                                href="{{ route('create_ticket',['event' => $event->id]) }}">
                                    <i class="fas fa-ticket-alt" style="color:#F38230;"></i>
                                    <p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Констр. билета</p>
                                </a>
                                @if($event->scheme == 1)
                                    <a class="px-2 py-1 d-flex align-items-center dropmenu-point"
                                    href="{{ route('hall.create', ['id' => $event->id]) }}">
                                        <i class="fas fa-th-large" style="color:#F38230;"></i>
                                        <p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Схема зала</p>
                                    </a>
                                @endif
                                @if(auth()->user()->role_id == 3 || auth()->user()->role_id == 6)
                                    <a class="px-2 py-1 d-flex align-items-center dropmenu-point" href="{{ route('tilda_create', ['id' => $event->id]) }}">
                                        <i class="fas fa-th-large" style="color:#F38230;"></i><p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Tilda</p>
                                    </a>
                                @endif
                                @if($event->info == 1)
                                    <a class="px-2 py-1 d-flex align-items-center dropmenu-point"
                                    href="{{ route('event_info_create',['event' => $event->id]) }}">
                                        <i class="fas fa-info-circle" style="color:#F38230;"></i>
                                        <p class="dropmenu-text ml-2 mb-0" style="width: max-content;">Информация</p>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                  </div>
                </div>
            </div>
        </div>

        {{--<div class="p-2 h-100" style="width:25%;">--}}
            {{--<div class="event-card position-relative">--}}
                {{--<div style="height: 220px; background-image: url({{ asset('storage/'.$event->image) }}); background-size: cover; background-position: center;">--}}

                {{--</div>--}}
                {{--<div class="p-4 {{ Auth::user()->role_id == 6 ? 'event-admin-info' : 'event-info' }} w-100"--}}
                     {{--style="background-image: url({{ asset('images/event.svg') }})">--}}
                    {{--<p class="font-weight-bold event-card-header Arial mb-2 pt-4 mt-2">{{ $event->title }}</p>--}}
                    {{--@if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6)--}}
                        {{--<a class="Arial text-dark mr-3" style="font-size:12px;"--}}
                           {{--href="{{ route('event_create',['event' => $event->id]) }}"><i--}}
                                    {{--style="color: #DB7070; width:15px;" class="fas fa-edit mr-1 fa-xl"></i>Редактировать</a>--}}
                        {{--@if($event->scheme == 1)--}}
                            {{--<a class="Arial text-dark" style="font-size:12px;"--}}
                               {{--href="{{ route('hall.create', ['id' => $event->id]) }}"><i--}}
                                        {{--style="color: #DB7070; width:15px;"--}}
                                        {{--class="fas fa-th-large mr-1 fa-xl"></i>Схема зала</a>--}}
                            {{--<a class="Arial text-dark mr-3" style="font-size:12px;"--}}
                               {{--href="{{ route('event_info_create',['event' => $event->id]) }}"><i--}}
                                        {{--style="color: #DB7070; width:15px;"--}}
                                        {{--class="fas fa-info-circle mr-1 fa-xl"></i>Информация</a>--}}
                        {{--@else--}}
                            {{--<a class="Arial text-dark" style="font-size:12px;"--}}
                               {{--href="{{ route('event_info_create',['event' => $event->id]) }}"><i--}}
                                        {{--style="color: #DB7070; width:15px;"--}}
                                        {{--class="fas fa-info-circle mr-1 fa-xl"></i>Информация</a>--}}
                        {{--@endif--}}
                        {{--<a class="Arial text-dark" style="font-size: 12px;"--}}
                           {{--href="{{ route('create_ticket',['event' => $event->id]) }}"><i--}}
                                    {{--style="color: #DB7070; width:15px;"--}}
                                    {{--class="fas fa-ticket-alt mr-1 fa-xl"></i>Констр. билета</a>--}}
                        {{--<br><a class="Arial text-dark" style="font-size: 12px;"--}}
                               {{--href="{{ route('create_certificate',['event' => $event->id]) }}"><i--}}
                                    {{--style="color: #DB7070; width:15px;" class="fas fa-certificate"></i>Констр.--}}
                            {{--сертификата</a>--}}
                    {{--@endif--}}
                    {{--@if(Auth::user()->role_id == 5 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6)--}}
                        {{--@if(isset($event->link_new))--}}
                            {{--<div class="text-left py-1">--}}
                                {{--<p class="reff_link mb-0" style="font-size:12px; line-height:15px;"><i--}}
                                            {{--style="color:#DB7070;"--}}
                                            {{--class="fas fa-share-square mr-1"></i> {{$event->link_new}}</p>--}}
                            {{--</div>--}}
                        {{--@else--}}
                            {{--<div class="text-left">--}}
{{--<span class="Arial text-dark reff_link_btn"--}}
      {{--style="font-size:12px; cursor:pointer; width:15px;"--}}
      {{--event_id="{{$event->id}}"><i style="color:#DB7070;"--}}
                                   {{--class="fas fa-share-square mr-1"></i>Создать реферальную ссылку</span>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                    {{--@endif--}}
                    {{--<div class="d-flex justify-content-between pt-2 ">--}}
                        {{--<p class="Arial" style="font-size:10px;">--}}
                            {{--<i class="fas fa-map-marker-alt mr-1" style="color: #DB7070;"></i>{{ $event->city }}--}}
                        {{--</p>--}}
                        {{--<p class="Arial" style="font-size:10px;">--}}
                            {{--<i class="far fa-clock mr-1" style="color: #DB7070;"></i> {{ $event->date }}--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    @endif
@empty
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Нет действующих <br> мероприятий</span>
    </div>
@endforelse
