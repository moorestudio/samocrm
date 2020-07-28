@extends('layouts.admin_app')
@section('content')

{{-- <div class="container pt-3">
	<h2 class="pb-3">{{ $user->franchise_id ? "Партнер" : 'Франчайзи' }}</h2>
	<p>Имя: {{$user->name}}</p>
	<p>Фамилия: {{$user->last_name}}</p>
	<p>E-mail: {{$user->email}}</p>
	<p>ИИН: {{$user->INN}}</p>
	<p>Статус: Франчайзи</p>Статус франчайзи (обычный франчайзи или франчайзи-организатор)
	<a href="{{url('storage/images/contracts/'.$user->contract)}}" download>Скачать контракт</a>
	<br>
	<img src="{{url('storage/images/contracts/'.$user->contract)}}">
	<br>
	<a href="{{ route('franchise_update',['id' => $user->id]) }}"><button class="btn btn-dark">Редактировать</button></a>
</div>


 --}}

<div class="container">
        <div class="row justify-content-center">
            <div class="col-12 pt-5">

                @if($user->role_id==2 and $user->confirmed_at==Null)
                    <div class="p-4" style="color:red;font-weight: bolder;">
                            Почта пользователя еще не была подтверждена!!!
                    </div>
                @endif
                    <div class="row">
                        <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12 p-1">
                            <div class="profile_main_info">
                                <div class="p-3 client_prof_card">
                                    <div class="d-flex align-items-center justify-content-center">
                                    <img class="mr-3" src="{{ asset('images/user_vec.png') }}" alt="">
                                    <span class="main-title font-weight-bold text-white">Личные данные</span>
                                    </div>
                                        <div class="d-flex mt-2 mb-2">
                                         <div>
                                            <img src="{{url('storage/avatars/'.$user->avatar)}}" style="width: 100px;height: 100px;object-fit: cover; border-radius: 50px;" alt="">
                                         </div>
                                         
                                         <div class="pt-3 mb-2 {{isset($user->avatar) ? 'ml-3' : ''}}">
                                            <p class="info-text mb-2 text-white">Статус:</p>
                                            <span class="px-4 py-1" style="border-radius: 10px;background-color:#17A555">
                                            {{$user->role->display_name}}
                                            </span>    
                                            
                                         </div>       
                                     </div>   

                                    <p class="info-text mb-0 text-white">Имя и Фамилия</p>
                                    <p class="font-weight-bold text-white" style="font-size: 16px;">{{ $user->name }} {{ $user->last_name }}</p>
                                    <p class="info-text mb-0 text-white">Город</p>
                                    <p class="font-weight-bold text-white" style="font-size: 16px;">{{ $user->city }}</p>
                                    <p class="info-text mb-0 text-white">E-mail адрес:</p>
                                    <p class="font-weight-bold text-white" style="font-size: 16px;">{{ $user->email }}</p>
                                    <p class="info-text mb-0 text-white">Номер телефона</p>
                                    <p class="font-weight-bold text-white" style="font-size: 16px;">{{ $user->contacts }}</p>
                                    <p class="info-text mb-0 text-white">ИИН</p>
                                    <p class="font-weight-bold text-white" style="font-size: 16px;">{{ $user->INN }}</p>
                                    @if(isset($user->contract))    
                                    <a href="{{url('storage/images/contracts/'.$user->contract)}}" style="color:white;" download>Скачать контракт</a>
                                    <span data-toggle="modal" data-target="#contract_view" style="cursor: pointer;">Просмотреть</span>
                                    @else
                                    У пользователя нет скана контракта
                                    @endif

                                    <div class="mb-4"></div>
                                    <a href="{{ route('franchise_update',['id' => $user->id]) }}">  
                                        <button class="d-block mt-2 pt-1 pb-1 pr-2 pl-2 border-0 w-6 rounded" style="text-transform: uppercase;background-color:#17A555;color:white;">
                                        <img class="mr-2" style="vertical-align: baseline;width: 10px;" src="{{ asset('images/user_vec.png') }}" alt="">
                                        <span>Редактировать</span>
                                        </button>
                                    </a>  
                                </div>
                                @if(isset($user->franchise_id))
                                <div class="p-3 mt-2 curator_prof_card">                               
                                    
                                        <?php
                                            $franchise = \App\User::find($user->franchise_id);
                                        ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <img class="mr-3" src="{{ asset('images/curator_vec.png') }}" alt="">
                                                <span class="main-title font-weight-bold ">Куратор</span>
                                            </div>
                                    {{--<img class="mr-2" style="float: left;" src="{{ asset('images/curator_vec.png') }}" alt="">    --}}
                                    {{--<p class="profile_info mb-0 font-weight-bold" style="font-size: 16px;">Ваш куратор</p>--}}
                                     <div class="d-flex mt-2 mb-2">
                                         <div>
                                            <img src="{{url('storage/avatars/'.$franchise->avatar)}}" style="width: 100px;height: 100px;object-fit: cover; border-radius: 50px;" alt="">
                                             
                                         </div>
                                     </div> 
                                    <p class="info-text mb-0">Имя и Фамилия</p>
                                    <p class="font-weight-bold" style="font-size: 16px; color: black;">{{ $franchise->name }} {{ $franchise->last_name }}</p>
                                    <p class="info-text mb-0">E-mail адрес:</p>
                                    <p class="font-weight-bold" style="font-size: 16px; color: black;">{{ $franchise->email }}</p>
                                    <p class="info-text mb-0">Номер телефона</p>
                                    <p class="font-weight-bold" style="font-size: 16px; color: black;">{{ $franchise->contacts }}</p>
                                </div>
                                @endif
                            </div>
                            </div>
                            <div class="profile_check d-none" data-id="{{$user->id}}"></div>
                            <div class="col-lg-9 col-md-7 col-sm-12 col-xs-12 p-3">


