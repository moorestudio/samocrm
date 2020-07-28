@if(count($activities->where('type','visit')))
    <div class="col-12 px-0">
        <p class="secondary-title">История пользователя</p>
        <div class="d-flex  report-header">
            <div class="col-lg-6 col-12"><p class="mb-0 title">Дата</p></div>
            <div class="col-lg-6 col-12"><p class="mb-0 title">Время</p></div>
        </div>
      @foreach($activities->where('type','visit') as $activity )
      <div class="d-flex special-card report-card">
          <div class="col-lg-6 d-lg-flex d-none">
              <p class="mb-0">{{$activity->date}}</p>
          </div>
          <div class="col-lg-6 d-lg-flex d-none">
              <p class="mb-0">{{$activity->time}}</p>
          </div>
      </div>
      @endforeach
    </div>
@endif
