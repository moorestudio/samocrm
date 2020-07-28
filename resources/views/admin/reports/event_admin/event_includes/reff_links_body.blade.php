<div class="row">
    @if(count($reff_links))
    <h3>Статистика по реферальным ссылкам на данное мероприятие</h3>
    <div class="d-flex report-header col-12 px-0">
        <div class="col-lg-2 col-12"><p class="mb-0 title">ФИО продавца</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Роль продавца</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Кол.переходов</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Кол.рег</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Кол.покупок</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Кол.броней</p></div>
    </div>

        @foreach($reff_links as $link)
            <?php  
                $ref_users = $link->relationships->pluck('user_id');
                $count_buys = count(\App\Ticket::whereIn('user_id',$ref_users)->where('type','buy')->get());
                $count_reserved = count(\App\Ticket::whereIn('user_id',$ref_users)->where('type','reserve')->get());
            ?>
            <div class="d-flex flex-wrap justify-content-center special-card report-card col-12 px-0">
                <div class="col-lg-2 col-12">
                    <a href="{{ route('partner_clients',['id'=>$link->user->id]) }}" >
                        <p class="mb-0 font-weight-bold">{{$link->user->name}}</p>
                    </a>
                </div>
                <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{\App\Role::find($link->user->role_id)->display_name}}</p></div>
                <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{$link->count}}</p></div>
                <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{count($link->relationships)}}</p></div>
                <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{$count_buys}} </p></div>
                <div class="col-lg-2 col-12"><p class="mb-0 font-weight-bold">{{$count_reserved}} </p></div>

            </div>
        @endforeach
    @else
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Список пуст</span>
        </div>
    @endif

</div>