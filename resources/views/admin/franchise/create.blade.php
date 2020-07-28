@extends('layouts.admin_app')
@section('content')


<div class="container">
@if(isset($users_data['user']))
<h1 class="font-weight-bold" style="font-size: 30px;text-transform: uppercase;color: #0055A7">Редакирование пользователя</h1>
@else
<h1 class="font-weight-bold" style="font-size: 30px;text-transform: uppercase;color: #0055A7">Создание пользователя</h1>
@endif
	<form id="franchise_form" action="{{ route('franchise_store',['id' => isset($users_data['user']) ? $users_data['user']->id : '' ])}}" method="POST" enctype="multipart/form-data">
	  @csrf
	  <div class="row p-2 m-0" style="background-color: white;border-radius: 5px;">

@if(isset($users_data['user']))
	<div class="col-12">
	<h5 style="text-transform: uppercase;color: #0055A7">Роль: {{$users_data['user_type']}}</h5>
	@if(isset($users_data['partner_belongs_to']))
		<h5 style="text-transform: uppercase;color: #0055A7">Закреплен за: {{$users_data['partner_belongs_to']}}</h5>
	@endif
	</div>

  	<div class="col">
	  <div id="franchise_or_partner">
	    <label class="radio-inline mr-3" style="font-size: 20px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="franchise" {{ $users_data['user_type'] == 'Franchise' ? 'checked' : '' }}>Франчайзи
	    </label>
	    <label class="radio-inline mr-3" style="font-size: 20px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="partner" {{ $users_data['user_type'] == 'Partner' ? 'checked' : '' }}>Партнер
	    </label>
	    <label class="radio-inline mr-3" style="font-size: 20px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="sales" {{ $users_data['user_type'] == 'Sales' ? 'checked' : '' }}>Продавцы само
	    </label>
	  </div>
  	</div>


@else
  	<div class="col-4  px-0">
	  <div id="franchise_or_partner" class="mt-2">
	    <label class="radio-inline mr-3" style="font-size: 15px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="franchise" {{ old('user_type') == 'franchise' ? 'checked' : '' }} >Франчайзи
	    </label>
	    <label class="radio-inline mr-3" style="font-size: 15px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="partner" {{ old('user_type') == 'partner' ? 'checked' : '' }}>Партнер
	    </label>
	    <label class="radio-inline mr-3" style="font-size: 15px;text-transform: uppercase;color: #0055A7">
	      <input class="mr-2" type="radio" name="user_type" value="sales" {{ old('user_type') == 'sales' ? 'checked' : '' }}>Продавцы само
	    </label>
	  </div>
  	</div>
