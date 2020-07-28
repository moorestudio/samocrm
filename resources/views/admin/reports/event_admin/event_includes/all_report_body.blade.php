<ul class="nav nav-tabs" role="tablist" style="border-bottom: 1px solid #E1E8F3;">
  <li class="nav-item mb-0">
    <a class="nav-link active user_list_btn_switch" id="KGS-tab" data-toggle="tab" href="#tab_panel_KGS" role="tab" aria-controls="profile" aria-selected="true">KGS</a>
  </li>
  <li class="nav-item">
    <a class="nav-link user_list_btn_switch" id="RUB-tab" data-toggle="tab" href="#tab_panel_RUB" role="tab" aria-controls="contact" aria-selected="false">RUB</a>
  </li>

  <li class="nav-item">
    <a class="nav-link user_list_btn_switch" id="USD-tab" data-toggle="tab" href="#tab_panel_USD" role="tab" aria-controls="home" aria-selected="false">USD</a>
  </li>

  <li class="nav-item">
    <a class="nav-link user_list_btn_switch" id="RUB-tab" data-toggle="tab" href="#tab_panel_KZT" role="tab" aria-controls="contact" aria-selected="false">KZT</a>
  </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="tab_panel_KGS" role="tabpanel" aria-labelledby="profile-tab">
        @include('admin.reports.event_admin.event_includes.main_rep_tabs.tab_currency',['currency'=>'KGS','index'=>2])
    </div> 
    <div class="tab-pane fade " id="tab_panel_RUB" role="tabpanel" aria-labelledby="profile-tab">
        @include('admin.reports.event_admin.event_includes.main_rep_tabs.tab_currency',['currency'=>'RUB','index'=>3])
    </div> 
    <div class="tab-pane fade " id="tab_panel_USD" role="tabpanel" aria-labelledby="home-tab">
        @include('admin.reports.event_admin.event_includes.main_rep_tabs.tab_currency',['currency'=>'USD','index'=>1])
    </div>
    <div class="tab-pane fade" id="tab_panel_KZT" role="tabpanel" aria-labelledby="contact-tab">
        @include('admin.reports.event_admin.event_includes.main_rep_tabs.tab_currency',['currency'=>'KZT','index'=>4])
    </div>
</div>
<div>
    <div class="py-4">
        <p class="report_block_title">Список мероприятий</p>
    </div>
    @if(count($all_events))
    <div class="d-flex report-header mt-3">
        <div class="col-lg-2 col-12"><p class="mb-0 title">Название</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Дата начала</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Дата конца</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Организатор</p></div>
        <div class="col-lg-1 col-12"><p class="mb-0 title">Спикер</p></div>
        <div class="col-lg-1 col-12"><p class="mb-0 title">#Прод</p></div>
        <div class="col-lg-1 col-12"><p class="mb-0 title">Выручка</p></div>
        <div class="col-lg-1 col-12"><p class="mb-0 title">Чистая $</p></div>
        <!-- <div class="col-lg-1 col-12"><p class="mb-0 font-weight-bold">Расход $</p></div> -->
    </div>
    @foreach($all_events as $event)
        <div class="d-flex special-card report-card">
            <div class="col-lg-2 col-12">
                <a href="{{route('eventAdmin/report/single/event',$event)}}" style="font-decoration:none; color:#000000;">
                <p class="mb-0 font-weight-bold">{{ \Illuminate\Support\Str::limit($event->title, 15, $end='...') }}</p>
                </a>
            </div>
            <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{date('Y-m-d', strtotime(\Carbon\Carbon::parseFromLocale($event->date, 'ru')))}}</p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{date('Y-m-d', strtotime(\Carbon\Carbon::parseFromLocale($event->end_date, 'ru')))}}</p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{ \App\User::find($event->user_id)->name }}</p></div>
            <div class="col-lg-1 col-12"><p class="mb-0 font-weight-bold">{{ $event->mentor }}</p></div>
            <div class="col-lg-1 col-12">
                <p class="mb-0 font-weight-bold">
                <?php
                    $count_buy = count(\App\Ticket::all()->where('event_id',$event->id)->whereIn('type',['buy','done']));
                ?>  

                {{ $count_buy }} 

                </p>
            </div>

            <div class="col-lg-1 col-12"><p class="mb-0 font-weight-bold">{{ $event->financial->total_income }} {{\App\CurrencyNames::find($event->currency)->currency}}</p>
            </div>
            <div class="col-lg-1 col-12">
                <p class="mb-0 font-weight-bold">
                {{ $event->financial->total}}  {{\App\CurrencyNames::find($event->currency)->currency}}

                </p>
            </div>
<!--                 <div class="col-lg-1 col-12">
                <p class="mb-0 font-weight-bold">
                {{ $event->financial->total_consuption }}  {{\App\CurrencyNames::find($event->currency)->currency}}

                </p>
            </div> -->
        </div>
    @endforeach
    @else
        <p class="second-title text-uppercase text-center mt-5">На выбранный период нет данных</p>
    @endif

</div>
