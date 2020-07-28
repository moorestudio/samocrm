@forelse($data['joined_table'] as $user)
  <div class="d-flex special-card event report-card">
      <div class="col-5 d-lg-flex d-none">
          <p class="mb-0 list-link of-elipsis">{{$user->name}} {{$user->last_name}} {{$user->middle_name}}</p>
      </div>
      <div class="col-7 d-lg-flex d-none">
            @if($user->type==="buy")
              <button user_id={{$user->id}} ticket_id={{$user->ticket_id}} class="change_type_btn attend border-0">Не участвовал <img class="mx-2" src="{{asset('images/attend-not-check.svg')}}" alt=""> <span class="disabled-text">Участвовал</span></button>
            @elseif($user->type==="done")
              <button user_id={{$user->id}} ticket_id={{$user->ticket_id}} class="change_type_btn attend border-0"><span class="disabled-text">Не участвовал</span> <img  class="mx-2" src="{{asset('images/attend-check.svg')}}" alt=""><span class="active-text">Участвовал</span></button>
            @endif
      </div>
    </div>
@empty
  <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
        <img src="{{asset('images/server.svg')}}" alt="">
        <div class="w-100"></div>
        <span class="second-title mt-2 empty-element">Список пуст</span>
    </div>    
@endforelse
