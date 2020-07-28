@extends('layouts.app')
@section('content')


@push('scripts')

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
   function chooseFile() {
      $("#contract_scan_id").click();
   }
	$('#contract_scan_id').change(function() {
	  $('#img_title').text(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
	})
</script>
@endpush

@endsection