@endif

	  	<div class="col-4" id="franchise_input" style="display: none;">
			<select id="franchise_select" name="franchise_selection_choice" class="user_create_select form-control2 border border-0">
				<option value="" disabled selected>За кем будет закеплен партнер?</option>
				<option value="SAMO">Офис Само</option>
				<option value="Franch">Франчайзи</option>
			</select>

	  	</div>

		<div class="col-4" id="choose_franch" style="display: none;">
			<span>
				<input id="chosen_franch_input" data-toggle="modal" placeholder="Выбрать франчайзи" data-target="#select_franchise_modal" type="text" class="form-control border border-0 user_create_select" value="" name="selected_franchise_input" readonly>
				<input id="chosen_franch_input_id" type="hidden" name="franchise" value="">
			</span>
		</div>
	  </div>

	  <div class="create_user_inputs_block p-4 mt-2">
	  <div class="row">
	    <div class="col">
	     <label for="name">Имя</label>
	      <input type="text" name="name" placeholder="Имя пользователя" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->name : old('name') }}" required>
	    </div>
	    <div class="col">
		<label for="last_name">Фамилия</label>
	      <input type="text" name="last_name" placeholder="Фамилия пользователя" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->last_name : old('last_name') }}" required>
	    </div>
	    <div class="col">
	    	<label for="middle_name">Отчество</label>
	      <input type="text" name="middle_name" placeholder="Отчество пользователя" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->middle_name : old('middle_name') }}" required>
	    </div>


	  </div>
	  <br>
	  <div class="row">
		  <div class="col">
			  <label for="country">Страна</label>
			  <input type="text" name="country" class="form-control form-control2 create_user_inputs" id="country" value="{{ isset($users_data['user']) ? $users_data['user']->country : old('country') }}" required>
		  </div>
	    <div class="col">
	    	<label for="city">Город</label>
	      <input type="text" name="city" placeholder="Название города" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->city : old('city') }}" required>
	    </div>
	    <div class="col">
	    	<label for="telephone">Номер телефона</label>
	      <input type="tel" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 43' name="contacts" id="telephone" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->contacts : old('contacts') }}" placeholder="+996 500 000 000" required>
		</div>
		<div class="col">
		  <label for="email">Email</label>
		  <input type="text" name="email" placeholder="Email адрес" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->email : old('email') }}"  required>
		</div>

	  </div>
	  <div class="row mt-4" id="contracts_dates_scan">

	    <div class="col"  id="contract_scan_date_id_col">
		<label for="contract_scan_date_id">Дата заключения контракта</label>
	    <input id="contract_scan_date_id" type="date" value="{{ isset($users_data['user']) ? date('Y-m-d', strtotime($users_data['user']->contract_date)) : (old('date') ? old('date', date('Y-m-d')) : '') }}"  name="date" class="form-control form-control2 create_user_inputs">
	    </div>
		<div class="col"  id="contract_scan_date_end_col">
		  <label for="contract_scan_date_end">Дата завершение контракта</label>
		  <input id="contract_scan_date_end" type="date"  value="{{ isset($users_data['user']) ? date('Y-m-d', strtotime($users_data['user']->contract_date_end)) : (old('date') ? old('date', date('Y-m-d')) : '') }}"  name="date_end" class="form-control form-control2 create_user_inputs">

		</div>

		<div class="col-4">
			<label>Скан контракта</label>
			<input style="display: none;" id="contract_scan_id" type="file"  name="contract" value="{{ isset($users_data['user']) ? $users_data['user']->contract : old('contract') }}" class="form-control">
			<div class="d-flex create_user_inputs" style="height: 40px;border-radius: 5px;">
				<!-- <img style="margin-left: 3%;" src= "{{ asset('images/profile_scan.svg') }}" alt=""> -->
				<span id="img_title" style="display: inline-flex;width: 100%;align-items: center;padding-left: 2%;" class="of-elipsis">Загрузить скан контракта</span>
				<button id="img_upload_btn" style="border: none;background-color: transparent;" type="button" onclick="chooseFile();"><img src="{{ asset('images/user_create/user_create_up.png') }}" alt=""></button>
			</div>
		</div>


	  </div>
		<br>
	<div class="row">
		<div class="col-4">
			<label>ИИН</label>
			<input type="text" name="inn" placeholder="ИИН длиной 12 - 14 символов" class="form-control form-control2 create_user_inputs" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ isset($users_data['user']) ? $users_data['user']->INN : old('inn') }}" required>
		</div>

    <div class="col-2">
      <label>% за продукт</label>
      <input type="number" name="franch_percent" placeholder="Бонус в %" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control form-control2 create_user_inputs" id="franch_percent" placeholder="%" value="{{ isset($users_data['user']) ? $users_data['user']->percent : old('franch_percent') }}">
    </div>

    <div class="col-2" id="partner_percent_row">
    	<label>% с дохода партнеров</label>
    	<input type="number" name="percent_from_partner" placeholder="Бонус в %" id="percent_from_partner" placeholder="%" class="form-control form-control2 create_user_inputs" value="{{ isset($users_data['user']) ? $users_data['user']->percent_from_partner : old('percent_from_partner') }}">
	</div>
	<div class="col-2" id="amo_id_row">
    	<label>ID пользователя AMOCrm</label>
    	<input type="number" name="amo_id" placeholder="ID пользователя" id="amo_id" class="form-control form-control2" value="{{ isset($users_data['user']) ? $users_data['user']->amo_id : old('amo_id') }}">
    </div>


	@isset($users_data['user'])

		<div class="col-4">
			<label>Пароль</label>
			<input type="text" name="password" class="form-control input-style grey-bg" value="" placeholder="Оставить пустым если без изменений" >
		</div>
	@endisset

		</div>
	  <br>
	  <div class="row">
	  <div class="col-lg-8">
	  	<textarea class="form-control form-control2 create_user_inputs" name="comments"  rows="4" placeholder="Поле для ввода комментария">{{ isset($users_data['user']) ? $users_data['user']->comments : old('comments')}}</textarea>
	  </div>
	  </div>
	  <div class="row">
	  	<div class="col p-2">
	  		<button form="franchise_form" type="submit" class="btn select-button">
			@if(isset($users_data['user']))
			Завершить редакирование
			@else
			Завершить и отправить письмо
			@endif
			</button>
			<a href="{{route('user_list')}}" class="btn select-button select-button-grey" style="width: 235px;">
				Отмена
			</a>
	  	</div>
	  </div>

	</div>

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


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="select_franchise_modal" tabindex="-1" role="dialog" aria-labelledby="select_franchise_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content create_user_modal_cont p-2">

          {{-- <div class="modal-header pl-0 pr-0 pt-0 pb-4"  style="border: none;"> --}}
            {{-- <h5 class="modal-title p-3" id="exampleModalLongTitle">Выберите Франчайзи</h5> --}}
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> --}}
              {{-- <span aria-hidden="true">X</span> --}}
            {{-- </button> --}}
          {{-- </div> --}}

          <div class="modal-body select_franchise_modal_body p-3" style="max-height: 300px;overflow: auto;">

	<div class="row">
		<div class="col-3" style="font-weight: 18px;font-weight: 600;">Выберите Франчайзи</div>
		<div class="col-3"></div>
		<div class="col-3"></div>
		<div class="col-3">
			<div class="form-group" style="position: relative;">
				<img id="user_create_search_icon" src="{{ asset('images/user_create/user_create_search.png') }}" alt="">
				<input type="text" class="form-control form-control2 create_user_inputs" placeholder="      Поиск по странам" id="search" name="search">
			</div>	
		</div>
	</div>

	  <div class="row">
	  	<div class="col">
			<div>
				<div class="row user_create_modal_head">
					<div class="col-4">ФИО</div>
					<div class="col-2">Страна</div>
					<div class="col-1">Город</div>
					<div class="col-2">Дата договора</div>
					<div class="col-3">Дата истечения договора</div>
				</div>

				<div id="tbody_franch">
					@foreach($users_data['franchise_all'] as $franchise)
		  				<div class="row create_user_inputs my-2 p-2" onclick="get_franch(this)" class="franch_row_class" franch_id="{{$franchise->id}}" franch_name="{{$franchise->fullName()}}">
		  					<div class="col-4">{{$franchise->fullName()}}</div>
		  					<div class="col-2">{{$franchise->country}}</div>
		  					<div class="col-1">{{$franchise->city}}</div>
		  					<div class="col-2">{{date('d-m-Y', strtotime($franchise->contract_date))}}</div>
		  					<div class="col-3">{{date('d-m-Y', strtotime($franchise->contract_date_end))}}</div>

		  				</div>
					@endforeach
				</div>
			
			</div>
	




	  	</div>
	  </div>

          </div>


    </div>
  </div>
