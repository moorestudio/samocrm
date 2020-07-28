<div>
    <div class="d-flex report-header mt-4">
        <div class="col-lg-2 col-12"><p class="mb-0 title">ФИО</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title">Email</p></div>
        <!-- <div class="col-lg-2 col-12"><p class="mb-0 title">Тел</p></div> -->
        <div class="col-lg-2 col-12"><p class="mb-0 title">Тип оплаты</p></div>
        <div class="col-lg-2 col-12"><p class="mb-0 title px-4">Статус оплаты</p></div>
        <div class="col-lg-3 col-12"><p class="mb-0 title">Статус данных</p></div>
        <div class="col-lg-1 col-12"><p class="mb-0 title"></p></div>
    </div>
    @if(count($data['pending_users_list']))
    @foreach($data['pending_users_list'] as $pending_users)
        <?php 
            $relationship = \App\partner_referral_relationship::where('user_id',$pending_users->id)->first()
        ?>
        <div class="d-flex align-items-center special-card report-card">
            <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                {{$pending_users->name}} {{$pending_users->middle_name}} {{$pending_users->last_name}}
            </p></div>
            <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                {{$pending_users->email}}
            </p></div>
<!--             <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                {{$pending_users->phone}}
            </p></div> -->
            <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                {{$pending_users->pay_type}}
            </p></div>
            <div class="col-lg-3 col-12"><p class="mb-0 of-elipsis">
            @if($pending_users->pay_type == 'cash')
              @if($pending_users->paid===0)
              <button user_id={{$pending_users->id}} class="pr-0 pl-0 btn btn-outline-dark change_pay_btn p-1" style="border-left-width:40px!important;width: 95%;border-radius: 5px;border-color:#B6B6B6!important;" data-toggle="tooltip" title="Измененить статус на оплатил, создать пользователя и отправить пригласительное!">Не оплатил</button>
              @elseif($pending_users->paid===1)
              Оплатил
              @endif
            @else
              Оплатил
            @endif    

            </p></div>
        </div>
    @endforeach
        {{ $data['pending_users_list']->appends(array_except(Request::query(), 'pending_page'))->links() }}
    @endif
    
    @if(count($data['ref_partners_list']))
    <p class="mb-2 secondary-title">Партнеры без бонусов</p>
    @foreach($data['ref_partners_list'] as $ref_p)
        @if($ref_p->percent <= 0 and !$ref_p->contract)
            <?php 
                $relationship = \App\partner_referral_relationship::where('user_id',$ref_p->id)->first()
            ?>
            <div class="d-flex align-items-center special-card report-card">

                <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                    {{$ref_p->fullName()}}
                </p></div>
                <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                    {{$ref_p->email}}
                </p></div>
    <!--             <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                    {{$ref_p->contacts}}
                </p></div> -->
                <div class="col-lg-2 col-12"><p class="mb-0 of-elipsis">
                    <!-- $relationship->pay_type -->
                </p></div>
                <div class="col-lg-2 col-12 px-4"><p class="mb-0 of-elipsis pl-4">
                    <!-- $relationship->paid==1?"Оплачен":"Не Оплачен" -->
                </p></div>
                <div class="col-lg-3 col-12"><p class="mb-0 of-elipsis">
                    <?php 
                        $per = $ref_p->percent > 0 ? "Бонус назначен, ":"Бонус Не назначен,";
                        $cont = $ref_p->contract ? "Договор загружен, ":"Договор Не загружен";
                    ?>
                    {{$per}}
                    {{$cont}}
                </p>
                </div>
                <div class="col-lg-1 col-12">
                    <p class="mb-0 font-weight-bold of-elipsis">
                        <a style="color:#000000;" href="{{route('franchise_update',[$ref_p->id])}}"><i user_id="{{$ref_p->id}}" class="fas fa-pen edit_icon ml-1" data-toggle="tooltip" title="Редактировать!"></i></a>
                    </p>
                </div>
            </div>
        @endif
    @endforeach
    {{ $data['ref_partners_list']->appends(array_except(Request::query(), 'ref_page'))->links() }}
    @else
        <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
            <img src="{{asset('images/disabled.svg')}}" alt="">
            <div class="w-100"></div>
            <span class="second-title mt-2 empty-element">Список пуст</span>
        </div>
    @endif
    
</div>
