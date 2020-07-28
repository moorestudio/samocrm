@forelse($users as $user)
  @php $name = $user->last_name.' '.$user->name.' '. $user->middle_name; @endphp
  <div class="report-header d-flex align-item-center franch_row_class w-100" onclick="get_user(this)" user_name="{{$name}}" user_id="{{$user->id}}">
    <div class="col-4 title of-elipsis">
        {{$name}}
    </div>
    <div class="col-4 pl-0 title of-elipsis">
        {{$user->email}}
    </div>
    <div class="col-4 pl-0 title of-elipsis">
        {{$user->contacts}}
    </div>
  </div>
@empty
  <div class="d-flex justify-content-center mt-5">
    Ничего не найдео
  </div>
@endforelse