</div>

@push('scripts')

@if(isset($users_data['user']))
<script type="text/javascript">
  	@if($users_data['user_type'] == 'Franchise')
  		$("#partner_percent_row").show();
  		$("#franchise_input").hide();
  	@elseif($users_data['user_type'] == 'Sales')
  		$("#partner_percent_row").hide();
  		$("#contracts_dates_scan").hide();
  	@elseif($users_data['user_type'] == 'Partner')
  		@if($users_data['partner_belongs_to'] == 'Samo Office')
  			$("#franchise_input").show();
	        $("#partner_percent_row").hide();
	        document.getElementById("franchise_select").selectedIndex = "1";
  		@else
			$("#franchise_input").show();
	        $("#partner_percent_row").hide();
	        document.getElementById("franchise_select").selectedIndex = "2";
	        $("#choose_franch").show();
			document.getElementById("chosen_franch_input").value = "{{$users_data['partner_belongs_to']}}";
			document.getElementById("chosen_franch_input_id").value = "{{$users_data['partners_franch_id']}}";
  		@endif


  	@endif
</script>
@else
<script type="text/javascript">

	@if(old('user_type') == 'franchise')
		$("#partner_percent_row").show();
  		$("#franchise_input").hide();
  	@elseif(old('user_type') == 'sales')
  		$("#partner_percent_row").hide();
  		$("#contracts_dates_scan").hide();

	@elseif(old('user_type') == 'partner')
		@if(old('franchise_selection_choice') == 'SAMO')
			$("#franchise_input").show();
	        $("#partner_percent_row").hide();
	        document.getElementById("franchise_select").selectedIndex = "1";
		@elseif(old('franchise_selection_choice') == 'Franch')
	        $("#franchise_input").show();
	        $("#partner_percent_row").hide();
	        document.getElementById("franchise_select").selectedIndex = "2";
	        $("#choose_franch").show();
	        document.getElementById("chosen_franch_input").value = "{{old('selected_franchise_input')}}";
			document.getElementById("chosen_franch_input_id").value = "{{old('franchise')}}";
		@endif


	@endif


</script>
@endif



<script type="text/javascript">
	$('#search').on('keyup',function(){
	$value=$(this).val();
	$.ajax({
	type : 'get',
	url : '{{URL::to('search_franch')}}',
	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	data:{'search':$value},
	success:function(data){
	$('#tbody_franch').html(data);
	}
	});
	})
	function get_franch(row) {
         document.getElementById("chosen_franch_input").value = row.getAttribute('franch_name');
         document.getElementById("chosen_franch_input_id").value = row.getAttribute('franch_id');

        $('#select_franchise_modal').modal('hide');

    };


	$('#franchise_select').change(function(){
	    let who = $(this).val();
	    if (who == 'SAMO'){
            // $("#franchise_input").show();
            $("#choose_franch").hide();
        }
        else{
            $("#choose_franch").show();

        }
	})
</script>






@if ($errors->any())
 <script>
 	let error_text='';
		@error('contacts')
			error_text+="<li>"+"{{ $message }}".replace("contacts", "# телефона")+"</li>";
        @enderror
        @error('inn')
			error_text+="<li>"+"{{ $message }}".replace("inn", "# ИНН")+"</li>";
        @enderror
        @error('contract')
			error_text+="<li>"+"{{ $message }}".replace("contract", "Скан контракта")+"</li>";
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
   function chooseFile() {
      $("#contract_scan_id").click();
   }
	$('#contract_scan_id').change(function() {
	  $('#img_title').text(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
	})
</script>
@endpush

@endsection
