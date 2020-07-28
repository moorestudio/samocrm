<?php
$agent = new \Jenssegers\Agent\Agent();

?>
<div class="d-flex justify-content-between report-header">
    <div class="col-1"></div>
    <div class="col-lg-1 col-12">
        <p class="mb-0 title">
            Франчайзи
        </p>
    </div>
    @foreach($option->options as $opt)
        @if($opt['option'] == 'fullname()')
            <div class="col-lg-1 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @elseif($opt['option'] == 'city')
            <div class="col-lg-2 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @else
            <div class="{{count($option->options) == 5 ?  'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @endif
    @endforeach
    <!-- <div class="col-lg-1 col-12"><p class="mb-0 title">срок окон контр</p></div> -->
    <div class="col-lg-1 col-12"><p class="mb-0 title">Действие</p></div>
</div>
@if(count($data['partners_all']))
@foreach($data['partners_all'] as $partner)
    <div class="d-flex justify-content-between special-card report-card">
        @if(!$agent->isPhone())
            <div class="custom-checkbox custom-control text-center  d-lg-flex d-none col-1" user_id="{{$partner->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$partner->id}}">
                <label class="custom-control-label" for="{{$partner->id}}"></label>
            </div>
            @foreach($option->options as $opt)
                @if($opt['option'] == 'fullname()')
                    <div class="col-lg-1 d-lg-flex d-none">
                        @if(isset($partner->franchise))
                            <a style="color:#000000;" href="{{route('franchise_show',[$partner->franchise_id])}}">
                                <p class="mb-0 list-link" data-toggle="tooltip" title="Подробнее о Франчайзи">{{ $partner->franchise->last_name }}</p>
                            </a>
                        @else
                            <p class="mb-0 ">Офис Само</p>
                        @endif
                    </div>
                    <div class="col-lg-1 d-lg-flex d-none"><a style="color:#000000;" href="{{route('franchise_show',[$partner->id])}}"><p class="mb-0 list-link"  data-toggle="tooltip" title="Подробнее о партнере">{{ $partner->fullname() }}</p></a></div>
                @elseif($opt['option'] == 'city')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{ $partner->city }}</p></div>  
                @elseif($opt['option'] == 'contract_date_end')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{date('Y-m-d', strtotime($partner->contract_date_end))}}</p></div>
                @elseif($opt['option'] == 'contract_date')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{date('Y-m-d', strtotime($partner->contract_date))}}</p></div>

                @else
                    <div class="{{count($option->options) == 5 ? 'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} d-lg-flex d-none"><p class="mb-0">{{ $partner[$opt['option']] }}</p></div>
                @endif
            @endforeach
<!--             <div class="col-lg-1 d-lg-flex d-none">
                <p class="mb-0">{{date('Y-m-d', strtotime($partner->contract_date_end))}}</p>
            </div> -->
            <div class="col-lg-1 d-lg-flex d-none">
                <a style="color:#000000;" href="{{route('franchise_update',[$partner->id])}}"><i user_id="{{$partner->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                <i user_id="{{$partner->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
            </div>
        @else
            <div class="col-10">
                @foreach($option->options as $opt)
                    @if($opt['option'] == 'fullname()')
                <div><a style="color:#000000;" href="{{route('franchise_show',[$partner->id])}}"><p class="mb-0">{{ $partner->fullname() }}</p></a></div>
                    @else
                        <div><p class="mb-0">{{ $partner[$opt['option']] }}</p></div>
                    @endif
                @endforeach
                <div class="mt-3">
                    <a style="color:#000000;" href="{{route('franchise_update',[$partner->id])}}"><i user_id="{{$partner->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                    <i user_id="{{$partner->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
                </div>
            </div>
            <div class="custom-checkbox custom-control text-center col-2 d-flex align-items-center justify-content-center" user_id="{{$partner->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$partner->id}}">
                <label class="custom-control-label" for="{{$partner->id}}"></label>
            </div>
        @endif
    </div>
@endforeach
{{ $data['partners_all']->appends(array_except(Request::query(), 'partner_page'))->links() }}

@else
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>
@endif