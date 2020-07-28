@extends('layouts.admin_app')
@section('content')

<div class="container">
	<div class="row mt-4">
{{-- 		<div class="col-lg-3 mb-lg-0 mb-4">
			<p class="main-title font-weight-bold">Личные данные</p>
			<p class="info-text mb-0">ФИО</p>
			<p class="font-weight-bold" style="font-size: 16px;">{{$profile_data["user"]->last_name}} {{$profile_data["user"]->name}}</p>
			<p class="info-text mb-0">ИИН</p>
			<p class="font-weight-bold" style="font-size: 16px;">{{$profile_data["user"]->INN}}</p>
			<p class="info-text mb-0">Контакты</p>
			<p class="font-weight-bold" style="font-size: 16px;">{{$profile_data["user"]->contacts}}</p>
			<p class="info-text mb-0">E-mail</p>
			<p class="font-weight-bold" style="font-size: 16px;">{{$profile_data["user"]->email}}</p>
			<a href="{{ route('profile_edit',['id'=>$profile_data["user"]->id]) }}"><button class="select-button">Редактировать контакты/пароль</button></a>
		</div> --}}
    <div class="col-lg-12 px-0 mb-4">
			<p class="main-title text-uppercase text-lg-left text-center mb-4">Рефералы, продажа партнерства</p>
	    @isset($part_ref_link)
				<div class="d-flex flex-column">
	        	<p class="link-description">Ваша ссылка для продажи партнерства</p>
					 	<div class="d-flex justify-content-between partnerLinkContent"><a id="partnerLink" href="{{$part_ref_link->url_link}}">{{$part_ref_link->url_link}}</a><img src="{{asset('images/copy.svg')}}" alt="" class="c-pointer" onclick="copyToClipboard('#partnerLink')"></div>
					</div>
	    @else
      <button class="partner_reff_link_btn" style="font-size:13px; cursor:pointer;color:rgba(30,36,51,0.6);">Cгенерировать ссылку на продажу партнерства
      </button>
    @endisset
    <span id="part_ref_link_parent_span_id" style="display: none;">Ваша ссылка на продажу партнерства: <span id="part_ref_link_span_id" class="p-1" style="background-color:#294996;color: white;"></span></span>
    </div>
		<div class="col-lg-12 px-0">
			@if($profile_data["user_links"]->count()>0)
                <div class="col-12 px-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom:1px solid #E1E8F3;">
                      <li class="nav-item mb-lg-0 mb-3">

                        <a class="nav-link active user_list_btn_switch" data-toggle="tab" href="#user_list_franch_pane" role="tab" aria-controls="user_list_franch_btn" aria-selected="true">
													<img src="{{asset('images/link.svg')}}" alt="">
													Реферальные ссылки
												</a>
                      </li>
                      <li class="nav-item mb-lg-0 mb-3">
                      <a class="nav-link user_list_btn_switch" data-toggle="tab" href="#user_list_partner_pane" role="tab" aria-controls="profile" aria-selected="false">
												<img src="{{asset('images/users.svg')}}" alt="">
												Рефералы
											</a>
                      </li>
                    </ul>
                </div>
                <div class="col-12 px-0">
                    <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active py-3" id="user_list_franch_pane" role="tabpanel" aria-labelledby="home-tab">
                            {{--<div class="user_show_list">--}}
                              {{--<table id="franch_table" class="table" style="border-collapse: separate;border-spacing: 0 15px;">--}}
                              {{--<thead class="rounded-top" style="color: white;">--}}
                                {{--<tr >--}}
                                  {{--<th style="background-color: #17A555;border-radius: 5px 0 0 0;" scope="col">Мероприятие</th>--}}
                                  {{--<th style="background-color: #17A555;border-radius: 0 5px 0 0;" scope="col">Реферальная ссылка</th>--}}
                                {{--</tr>--}}
                              {{--</thead>--}}
                              {{--<tbody>--}}
								{{--@foreach($profile_data["user_links"] as $link)--}}
									{{--<tr class="user_list_tb_row">--}}
									    {{--<td class="left_border_orange">{{$link->event_title}}</td>--}}
									    {{--<td>{{$link->url_link}}</td>--}}
									{{--</tr>--}}
								{{--@endforeach--}}
                              {{--</tbody>--}}
                              {{--</table>--}}
							  <div id="profile_ref_list_row_001_id" class="d-flex report-header">
								  <div class="col-lg-3 col-12">
									  <p class="mb-0 title">Мероприятие</p>
								  </div>
								  <div class="col-lg-6 col-12">
									  <p class="mb-0 title">Реферальная ссылка</p>
								  </div>
                  <div class="col-lg-1 col-12">
                      <p class="mb-0 font-weight-bold text-white"><i class="fa fa-users" aria-hidden="true" data-toggle="tooltip" title="Количество переходов по реферальной ссылке"></i>
                        </p>
                  </div>
							  </div>
							  @forelse($profile_data["user_links"] as $link)
							  <div class="d-flex report-card special-card">
								  <div class="col-lg-3 col-12">
									  <p class="mb-0">{{$link->event_title}}</p>
								  </div>
								  <div class="col-lg-6 col-12">
									  <p class="mb-0">{{$link->url_link}}</p>
								  </div>
                  <div class="col-lg-1 col-12">
                      <p class="mb-0" data-toggle="tooltip" title="Количество переходов по реферальной ссылке">{{$link->count}}</p>
                  </div>
								   <div class="col-lg-2 col-12">
										 <a class="reff_link_btn_share" event_ref_link="{{$link->url_link}}">Поделиться <img  src="{{ asset('images/share.svg') }}"></a>
								  </div>
							  </div>
								@empty
									<div class="d-flex flex-column align-items-center my-5">
										<img src="{{asset('images/empty-link.svg')}}" alt="">
										<p class="info-text my-1" >
											Реферальные ссылки отсуствуют
										</p>
									</div>
							  @endforelse
              </div>
          <div class="tab-pane fade py-3" id="user_list_partner_pane" role="tabpanel" aria-labelledby="profile-tab">
							@if($profile_data["referred_users"]->count()>0)
									<div id="partner_table" class="d-flex report-header partner_user_show_list">
										<div class="col-lg-3 col-12">
										  <p class="mb-0 title">ФИО</p>
									  </div>
										<div class="col-lg-3 col-12">
										  <p class="mb-0 title">Телефон</p>
									  </div>
									  <div class="col-lg-3 col-12">
										  <p class="mb-0 title">Город</p>
									  </div>
	                  <div class="col-lg-3 col-12">
												<p class="mb-0 title">Пришел из</p>
	                  </div>
								  </div>
	                @foreach($profile_data["referred_users"] as $users)
										<div class="d-flex report-card special-card">
										  <div class="col-lg-3 col-12">
											  <p class="mb-0">{{$users->fullname()}}</p>
										  </div>
										  <div class="col-lg-3 col-12">
											  <p class="mb-0">{{$users->contacts}}</p>
										  </div>
		                  <div class="col-lg-3 col-12">
												<p class="mb-0">{{$users->city}}</p>
		                  </div>
		                  <div class="col-lg-3 col-12">
												<p class="mb-0">{{$users->city}}</p>
		                  </div>
									  </div>
									@endforeach
							@else
								<div class="d-flex flex-column align-items-center my-5">
									<img src="{{asset('images/referals.svg')}}" alt="">
									<p class="info-text my-1" >
										У вас еще нет рефералов
									</p>
								</div>
							@endif

            </div>
          </div>
        </div>
			@else
				<h3>У Вас нет ссылок к мероприятиям</h3>
			@endif
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="social_share_modal" tabindex="-1" role="dialog" aria-labelledby="social_share_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;" role="document">
    <div class="modal-content p-4">
        <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">X</span>
        </button>
        </div>

        <div>
            <div class="mb-2" style="margin-top: -40px;">
                <span style="font-size: 21px;text-transform:uppercase;font-weight: bold;">поделиться</span>
            </div>

            <div class="d-flex social_modal_icons_wrapper">
                <div class="m-3 text-center">
                    <a id="fb_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/fb_icon.png') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Facebook</div>
                </div>
                <div class="m-3 text-center">
                    <a id="vk_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/vk_icon.png') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">ВКонтакте</div>
                </div>
                <div class="m-1 mt-3 text-center">
                    <a id="ok_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/ok_icon.png') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Одноклассники</div>
                </div>
                <div class="m-3 text-center">
                    <a id="tw_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/tw_icon.png') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Твиттер</div>
                </div>
            </div>
            <div>
                <span style="font-weight: 600;font-size: 21px;">CСЫЛКА НА МЕРОПРИЯТИЕ</span>
            </div>
            <div class="p-2 mt-2 mb-3" style="border:1px solid #EDEDED;background-color: #FAFAFA;">
                <input type="text"  style="font-size: 10.5px;width: 75%;border: none!important;background: transparent;"  id="reff_to_copy" value="" readonly>
                <span id="copy_reff_btn" style="color: #065FD4;font-size: 16px;font-weight: bold;float: right;" onclick="copy_reff()">КОПИРОВАТЬ</span>
            </div>
        </div>


    </div>
  </div>
