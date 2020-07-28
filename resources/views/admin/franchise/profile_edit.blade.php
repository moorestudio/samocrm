@extends('layouts.admin_app')
@section('content')

<div class="container">
	<div class="row px-0 mb-4">
		<p class="main-title text-uppercase text-lg-left text-center mb-1">Редактирование профиля</p>
	</div>
	<div class="mt-2 row justify-content-center">
		<div class="col-lg-12 p-5 profile_edit_block_class_wrapper white-bg-round">
			<div class="row">
					<div class="col-3 posititon-relative" style="text-align: center;">
							<form id="profile_image_update" action="{{ route('profile_image_update',['id'=>$user->id])}}" method="POST" enctype="multipart/form-data">
								  @csrf
										@if($user->avatar=="users/default.png")
											<div class="d-flex justify-content-start">
												<img src="{{asset('images/default-user.svg')}}" alt="">
											</div>
										@elseif($user->avatar)
											<div style="width:67px; height:67px; border-radius:50px; background-image: url({{asset('storage/avatars/'.$user->avatar)}}); background-size: cover; background-position:center;">
											</div>
										@endif
							      <input style="display: none;" id="avatar_input_id" type="file"  name="avatar" class="form-control">
                    <button id="avatar_upload_btn" type="button" onclick="chooseAvatar();" class="img-take-btn border-0">
											<img src="{{asset('images/photo.svg')}}" alt="">
										</button>
				            @error('avatar')
		                    <span class="invalid-feedback" role="alert" style="display: block">
                            <strong style="color:white" id="avatar_error_message"></strong>
                            <script>
																document.getElementById("avatar_error_message").innerHTML='{{ $message }}'.replace("The avatar", "Аватар")
                            </script>
                        </span>
                		@enderror
                  </form>
					</div>
				<div class="col-12 form-style">
			<form  id="profile_edit_form" action="{{ route('profile_update',['id'=>$user->id])}}" method="POST" enctype="multipart/form-data">
				  @csrf
						<div class="row">
							<div class="col-lg-4 col-12">
								<label for="name">Имя</label>
								<input type="text" name="name" class="form-control  input-style grey-bg" value="{{ $user->name}}" placeholder="Имя" required>
							</div>
							<div class="col-lg-4 col-12">
								<label for="name">Фамилия</label>
								<input type="text" name="last_name" class="form-control  input-style grey-bg" value="{{ $user->last_name}}" placeholder="Фамилия" required>
							</div>
							<div class="col-lg-4 col-12">
								<label for="name">Отчество</label>
								<input type="text" name="middle_name" class="form-control  input-style grey-bg" value="{{ $user->middle_name}}" placeholder="Отчество" required>
							</div>
							<div class="col-lg-6 col-12">
								<label for="country">Страна</label>
								<input type="text" name="country" id="country" class="form-control  input-style grey-bg @error('country') is-invalid @enderror" value="{{ $user->country}}"  autocomplete="country" autofocus  required placeholder="Страна">
							</div>
							<div class="col-lg-6 col-12">
								<label for="name">Город</label>
								<input type="text" name="city" class="form-control  input-style grey-bg" value="{{ $user->city}}" placeholder="Город проживания" required>
							</div>
							<div class="col-lg-4 col-12">
								<label for="contacts">Номер телефона</label>
								<input type="tel" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 43' name="contacts" id="telephone" class="form-control  input-style grey-bg" value="{{ $user->contacts}}" placeholder="Например: +996 500 000 000" required>
								@error('contacts')
									<span class="invalid-feedback" role="alert" style="display: block;">
										<strong style="color: #1430f6;" id="contacts_reg_error_message"></strong>
											<script>
											document.getElementById("contacts_reg_error_message").innerHTML+='{{ $message }}'.replace("contacts", "# телефона")
											</script>
									</span>
								@enderror
					    	</div>
							<div class="col-lg-4 col-12">
								<label for="job">Должность *</label>
								<input type="text" name="job" id="job" class="form-control input-style grey-bg @error('job') is-invalid @enderror" value="{{ $user->job}}"  autocomplete="job" autofocus placeholder="Должность">
								@error('job')
								<span class="invalid-feedback" role="alert">
									<strong>это поле обязательно для заполнения.</strong>
								</span>
								@enderror
					  		</div>
							<div class="col-lg-4 col-12">
								<label for="work_type">Деятельность</label>
								<select class="browser-default custom-select  input-style grey-bg" name="work_type" id="work_type">
									<option selected>Деятельность</option>
									<option value="Строительство" {{ $user->work_type == "Строительство" ? 'selected' : '' }}>Строительство</option>
									<option value="Бизнес" {{ $user->work_type == "Бизнес" ? 'selected' : '' }}>Бизнес</option>
									<option value="Маркетинг" {{ $user->work_type == "Маркетинг" ? 'selected' : '' }}>Маркетинг</option>
									<option value="IT сфера" {{ $user->work_type == "IT сфера" ? 'selected' : '' }}>IT сфера</option>
									<option value="Хозяйственная отрасль" {{ $user->work_type == "Хозяйственная отрасль" ? 'selected' : '' }}>Хозяйственная отрасль</option>
									<option value="Сфера услуг" {{ $user->work_type == "Хозяйственная отрасль" ? 'selected' : '' }}>Сфера услуг</option>
									<option value="Творческая деятельность" {{$user->work_type == "Творческая деятельность" ? 'selected' : '' }}>Творческая деятельность</option>
									<option value="СМИ" {{ $user->work_type == "СМИ" ? 'selected' : '' }}>СМИ</option>
									<option value="6" {{ $user->work_type == "6" ? 'selected' : '' }}>Другое (ввести)</option>
								</select>
					    	</div>
							<div id="other_work_type_form" class="col-lg-4 col-12" style="display: none;">
								<label for="other_work_type">Введите Вашу деятельность</label>
								<input type="text" name="other_work_type" id="other_work_type" class="form-control  input-style grey-bg @error('other_work_type') is-invalid @enderror" value="{{ $user->work_type }}"  autocomplete="other_work_type" autofocus  placeholder="Деятельность">
								@error('other_work_type')
								<span class="invalid-feedback" role="alert">
										<strong>это поле обязательно для заполнения.</strong>
								</span>
								@enderror
							</div>
							<div class="col-lg-4 col-12">
								<label for="email">Email</label>
								<input type="text" name="email" class="form-control input-style grey-bg @error('email') is-invalid @enderror" value="{{ $user->email}}" required placeholder="Email адрес">
									@error('email')
										<span class="invalid-feedback" role="alert">
											 <strong style="color: #1430f6;">{{ $message }}</strong>
										</span>
								@enderror
							</div>
							<div class="col-lg-4 col-12">
								<label for="contacts">Пароль</label>
								<input type="text" name="password" class="form-control input-style grey-bg" value="" placeholder="Оставить пустым если без изменений" >
							</div>
							<div class="col-12 d-flex justify-content-start"  style="margin-top:50px;">
								<button id="profile_update_submit" form="profile_edit_form" type="submit" class="btn btn-success font-weight-bold ml-0">
								Сохранить
								</button>
								<a href="/profile" class="btn btn-cancel li-btn">
									Отмена
								</a>
							</div>
						</div>
					 </form>
					</div>

			</div>

		</div>
	</div>
</div>



@push('scripts')
<script>

		let work_types = ['Строительство','Бизнес','Маркетинг','IT сфера','Хозяйственная отрасль','Хозяйственная отрасль','Творческая деятельность','СМИ'];

		let current_user_work_type = "{{$user->work_type}}";

		if(!work_types.includes(current_user_work_type)){
			document.getElementById('other_work_type_form').style.display = "block";
			document.getElementById("work_type").selectedIndex = "9";
		}

	    document.getElementById('work_type').addEventListener('change', function() {
	    if (this.value == 6){
	        document.getElementById('other_work_type_form').style.display = "block"
	    }
	    else{
	        document.getElementById('other_work_type_form').style.display = "none"
	    }
	    });
	    if (document.getElementById("work_type").value == 6){
	        document.getElementById('other_work_type_form').style.display = "block"
	    }
	    else{
	        document.getElementById('other_work_type_form').style.display = "none"
	    }



   function chooseAvatar() {
      $("#avatar_input_id").click();
      $('#avatar_input_id').change(function() {
	  	document.getElementById("profile_image_update").submit();
		})

   }
</script>
@endpush
@endsection
