@extends('layouts.app')
@section('content')


<div class="container">
	  <h1 class="font-weight-bold" style="font-size: 30px;text-transform: uppercase;color: #0055A7">Анкета для партнера</h1>
	  <hr>
	  <form id="franchise_form" action="{{ route('partner_new_store')}}" method="POST" enctype="multipart/form-data">
	  @csrf
		<input type="hidden" name="pay_type" id="pay_type">
	  <div class="row">
	    <div class="col">
	      <label for="name">Имя</label>
	      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
	    </div>
	    <div class="col">
			  <label for="last_name">Фамилия</label>
	      <input type="text" name="last_name"class="form-control" value="{{ old('last_name') }}" required>
	    </div>
	    <div class="col">
	    	<label for="middle_name">Отчество</label>
	      <input type="text" name="middle_name"class="form-control" value="{{ old('middle_name') }}" required>
	    </div>
	  </div>
	  <br>
	  <div class="row">
		  <div class="col">
			  <label for="country">Страна</label>
			  <input type="text" name="country" class="form-control" id="country" value="{{ old('country') }}" required>
		  </div>
	    <div class="col">
	    	<label for="city">Город</label>
	      <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
	    </div>
	    <div class="col">
	    	<label for="telephone">Номер телефона</label>
	      <input type="tel" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 43' name="contacts" id="telephone" class="form-control" value="{{ old('contacts') }}" placeholder="Например: +996 500 000 000" required>
		</div>
	  </div>
	  <br>
	  <div class="row">
		  <div class="col">
			  <label for="email">email</label>
			  <input type="text" name="email" class="form-control" value="{{ old('email') }}"  required>
		  </div>
		  <div class="col">
				<label>ИИН</label>
				<input type="text" name="inn" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{old('inn') }}" required>
		  </div>
<!-- 	    <div class="col">
		<label for="contract_scan_date_id">Дата заключения контракта</label>
	    <input id="contract_scan_date_id" type="date"  value="{{ old('date', date('Y-m-d')) }}" name="date" class="form-control">
	    </div>
		  <div class="col">
			  <label for="contract_scan_date_end">Дата завершение контракта</label>
			  <input id="contract_scan_date_end" type="date"  value="{{  old('date_end', date('Y-m-d')) }}" name="date_end" class="form-control">

		  </div> -->
	  </div>
		<!-- <br> -->
		<!-- <div class="row"> -->
<!-- 			<div class="col-4">
				<label>Скан контракта</label>
				<input style="display: none;" id="contract_scan_id" type="file"  name="contract" value="{{ old('contract') }}" class="form-control">
				<div class="d-flex" style="height: 37px;border:1px solid #ced4da; border-radius: 4px;background-color: white;">
					<img style="margin-left: 3%;" src="{{ asset('images/profile_scan.svg') }}" alt="">
					<span id="img_title" style="display: inline-flex;width: 100%;align-items: center;padding-left: 2%;"></span>
					<button id="img_upload_btn"style="border: none;border-left: 1px solid #ced4da;margin-right: 3%;background-color: transparent;" type="button" onclick="chooseFile();"><img src="{{ asset('images/profile_upload.png') }}" alt=""></button>
				</div>
			</div> -->

<!-- 			<div class="col-4">
				<label>ИИН</label>
				<input type="text" name="inn" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{old('inn') }}" required>
			</div>
		</div> -->
	  <br>
	  <div class="row">