</div>

@push('scripts')
<script>
    function copy_reff() {
        var reff = document.getElementById("reff_to_copy");
        reff.select();
        reff.setSelectionRange(0, 99999)
        document.execCommand("copy");
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Скопировано!',
        });
    }
    $(".reff_link_btn_share").click(function() {
        $('#social_share_modal').modal('show');
        let event_ref_link = this.getAttribute('event_ref_link');
        let fb_link = "https://www.facebook.com/sharer/sharer.php?u="+event_ref_link;
        let vk_link = "http://vk.com/share.php?url="+event_ref_link;
        let ok_link = "https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&amp;st.shareUrl="+event_ref_link;
        let tw_link = "https://twitter.com/intent/tweet?url="+event_ref_link;
        document.getElementById("fb_share_link").setAttribute('href', fb_link);
        document.getElementById("vk_share_link").setAttribute('href', vk_link);
        document.getElementById("ok_share_link").setAttribute('href', ok_link);
        document.getElementById("tw_share_link").setAttribute('href', tw_link);
        document.getElementById("reff_to_copy").value = event_ref_link;
    })
		function copyToClipboard(element) {
      event.preventDefault();
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
			Swal.fire({
					position: 'center',
					icon: 'success',
					title: 'Скопировано!',
			});
    }
</script>
@endpush
@endsection
