@extends('layouts.admin_app')
@section('content')
    <?php
    $agent = new \Jenssegers\Agent\Agent();
    $option = \App\List_option::where('kind','franch')->first();
    ?>
    @include('modals.option_list.client_active',$option)
    <div class="container">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 pb-3 d-flex">
                <p class="main-title text-uppercase text-lg-left text-center">СПИСОК ПРОДАВЦОВ</p>
                <div class="ml-auto col-5 px-0 d-flex justify-content-end align-items-center">
                    <p class="font-weigth-bold mb-0 mr-2">Колличество пользователей на страницу</p>
                    <select class="form-control input-style" name="" id="userPerPage">
                        <option value="25" {{$option->user_per_page == "25" ? 'selected': ""}}>25</option>
                        <option value="50" {{$option->user_per_page == "50" ? 'selected': ""}}>50</option>
                        <option value="100" {{$option->user_per_page == "100" ? 'selected': ""}}>100</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom: 1px solid #E1E8F3;">
                    @php $tab = session('userTab'); @endphp
                    <li class="nav-item mr-3 mb-0">
                        <a class="nav-link {{$tab ? '' :'active'}} {{$tab=='user_list_franch' ? 'active' :''}} user_list_btn_switch font-weight-medium " id="user_list_franch" data-toggle="tab" href="#user_list_franch_pane" role="tab" aria-controls="user_list_franch_btn" aria-selected="true" data-id="1">
                            <img style="width: 15px;" src="{{ asset('images/franch.svg') }}">
                            Франчайзи
                        </a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link {{$tab=='user_list_partner' ? 'active' :''}} user_list_btn_switch font-weight-medium" id="user_list_partner" data-toggle="tab" href="#user_list_partner_pane" role="tab" aria-controls="profile" aria-selected="false" data-id="2">
                            <img style="width: 15px;     vertical-align: text-top;" src="{{ asset('images/prt1.svg') }}">
                            Партнеры
                        </a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link {{$tab=='user_list_sales' ? 'active' :''}} user_list_btn_switch font-weight-medium" id="user_list_sales" data-toggle="tab" href="#user_list_sales_pane" role="tab" aria-controls="profile" aria-selected="false" data-id="3">
                            <img style="width: 15px;vertical-align: text-top;" src="{{ asset('images/bag11.svg') }}">
                            Продавцы САМО
                        </a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link {{$tab=='user_list_ref_partners' ? 'active' :''}} user_list_btn_switch font-weight-medium" id="user_list_ref_partners" data-toggle="tab" href="#user_list_ref_partners_pane" role="tab" aria-controls="profile" aria-selected="false" data-id="2">
                            <img style="width: 15px;vertical-align: text-top;" src="{{ asset('images/clock32.svg') }}">
                            Партнеры в ожидании
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link user_list_btn_switch font-weight-medium" href="{{route('user_activity_statistics')}}">
                            <img style="width: 15px;vertical-align: text-top;" src="{{ asset('images/stat09.svg') }}">
                            Статистика по активностям
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('franchise_create')}}">
                            <button class="btn pt-2 pb-2 pl-3 pr-3 user_list_action_btn user_list_btn_n">
                            <i class="fas fa-plus-circle mr-1"></i>Добавить нового</button>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-12 px-0  {{$tab=='user_list_ref_partners' ? 'fadeOnReady' :''}} d-flex d-none needHide">
                <div class="col-lg-8 col-12 p-3 mt-4">

                    <span class="font-weight-medium">Выбрано: </span>
                    <span class="font-weight-medium" style="display: inline-block;width: 20px;" id="show_checked">0</span>
                    <button class="btn pt-2 pb-2 pl-3 pr-3 user_list_action_btn" id="block_user_btn">
                        <i class="fas fa-ban mr-1 text-transform-none"></i>Заблокировать</button>

                    <div class="dropdown show" style="display: inline-block;">
                        <img class="ml-3" src="{{ asset('images/bx_bx-export.svg') }}">
                        <button  id="dropdownMenuLink" class="dropdown-toggle btn p-0 m-0 user_list_action_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Экспорт в Excel
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="z-index: 1021;">
                            <a class="dropdown-item" href="{{route('export')}}">Всех пользователей</a>
                            <a class="dropdown-item" href="{{route('export_f')}}">Франчайзи</a>
                            <a class="dropdown-item" href="{{route('export_p')}}">Партнеры</a>
                            <a class="dropdown-item" href="{{route('export_s')}}">Sales</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-10 d-flex align-items-center px-0">
                    <input type="text" class="form-control input-style search-style" id="search_list" placeholder="Поиск...">
                    <button class="btn user_list_action_btn p-0 my-0 ml-2" data-toggle="modal" data-target="#ActiveClient">
                        <img src="{{asset('images/settings-b.svg')}}" alt="">
                    </button>
                    <div class="d-none" id="identifier" data-parent="franch" data-id="1"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade  {{$tab ? '' :'show active'}} {{$tab=='user_list_franch' ? 'show active' :''}}" id="user_list_franch_pane" role="tabpanel" aria-labelledby="home-tab">
                        <div id="franch_options">
                            @include('admin.users.include.franch_list')
                        </div>
                    </div>
                    <div class="tab-pane fade  {{$tab=='user_list_partner' ? 'show active' :''}}" id="user_list_partner_pane" role="tabpanel" aria-labelledby="profile-tab">
                        <div id="partner_options">
                            @include('admin.users.include.partner_list')
                        </div>
                    </div>
                    <div class="tab-pane fade {{$tab=='user_list_sales' ? 'show active' :''}}" id="user_list_sales_pane" role="tabpanel" aria-labelledby="profile-tab">
                        <div id="sales_options">
                            @include('admin.users.include.sales_list')
                        </div>
                    </div>
                    <div class="tab-pane fade  {{$tab=='user_list_ref_partners' ? 'show active' :''}}" id="user_list_ref_partners_pane" role="tabpanel" aria-labelledby="profile-tab">
                        <div id="ref_partners_list_">
                            @include('admin.users.include.ref_partners_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('optionChanged') !== null)
        <input type="hidden" name="" value="{{session('optionChanged')}}" id='optionChanged'>
        @php session()->forget('optionChanged'); @endphp
    @endif
    
@endsection
@push('scripts')
    <script src="{{ asset('js/option_save.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.fadeOnReady').css('opacity',0);
            $('.fadeOnReady').animate({
                'height':0,
            },500);
            let val = $('#optionChanged').val();
            if(val=="success"){
                $('.preloader').fadeOut(50);

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Настройки изменены!',
                    showConfirmButton: false,
                });
            }
            let active_link = $('.user_list_btn_switch.active').attr('data-id');
            document.getElementById('identifier').setAttribute('data-id',active_link);

        });
        $(document).on("change",'#userPerPage',function(){
            let btn = $(this).val();
            if(btn){
                axios.post('/setUserPerPage',{
                    'perPage':btn,
                }).then(function(response){
                    location.reload();
                });
            }
        })
        $('.user_list_btn_switch').click(function (e) {
            var btn = $(e.currentTarget);
            var tab = btn.attr('id');
            var id = btn.data('id');
            var href = btn.attr('id');
            if (href == 'user_list_ref_partners'){
                $('.needHide').css('opacity',0);
                $('.needHide').animate({
                    'height':0,
                },500);
            }else{
                $('.needHide').css('opacity',1);
                $('.needHide').animate({
                    'height':'102px',
                },500);
            }
            
            document.getElementById('identifier').setAttribute('data-id',id);

            axios.post('/setUserTab',{
                'tab':tab,
            });

        })
    </script>
    <script>
        $(document).on('keyup click', '#search_list', function (e) {
            let value = $(e.currentTarget).val();
            let kind = document.getElementById('identifier').dataset.parent;
            let type = document.getElementById('identifier').dataset.id;
            console.log('IN');
            $.ajax({
                url: '{{ route('search_seller') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "kind": kind,
                    "type": type,
                    "value": value,
                },
                success: data => {
                    console.log(data,'ss');
                    if (type == 1){
                        $('#franch_options').html(data.view).show('slide', {direction: 'left'}, 400);
                    }
                    else if (type == 2){
                        $('#partner_options').html(data.view).show('slide', {direction: 'left'}, 400);
                    }
                    else if (type == 3){
                        console.log('use');
                        $('#sales_options').html(data.view).show('slide', {direction: 'left'}, 400);
                    }
                    // $('.report_block').removeClass('disappear');
                },
                error: () => {
                    // $('.report_block').removeClass('disappear');
                }
            })
        })

        $(".change_pay_btn").click(function() {

            let user_id = this.getAttribute("user_id");
            that=this;
            $.ajax({
            type: 'POST',
            url: "/pending_change_paid",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'user_id': user_id,
            },
            success: function (data) {
                window.location.reload();
                // $('#ref_partners_list_').html(data.view).show('slide', {direction: 'left'}, 400);
                },
            });


        });

    </script>
@endpush
