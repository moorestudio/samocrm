<?php
$agent = new \Jenssegers\Agent\Agent();

?>
<div class="d-flex justify-content-between report-header">
    <div class="col">&nbsp;</div>
    @foreach($option->options as $opt)
        @if($opt['option'] == 'fullname()')
            <div class="col-lg-3 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @elseif($opt['option'] == 'city')
            <div class="col-lg-2 col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @else
            <div class="{{count($option->options) == 5 ?  'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} col-12"><p class="mb-0 title">{{$opt['slug']}}</p></div>
        @endif

    @endforeach
    <!-- <div class="col-lg-2 col-12"><p class="mb-0 title">срок окон контр</p></div> -->
    <div class="col-lg-2 col-12"><p class="mb-0 title text-center">Действие</p></div>
</div>
@if(count($data['franchise_all']))
@foreach($data['franchise_all'] as $franchise)
    <div class="d-flex align-items-center report-card special-card">
        @if(!$agent->isPhone())
            <div class="custom-checkbox custom-control text-center col" user_id="{{$franchise->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$franchise->id}}">
                <label class="custom-control-label" for="{{$franchise->id}}"></label>
            </div>

        @foreach($option->options as $opt)
            @if($opt['option'] == 'fullname()')
                    <div class="col-lg-3 d-lg-flex d-none"><a style="color:#000000;" href="{{route('franchise_show',[$franchise->id])}}"><p class="mb-0">{{ $franchise->fullname() }}</p></a></div>
            @elseif($opt['option'] == 'city')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{ $franchise->city }}</p></div>
            @elseif($opt['option'] == 'contract_date_end')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{date('Y-m-d', strtotime($franchise->contract_date_end))}}</p></div>
            @elseif($opt['option'] == 'contract_date')
                    <div class="col-lg-2 d-lg-flex d-none"><p class="mb-0">{{date('Y-m-d', strtotime($franchise->contract_date))}}</p></div>
            @else
                <div class="{{count($option->options) == 5 ? 'col-lg-2': (count($option->options) == 4 ? 'col-lg-2' : (count($option->options) == 3 ? 'col-lg-2' : (count($option->options) == 2 ? 'col-lg-4' : 'col-lg-8')))}} d-lg-flex d-none"><p class="mb-0">{{ $franchise[$opt['option']] }}</p></div>
            @endif
        @endforeach
            <div class="col-lg-2 d-lg-flex d-none justify-content-center">
                <a style="color:#000000;" href="{{route('franchise_update',[$franchise->id])}}"><i user_id="{{$franchise->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                <i user_id="{{$franchise->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
              <div class="p-4">
                @if($franchise->event_rights)
                    <i class="fas fa-check-square  change_to_event_manager" user_id = "{{$franchise->id}}" event_rights = "{{$franchise->event_rights}}" aria-hidden="true"  data-toggle="tooltip" title="Может создавать мероприятиe, изменить?"></i>
                @else
                    <i class="fas fa-square change_to_event_manager" user_id = "{{$franchise->id}}" event_rights = "{{$franchise->event_rights}}" aria-hidden="true" data-toggle="tooltip" title="НЕ Может создавать мероприятие, изменить?"></i>
                @endif
              </div>
            </div>
        @else
            <div class="col-10">
                @foreach($option->options as $opt)
                    @if($opt['option'] == 'fullname()')
                        <div><a style="color:#000000;" href="{{route('franchise_show',[$franchise->id])}}"><p class="mb-0">{{ $franchise->fullname() }}</p></a></div>
                    @else
                        <div><p class="mb-0">{{ $franchise[$opt['option']] }}</p></div>
                    @endif
                @endforeach
                <div class="mt-3">
                    <a style="color:#000000;" href="{{route('franchise_update',[$franchise->id])}}"><i user_id="{{$franchise->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                    <i user_id="{{$franchise->id}}" class="fas fa-ban block_icon ml-4" data-toggle="tooltip" title="Заблокировать!"></i>
                </div>
            </div>
            <div class="custom-checkbox custom-control text-center col-2 d-flex align-items-center justify-content-center" user_id="{{$franchise->id}}">
                <input type="checkbox" class="custom-control-input" name="franchise_partner_user" id="{{$franchise->id}}">
                <label class="custom-control-label" for="{{$franchise->id}}"></label>
            </div>
        @endif
    </div>

@endforeach
{{ $data['franchise_all']->links() }}
@else
    <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/disabled.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>
@endif
