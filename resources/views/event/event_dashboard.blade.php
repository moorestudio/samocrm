@extends('layouts.admin_app')
@push('styles')
    <style>
        .event_selector
        {
            transition: 0.5s;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 pb-3">
                @if(Auth::user()->role_id ==3)
                    <div class="justify-content-between d-lg-flex d-none">
                        <p class="main-title text-uppercase text-lg-left text-center">Все мероприятия</p>
                    </div>
                    <div class="d-lg-none d-block text-center">
                        <p class="main-title text-uppercase text-lg-left text-center">Все мероприятия</p>
                    </div>
                @endif
            </div>
            <div class="col-12 pb-3 d-flex flex-wrap">
                @php $route = auth()->user()->role_id==3 ? 'admin' : 'event_list';
                     $category = isset($category) ? $category : '';
                @endphp
                <div class="col-auto px-0">
                    <a href="{{route($route)}}" class="category-sort-title {{$category ?  '': 'active' }}">Все</a>
                </div>
                @foreach(\App\Category::all() as $cat)
                  <div class="col-auto px-0">
                      <a href="{{route($route,$cat->id)}}" class="category-sort-title {{$category==$cat->id ? 'active' : '' }}">{{$cat->name}}</a>
                  </div>
                @endforeach
            </div>
            @if(count($events) && \Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id != 6)
                        <div class="col-12 pb-3">
                            <p class="second-title text-uppercase text-lg-left text-center">Все мероприятия</p>
                        </div>
            @endif
            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 6)

                    <ul class="nav nav-tabs mb-3" id="event_ul_btns" role="tablist" style="border:none;">
                        <li class="nav-item mr-3">

                            <a class="nav-link active event_list_btn_switch" who="this" data-toggle="tab" href="#current_user_event_list_pane" role="tab" aria-selected="true">
                                <p class="m-0 text-none text-lg-left text-center">Ваши мероприятия</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link event_list_btn_switch" who="all" data-toggle="tab" href="#all_event_list_pane" role="tab"  aria-selected="false">
                            <p class="m-0 text-none text-lg-left text-center">Все мероприятия</p>
                            </a>
                        </li>
                    </ul>
            @endif
            @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id != 6)
                @forelse($events as $event)
                    @if(\Carbon\Carbon::parseFromLocale($event->date, 'ru') > \Carbon\Carbon::now())
                    <div class="p-2 h-100 col-lg-3 col-9">
                        <div class="event-card position-relative">
                            <div class="event-image" style="background-image: url({{ asset('storage/'.$event->image) }});">
                            </div>
                            <div class="p-4 event-info w-100">
                                <p class="font-weight-bold event-card-header mb-2 of-elipsis">{{ $event->title }}</p>
                                <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                                <img src="{{asset('images/pin-g.svg')}}" alt="">{{ $event->city }}
                                </p>
                                <p class="mb-3" style="font-size:13px; color: rgba(30,36,51,0.6);">
                                <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                                </p>
                                @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6 || Auth::user()->role_id == 8)
                                    @if(isset($event->link_new))
                                        <div class="text-left py-1 mb-3">
                                            <p class="reff_link" style="font-size:13px; line-height:13px; color:rgba(30,36,51,0.6);"><i
                                                        style="color:#F38230;"
                                                        class="fas fa-share-square mr-1"></i>{{$event->link_new}}
                                            </p>
                                        </div>
                                    @else
                                        <div class="text-left my-3">
                                                  <button class="mt-1 pl-3 pr-3 pt-1 pb-1 w-100 resend_btn_class reff_link_btn"
                                                        style="font-size:13px; cursor:pointer;"
                                                        event_id="{{$event->id}}">Сгенерировать ссылку</button>
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

            </div>{{--Row --}}
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 6)
            <div  class="tab-content col-12 row px-0">
           <div class="tab-pane fade col-12" id="all_event_list_pane" role="tabpanel">
                <div class="row justify-content-lg-start justify-content-center">


                        @if(Auth::user()->event_rights)
                        <div class="col-lg-3 col-12 d-lg-none d-block mb-4">
                            <a href="{{ route('event_create') }}">
                                <button class="select-button w-100" style="border:1px solid #0055A7; background-color:transparent; color:#000000;">+ Создать мероприятие</button>
                            </a>
                        </div>
                        @endif
                        <div class="col-6 d-flex">
                            <button class="w-100 select-button_this select_active" data-id="1" data-category="{{$category}}" data-who="this_all">
                               Все Действующие мероприятия
                            </button>
                                <button class="w-100 select-button_this" style="    margin-left: 5px;" data-id="2" data-category="{{$category}}" data-who="this_all">
                               Все Прошедшие мероприятия
                            </button>
                        </div>
                    @if(Auth::user()->event_rights)
                    <div class="col-lg-6 col-12 d-none d-flex justify-content-end">
                    <div style="width: 50%;">
                        <a href="{{ route('event_create') }}">
                            <button class="select-button w-100" style="background-color:#189ddf; color:#fff;">+ Создать мероприятие</button>
                        </a>
                        </div>
                    </div>
                    @endif

                    <div class="col-12 mt-5">
                            <div class="event_selector_this_all row justify-content-lg-start justify-content-center">
                                @include('event.admin.includes.events_active')
                            </div>
                    </div>

                </div>{{--Row --}}
            </div>


           <div class="tab-pane fade show active col-12" id="current_user_event_list_pane" role="tabpanel">
                <div class="row justify-content-lg-start justify-content-center">


                        @if(Auth::user()->event_rights)
                        <div class="col-lg-3 col-12 d-lg-none d-block mb-4">
                            <a href="{{ route('event_create') }}">
                                <button class="select-button w-100" style="border:1px solid #0055A7; background-color:transparent; color:#000000;">Создать мероприятие</button>
                            </a>
                        </div>
                        @endif
                        <div class="col-6 d-flex">
                            <button class="w-100 select-button_this select_active" data-id="1" data-category="{{$category}}" data-who="this">
                                Ваши Действующие мероприятия
                            </button>
                            <button class="w-100 select-button_this" style="margin-left: 1%;" data-id="2" data-category="{{$category}}" data-who="this">
                                Ваши Прошедшие мероприятия
                            </button>

                        </div>
                        
                    @if(Auth::user()->event_rights)
                    <div class="col-lg-6 col-12 d-none d-flex justify-content-end">
                    <div class="" style="    width: 50%;">
                    <a href="{{ route('event_create') }}">
                            <button class="select-button w-100" style="background-color:#189ddf; color:#fff;">Создать мероприятие</button>
                        </a>
                        </div>
                    </div>
                    @endif 

                    <div class="col-12 mt-3">
                            <div class="event_selector_this_all row justify-content-lg-start justify-content-center">
                                @include('event.admin.includes.events_active_this')
                            </div>
                    </div>

                </div>{{--Row --}}
            </div>

            </div>
            @endif






            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3)
                                      <div class="col-lg-3 col-12 d-lg-none d-block mb-4">
                            <a href="{{ route('event_create') }}">
                                <button class="select-button w-100" style="border:1px solid #0055A7; background-color:transparent; color:#000000;">+ Создать мероприятие</button>
                            </a>
                        </div>

                        <div class="col-lg-3 col-6">
                            <button class="btn-all-none w-100 select-button select_active" data-id="1" data-category="{{$category}}">
                            <img src="{{asset('images/Calendar2.svg')}}" alt="">
                                Действующие мероприятия
                            </button>
                        </div>
                        <div class="col-lg-3 col-6">
                            <button class="btn-all-none w-100 select-button" data-id="2" data-category="{{$category}}">
                            <img src="{{asset('images/History2.svg')}}" alt="">
                                Прошедшие мероприятия
                            </button>
                        </div>
                    <div class="col-3">
                      <a href="{{ route('category') }}">
                          <button class="select-button w-100 btn-cat" style="">Редактировать категории</button>
                      </a>
                    </div>
                    <div class="col-lg-3 col-12 d-lg-block d-none">
                        <a href="{{ route('event_create') }}">
                            <button class="select-button w-100 soz-merop text-transform-none font-weight-medium" style="">+ Создать мероприятие</button>
                        </a>
                    </div>


                    <div class="col-12 mt-5">
                            <div class="event_selector row justify-content-lg-start justify-content-center">
                                @include('event.admin.includes.events_active')
                            </div>
                    </div>
            @endif






               </div>{{--Container --}}









