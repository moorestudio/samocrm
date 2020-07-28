<?php
$agent = new \Jenssegers\Agent\Agent();
?>

<div class="row report-header">
    <div style="width:50px;"></div>
    <div class="col-lg-2 col-12"><p class="mb-0 title">откуда узнали</p></div>
    @foreach($option->options as $opt)
        @if($opt['option'] == 'fullname()')
            <div class="col-lg-2 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @elseif($opt['option'] == 'city')
            <div class="col-lg-2 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @else
            <div class="{{count($option->options) == 4 ? 'col-lg-2': (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-6' : 'col-lg-12'))}} col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @endif

    @endforeach
    <div class="col-lg-1 col-12"><p class="mb-0 title">Действие</p></div>
</div>
@if(count($inactive_clients))
@foreach($inactive_clients as $client)
    @php 
        $source_list = \App\Ticket::where('user_id',$client->id)->pluck('found')->unique()->toArray(); 
        $source_list_str = implode(", ", $source_list)
    @endphp
    <div class="row report-card special-card">
        @if(!$agent->isPhone())
            <div class="custom-checkbox custom-control text-center d-lg-flex d-none" style="width:50px;" user_id="{{$client->id}}">
            <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$client->id}}">
            <label class="custom-control-label" for="{{$client->id}}"></label>
            </div>
                <div class="col-2 d-lg-flex d-none" data-toggle="tooltip" title="{{$source_list_str}}">
                    <p class="mb-0 list-link">{{  \Illuminate\Support\Str::limit($source_list_str, 10, $end='...') }}</p>
                </div>
            @foreach($option->options as $opt)
                {{--@dd($opt['option'])--}}
                @if($opt['option'] == 'fullname()')
                        <div class="col-2 d-lg-flex d-none"><a
                                    href="{{route('user_profile',['user_id' => $client->id])}}"
                                    class="user_link"><p class="mb-0 list-link">{{ $client->fullname() }}</p></a>
                        </div>
                @elseif($opt['option'] == 'promo_name')
                        <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{ $client->promoname() }}</p>
                        </div>
                @elseif($opt['option'] == 'promo_discount')
                        <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{ $client->promodiscount() }}</p>
                        </div>
                @elseif($opt['option'] == 'q_bought')
                        <div class="col-lg-2 d-lg-flex d-none">
                            <!-- <p class="mb-0">{{ \App\Count_buys::where('user_id',$client->id)->sum('count')  }}</p> -->
                            <p class="mb-0">{{ $client->ticket_count() }}</p>
                        </div>
                @else
                        <div class="{{count($option->options) == 4 ? 'col-lg-2': (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8'))}} d-lg-flex d-none"><p class="mb-0">{{ $client[$opt['option']] }}</p>
                        </div>

                @endif
            @endforeach
            <div class="col-1">
                <a style="color:#000000;" href="{{ route('profile_edit',[$client->id])}}"><i user_id="{{$client->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                <i user_id="{{$client->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
            </div>
        @else
            <div class="col-10">
                @foreach($option->options as $opt)
                    @if($opt['option'] == 'fullname()')
                        <div><a href="{{route('user_profile',['user_id' => $client->id])}}" class="user_link"><p class="mb-0">{{ $client->fullname() }}</p></a></div>
                    @else
                        <div><p class="mb-0">{{ $client[$opt['option']]}}</p></div>
                    @endif
                @endforeach

            </div>
        @endif
    </div>
@endforeach
{{ $inactive_clients->appends(array_except(Request::query(), 'inactive_page'))->links() }}
@else
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Список пуст</span>
        </div>
@endif