<!-- 	  			<div class="col-lg-4">
			  <div class="form-group row">
    <label for="franch_percent" class="col col-form-label col-form-label-sm">Бонус за продукт</label>
    <div class="col">
      <input type="number" name="franch_percent" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control w-50" id="franch_percent" placeholder="%" value="{{ isset($users_data['user']) ? $users_data['user']->percent : old('franch_percent') }}">
    </div>
  </div>
    <div class="form-group row" id="partner_percent_row">
    <label for="percent_from_partner" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="col col-form-label col-form-label-sm">Бонус с дохода партнеров</label>
    <div class="col">
    	<input type="number" name="percent_from_partner" id="percent_from_partner" placeholder="%" class="form-control w-50" value="{{ isset($users_data['user']) ? $users_data['user']->percent_from_partner : old('percent_from_partner') }}">
    </div>
  </div>


		</div> -->
	  <div class="col-lg-8">
	  	<textarea class="form-control" name="comments"  rows="4" placeholder="Комментарий">{{  old('comments')}}</textarea>
	  </div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-12 my-4">
						<div class="p-3 d-flex justify-content-between" style="background-color: #F5F5F5;">
								<span>итого к оплате:</span><span class="font-weight-bold" style="font-size: 18px;">{{$user->partner_sell_price}} руб</span>
						</div>
				</div>
				<div class="col-lg-4 col-6">
						<div class="p-3 text-center" style="background-color: #FAFAFA;">
								<p>
										Карточка
								</p>
								<img class="img-fluid" src="{{ asset('images/pay_box.svg') }}" alt="">
								<div class="custom-checkbox custom-control pt-3 text-center">
										<input type="checkbox" class="custom-control-input pay_type paybox" name="paybox" id="paybox">
										<label class="custom-control-label" for="paybox"></label>
								</div>
						</div>
				</div>
				<div class="col-lg-4 col-6">
						<div class="p-3 text-center" style="background-color: #fafafa;">
								<p>
										Яндекс деньги
								</p>
								<img class="img-fluid" src="{{ asset('images/yandex.png') }}" alt="">
								<div class="custom-checkbox custom-control pt-3 text-center">
										<input type="checkbox" class="custom-control-input pay_type yandex" name="yandex" id="yandex">
										<label class="custom-control-label" for="yandex"></label>
								</div>
						</div>
				</div>
			  <div class="col-lg-4 col-6">
						<div class="p-3 text-center" style="background-color: #fafafa;">
								<p>
										Наличные
								</p>
								<img class="img-fluid" src="{{ asset('images/cash.png') }}" alt="">
								<div class="custom-checkbox custom-control pt-3 text-center">
										<input type="checkbox" class="custom-control-input pay_type cash" name="cash" id="cash">
										<label class="custom-control-label" for="cash"></label>
								</div>
						</div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="row">
	  	<div class="col p-2">
	  		<button form="franchise_form" type="submit" class="btn buy agree_block" style="background-color:#0055A7;color:white;">
			Завершить и оплатить.
			</button>
	  	</div>
	  </div>

<!-- 	  <div class="row">
	  	<div class="col p-2">
	  		<button form="franchise_form" class="btn" style="background-color:#0055A7;color:white;">
			Оплатить
			</button>
	  	</div>

	  	<div class="col p-2">
	  		<span>Сумма к оплате за партнерство</span>
	  	</div>

	  </div> -->

	</form>

</div>



<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ошибки заполнения</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="error_modal_body">

{{--            	@foreach ($errors->all() as $error)
                <li>{{ $error }} </li>
            @endforeach
        	  --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>







@push('scripts')

@if ($errors->any())
 <script>
 	let error_text='';
		@error('contacts')
			error_text+="<li>"+"{{ $message }}".replace("contacts", "# телефона")+"</li>";
        @enderror
        @error('inn')
			error_text+="<li>"+"{{ $message }}".replace("inn", "# ИИН")+"</li>";
        @enderror
        @error('contract')
			error_text+="<li>"+"{{ $message }}".replace("contract", "# Скан контракта")+"</li>";
        @enderror
        @error('email')
			error_text+="<li>"+"{{ $message }}"+"</li>";
        @enderror
    	@error('franchise')
			error_text+="<li>"+"{{ $message }}".replace("franchise", "Франчайзи для партнера")+"</li>";
        @enderror
        @error('user_type')
			error_text+="<li>"+"{{ $message }}".replace("user type", "Роль пользователя")+"</li>";
        @enderror
    document.getElementById("error_modal_body").innerHTML +=error_text
 	$('#errorModal').modal('show');



 </script>
@endif
<script>
	$('.pay_type').change(function (e) {
			var btn = $(e.currentTarget);
			$('.pay_type').prop("checked", false);
			$(this).prop("checked", true);
			if($('.buy').hasClass('agree_block')){
				$('.buy').removeClass('agree_block');
			}
			if (btn.hasClass('paybox'))
			{
					$('#pay_type').val('paybox');
			}
			else if(btn.hasClass('yandex'))
			{
					$('#pay_type').val('yandex');
			}
			else
			{
					$('#pay_type').val('cash');
			}
	});
</script>
<script>
   function chooseFile() {
      $("#contract_scan_id").click();
   }
	$('#contract_scan_id').change(function() {
	  $('#img_title').text(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
	})
</script>
@endpush

@endsection
