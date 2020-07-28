<?php
$agent = new \Jenssegers\Agent\Agent();

?>
<div class="d-flex justify-content-between report-header">
    <div class="col-1"></div>
<!--     <div class="col-lg-2 col-12">
        <p class="mb-0 text-white font-weight-bold">
            Франчайзи
        </p>
    </div> -->
    @foreach($option->options as $opt)
        @if($opt['option'] == 'fullname()')
            <div class="col-lg-2 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @elseif($opt['option'] == 'city')
            <div class="col-lg-1 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div> 
        @else
            <div class="{{count($option->options) == 5 ?  'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @endif
    @endforeach
    <!-- <div class="col-lg-2 col-12"><p class="mb-0 title">Cрок окон контр</p></div> -->
    <div class="col-lg-1 col-12"><p class="mb-0 title">Действие</p></div>
</div>
@if(count($data['sales_all']))

@foreach($data['sales_all'] as $sales)
    <div class="d-flex align-items-center justify-content-between special-card report-card">
        @if(!$agent->isPhone())
            <div class="custom-checkbox custom-control text-center d-lg-flex d-none col-1" user_id="{{$sales->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$sales->id}}">
                <label class="custom-control-label" for="{{$sales->id}}"></label>
            </div>
            @foreach($option->options as $opt)
                @if($opt['option'] == 'fullname()')
                    <div class="col-2 d-lg-flex d-none"><a style="color:#000000;" href="{{route('franchise_show',[$sales->id])}}"><p class="mb-0">{{ $sales->fullname() }}</p></a></div>
                @elseif($opt['option'] == 'city')
                        <div class="col-1 d-lg-flex d-none"><p class="mb-0">{{ $sales->city }}</p></div>     
                @else
                    <div class="{{count($option->options) == 5 ? 'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} d-lg-flex d-none"><p class="mb-0">{{ $sales[$opt['option']] }}</p></div>
                @endif
            @endforeach
<!--             <div class="col-2 d-lg-flex d-none">
                <p class="mb-0">{{date('Y-m-d', strtotime($sales->contract_date_end))}}</p>
            </div> -->
            <div class="col-1 d-lg-flex d-none">
                <a style="color:#000000;" href="{{route('franchise_update',[$sales->id])}}"><i user_id="{{$sales->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                <i user_id="{{$sales->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
            </div>
        @else
            <div class="col-10">
                @foreach($option->options as $opt)
                    @if($opt['option'] == 'fullname()')
                <div><a style="color:#000000;" href="{{route('franchise_show',[$sales->id])}}"><p class="mb-0">{{ $sales->fullname() }}</p></a></div>
                    @else
                        <div><p class="mb-0">{{ $sales[$opt['option']] }}</p></div>
                    @endif
                @endforeach
                <div class="mt-3">
                    <a style="color:#000000;" href="{{route('franchise_update',[$sales->id])}}"><i user_id="{{$sales->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                    <i user_id="{{$sales->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
                </div>
            </div>
            <div class="custom-checkbox custom-control text-center col-2 d-flex align-items-center justify-content-center" user_id="{{$sales->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$sales->id}}">
                <label class="custom-control-label" for="{{$sales->id}}"></label>
            </div>
        @endif
    </div>
@endforeach
{{ $data['sales_all']->appends(array_except(Request::query(), 'sale_page'))->links() }}

@else
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>
@endif