@if($user_links->count()>0)
                <div class="col-12 px-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border:none;">
                      <li class="nav-item mr-3 mb-lg-0 mb-3">
                       
                        <a class="nav-link active ref_prof_btn_ p-3" data-toggle="tab" href="#user_list_franch_pane" role="tab" aria-controls="user_list_franch_btn" aria-selected="true">
                        Реферальные ссылки</a>
                      </li>
                      <li class="nav-item mb-lg-0 mb-3">
                      <a class="nav-link ref_prof_btn_ p-3" data-toggle="tab" href="#user_list_partner_pane" role="tab" aria-controls="profile" aria-selected="false">
                      Рефералы</a>
                      </li>
                    </ul>
                </div>
                <div class="col-12">
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active py-3" id="user_list_franch_pane" role="tabpanel" aria-labelledby="home-tab">
							  <div class="row py-3 mb-1 sticky-top report-header">
								  <div class="col-lg-3 col-12">
									  <p class="mb-0 font-weight-bold">Мероприятие</p>
								  </div>
								  <div class="col-lg-8 col-12">
									  <p class="mb-0 font-weight-bold">Реферальная ссылка</p>
								  </div>
                      <div class="col-lg-1 col-12">
                          <p class="mb-0 font-weight-bold"><i class="fa fa-users" aria-hidden="true" data-toggle="tooltip" title="Количество переходов по реферальной ссылке"></i>
                            </p>
                      </div>
							  </div>
							  @foreach($user_links as $link)
							  <div class="row py-3 mb-1 report-card">
								  <div class="col-lg-3 col-12">
									  <p class="mb-0 of-elipsis">{{$link->event_title}}</p>
								  </div>
								  <div class="col-lg-8 col-12">
									  <p class="mb-0">{{$link->url_link}}</p>
								  </div>
                  <div class="col-lg-1 col-12">
                      <p class="mb-0" data-toggle="tooltip" title="Количество переходов по реферальной ссылке">{{$link->count}}</p>
                  </div>
							  </div>
							  @endforeach
                  </div>
                      <div class="tab-pane fade" id="user_list_partner_pane" role="tabpanel" aria-labelledby="profile-tab">
            					@if($referred_users->count()>0)
                          <div class="partner_user_show_list">
                            <table id="partner_table" class="table" style="border-collapse: separate;border-spacing: 0 15px;">
                            <thead>
                              <tr>
                                  <th style="background-color: #17A555;border-radius: 5px 0 0 0; /* top left, top right, bottom right, bottom left */" scope="col">ФИО</th>
                                  <th style="background-color: #17A555;" scope="col">Телефон</th>
                                  <th style="background-color: #17A555;" scope="col">Город</th>
                                  <th style="background-color: #17A555;border-radius: 0 5px 0 0;" scope="col">Откуда</th>
                              </tr>
                            </thead>
                            <tbody>
  		                @foreach($referred_users as $users)
            									<tr class="user_list_tb_row">
            									<td class="left_border_orange">{{ $users->fullname() }}</td>
            									<td>{{ $users->contacts}}</td>
            									<td>{{ $users->city}}</td>
            									<td>{{ $users->found}}</td>
            									</tr>				
      								@endforeach
                            </tbody>
                            </table>
                          </div>
            					@else
            						<p class="info-text my-3" style="font-size: 16px;">
            						У пользователя еще нет рефералов
            						</p>
            					@endif

                      </div>                    
                    </div>
                  </div>
        			@else
        				<h3>У пользователя нет ссылок к мероприятиям</h3>
        			@endif	

                            </div>

                    </div>
                </div>
        </div>
    </div>

<div class="modal" id="contract_view">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Договор</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" style="height: 80vh;">
        <iframe id="contract_pdf" style="display: none;" 
            src="{{url('storage/images/contracts/'.$user->contract)}}"
            frameBorder="0"
            scrolling="auto"
            height="100%"
            width="100%"
            max-width = "500px" 
            position= "relative"
            >
    
        </iframe>
        <div>
            <img src="{{url('storage/images/contracts/'.$user->contract)}}" style="width: 100%" alt="">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
</div>
    
@endsection
@push('scripts')
    <script>
        var contract_extension = '{{$user->contract}}';
        var contract_extension_arr = contract_extension.split('.');

        if(contract_extension_arr[contract_extension_arr.length-1] != 'pdf' ){
            $('#contract_img').show();
        }
        else{
            $('#contract_pdf').show();
        }
   </script>
@endpush        