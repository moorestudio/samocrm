@if(count($activities->whereIn('type',['block','active'])))
  <div class="col-12 px-0">
      <p class="secondary-title">Действия над пользователем</p>
      <div class="d-flex report-header">
          <div class="col-lg-4 col-12"><p class="mb-0 title">Дата</p></div>
          <div class="col-lg-4 col-12"><p class="mb-0 title">Время</p></div>
          <div class="col-lg-4 col-12"><p class="mb-0 title">Действие</p></div>
      </div>
    @foreach($activities->whereIn('type',['block','active']) as $activity )
      <div class="d-flex special-card report-card">
          <div class="col-lg-4 d-lg-flex d-none">
              <p class="mb-0">{{$activity->date}}</p>
          </div>
          <div class="col-lg-4 d-lg-flex d-none">
              <p class="mb-0">{{$activity->time}}</p>
          </div>
          @php $name = $activity->type == "block" ? "Блокирован" : "Активирован" @endphp
          <div class="col-lg-4 d-lg-flex d-none">
              <p class="mb-0">{{$name}}</p>
          </div>
      </div>
    @endforeach
  </div>
@endif
