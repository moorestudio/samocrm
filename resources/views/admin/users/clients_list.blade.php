@extends('layouts.admin_app')
@section('content')
{{--     <div class="container">
        <div class="position-relative adaptive_block">
        <div class="row pt-3">
            <div class="col"><h2 class="text-center">Список всех клиентов</h2></div>
        </div>
        <div class="row pt-3">
            <div class="col-6">
                <div class="user_show_list">
                <h3 class="text-center">Закрепленные Клиенты</h3>
                  @foreach($data['clients_all'] as $client)
                    @if(isset($client->franchise_id))
                      <a href="{{route('user_profile',['user_id' => $client->id])}}" class="user_link">
                      <div class="user_card py-1 px-2">
                              <span class="user_name">Продавец: {{ $client->franchise->name }} --</span>
                              <span class="user_name">{{ $client->fullName() }}</span>
                      </div>
                      </a>
                    @endif
                  @endforeach
                </div>
            </div>
            <div class="col-6">
                <div class="user_show_list">
                <h3 class="text-center">Не закрепленные Клиенты</h3>
                  @foreach($data['clients_all'] as $client)
                    @if(empty($client->franchise_id))
                      <a href="{{route('user_profile',['user_id' => $client->id])}}" class="user_link">
                      <div class="user_card py-1 px-2">
                              <span class="user_name">{{ $client->fullName() }}</span>
                      </div>
                      </a>
                    @endif
                  @endforeach

                </div>
            </div>

        </div>
        </div>
    </div> --}}



        <?php
    $agent = new \Jenssegers\Agent\Agent();
        $option = \App\List_option::where('kind','client')->where('type', 1)->first();
    ?>
@include('modals.option_list.client_active',$option)
    <div class="container">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 pb-3 d-flex">
                <p class="main-title text-uppercase text-lg-left text-center">Список всех клиентов</p>
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
            <div class="col-12 px-0">
                @php $tab = session('clientTab'); @endphp
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom:1px solid #E1E8F3;">
                    <li class="nav-item mr-3 my-lg-0 mt-3">
                        <a class="nav-link {{$tab ? '' :'active'}} {{$tab=='user_list_franch' ? 'active' :''}} user_list_btn_switch" id="user_list_franch" data-toggle="tab" href="#clients_with_sales" role="tab" aria-controls="user_list_franch_btn" aria-selected="true" data-id="1">
                            <img src="{{ asset('images/user_list_partner.svg') }}">
                            Закрепленные Клиенты</a>
                    </li>
                    <li class="nav-item my-lg-0 mt-3">
                        <a class="nav-link {{$tab=='user_list_partner' ? 'active' :''}} user_list_btn_switch" id="user_list_partner" data-toggle="tab" href="#clients_without" role="tab" aria-controls="profile" aria-selected="false" data-id="2">
                            <img src="{{ asset('images/user_list_franch.svg') }}">
                            Не закрепленные Клиенты</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 col-12 py-3 px-0 mt-4">
                <span class="font-weight-bold">Выбрано: </span>
                <span class="font-weight-bold" style="display: inline-block;width: 20px;" id="show_checked">0</span>
                <img src="{{asset('images/block.svg')}}" alt="">
                <button class="btn user_list_action_btn p-0 m-0" id="block_user_btn">
                    Заблокировать
                </button>
                <div class="dropdown show" style="display: inline-block;">
                  <img class="ml-3" src="{{ asset('images/bx_bx-export.svg') }}">
                  <button  id="dropdownMenuLink" class="dropdown-toggle btn p-0 m-0 user_list_action_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Экспорт в Excel
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="z-index: 1021;">
                      <a class="dropdown-item" href="{{route('export_c')}}">Всех клиентов</a>
                      <a class="dropdown-item" href="{{route('export_c_with')}}">Закрепленных</a>
                      <a class="dropdown-item" href="{{route('export_c_without')}}">Не закрепленных</a>
                  </div>
              </div>
            </div>
            <div class="col-lg-6 col-12 py-3 px-0 mt-lg-4 mt-1 text-right">
                <div class="row justify-content-lg-end justify-content-center">
                    <div class="col-lg-6 col-10 d-flex align-items-center">
                        <input type="text" class="form-control input-style search-style" id="search_list" placeholder="Поиск...">
                        <button class="btn user_list_action_btn p-0 my-0 ml-2" data-toggle="modal" data-target="#ActiveClient">
                            <img src="{{asset('images/settings-b.svg')}}" alt="">
                        </button>
                    </div>
                    <div class="d-none" id="identifier" data-parent="client" data-id="{{$tab ? '' :'1'}}{{$tab=='user_list_franch' ? '1' :'2'}}"></div>
                </div>
            </div>
            <div class="col-12 ">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade {{$tab=='user_list_franch' ? 'show active' :''}}" id="clients_with_sales" role="tabpanel" aria-labelledby="home-tab">
                        <div id="active_clients">
                            @include('admin.users.include.active_list')
                        </div>
                    </div>
                    <div class="tab-pane fade {{$tab=='user_list_partner' ? 'show active' :''}}" id="clients_without" role="tabpanel" aria-labelledby="profile-tab">
                        <div id="inactive_clients">
                            @include('admin.users.include.inactive_list')
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
    });
    $(document).on("change",'#userPerPage',function(){
        let btn = $(this).val();
        if(btn){
            axios.post('/setClientPerPage',{
                'perPage':btn,
            }).then(function(response){
                location.reload();
            });
        }
    })
    </script>
    <script>
        $('.user_list_btn_switch').click(function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');
            var tab = btn.attr('id');

            document.getElementById('identifier').setAttribute('data-id',id);
            
            axios.post('/setClientTab',{
                'tab':tab,
            });
        });
    </script>
    <script>
        $(document).on('keyup click', '#search_list', function (e) {
            let value = $(e.currentTarget).val();
            let kind = document.getElementById('identifier').dataset.parent;
            let type = document.getElementById('identifier').dataset.id;

                $.ajax({
                    url: '{{ route('search_client') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "kind": kind,
                        "type": type,
                        "value": value,
                    },
                    success: data => {
                        if (type == 1){
                            $('#active_clients').html(data.view).show('slide', {direction: 'left'}, 400);
                        }
                        else if (type == 2){
                            $('#inactive_clients').html(data.view).show('slide', {direction: 'left'}, 400);
                        }
                        // $('.report_block').removeClass('disappear');
                    },
                    error: () => {
                        // $('.report_block').removeClass('disappear');
                    }
                })
        })
    </script>

@endpush