@endsection
@push('scripts')
    <script>
        $('.select-button').click(function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var category = btn.data('category');
            if(!btn.hasClass('select_active'))
            {
                $('.event_selector').addClass('disappear');
                $('.select-button').removeClass('select_active');
                btn.addClass('select_active');

                $.ajax({
                    url: '{{ route('switch_event') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "category": category,
                    },
                    success: data => {
                        $('.event_selector').html(data.view).show('slide', {direction: 'left'}, 400);
                        $('.event_selector').removeClass('disappear');
                    },
                    error: () => {
                        $('.event_selector').removeClass('disappear');
                    }
                })
            }
        })


        $('.select-button_this').click(function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var category = btn.data('category');
            if(!btn.hasClass('select_active'))
            {
                $('.event_selector_this_all').addClass('disappear');
                $('.select-button_this').removeClass('select_active');
                btn.addClass('select_active');
                $.ajax({
                    url: '{{ route('switch_event') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "category": category,
                        "who": btn.data('who'),
                    },
                    success: data => {
                        $('.event_selector_this_all').html(data.view);
                        $('.event_selector_this_all').removeClass('disappear');
                    },
                    error: () => {
                        $('.event_selector_this_all').removeClass('disappear');
                    }
                })
            }
        })
        $('.event_list_btn_switch').click(function (e) {
            $('.event_selector_this_all').addClass('disappear');
            $('.select-button_this').removeClass('select_active');
        })
    </script>
@endpush
