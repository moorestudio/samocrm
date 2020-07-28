<div class="col-12 d-flex flex-wrap form-style" id="new_user_form">
  <div class="col-12">
    <p class="profile-info font-weight-bold">
      Анкета нового пользователя
    </p>
  </div>
  <div class="col-5">
    <div class="">
      <label for="name">Имя</label>
      <input type="text" name="name" id="name" class="form-control input-style grey-bg @error('name') is-invalid @enderror" value="{{ old('name') }}"  autocomplete="name" autofocus placeholder="Имя пользователя">
      @error('name')
      <span class="invalid-feedback" role="alert">
          <strong>это поле обязательно для заполнения.</strong>
      </span>
      @enderror
    </div>
    <div>
      <label for="last_name">Фамилия</label>
      <input type="text" name="last_name" id="last_name" class="form-control input-style grey-bg @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"  autocomplete="last_name" autofocus placeholder="Фамилия пользователя">
      @error('last_name')
      <span class="invalid-feedback" role="alert">
              <strong>это поле обязательно для заполнения.</strong>
          </span>
      @enderror
    </div>
    <div>
      <label for="middle_name">Отчество</label>
      <input type="text" name="middle_name" id="middle_name" class="form-control input-style grey-bg @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') }}"  autocomplete="middle_name" autofocus placeholder="Отчество пользователя">
      @error('middle_name')
      <span class="invalid-feedback" role="alert">
              <strong>это поле обязательно для заполнения.</strong>
          </span>
      @enderror
    </div>
    <div>
        <label for="city">Город</label>
        <input type="text" name="city" id="city" class="form-control input-style grey-bg @error('city') is-invalid @enderror" value="{{ old('city') }}"  autocomplete="city" autofocus placeholder="Город">
        @error('city')
        <span class="invalid-feedback" role="alert">
            <strong>это поле обязательно для заполнения.</strong>
        </span>
        @enderror
    </div>
    <div>
        <label for="country">Страна</label>
        <input type="text" name="country" id="country" class="form-control input-style grey-bg @error('country') is-invalid @enderror" value="{{ old('country') }}"  autocomplete="country" autofocus>
        @error('country')
        <span class="invalid-feedback" role="alert">
            <strong>это поле обязательно для заполнения.</strong>
        </span>
        @enderror
    </div>
  </div>
  <div class="col-5">

  <div>
      <label for="email">Email</label>
      <input type="text" name="email" id="email" class="form-control input-style grey-bg @error('email') is-invalid @enderror" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Email пользователя">
      @error('email')
      <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div>
    <label for="contacts">Номер телефона</label>
    <input type="text" name="contacts" id="telephone" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 43' class="tel_input form-control  input-style grey-bg @error('contacts') is-invalid @enderror" value="{{ old('contacts') }}"  autocomplete="contacts" placeholder="Например: +996 500 000 000" autofocus >
    @error('contacts')
    <span class="invalid-feedback" role="alert" style="display: block;">
            <strong id="contacts_reg_error_message"></strong>
            <script>
              document.getElementById("contacts_reg_error_message").innerHTML+='{{ $message }}'.replace("contacts", "# телефона")
            </script>
        </span>
    @enderror
  </div>
      <div>
          <label for="job">Должность</label>
          <input type="text" name="job" id="job" class="form-control input-style grey-bg @error('job') is-invalid @enderror" value="{{ old('job') }}"  autocomplete="job" autofocus  placeholder="Ваша должность">
          @error('job')
          <span class="invalid-feedback" role="alert">
              <strong>это поле обязательно для заполнения.</strong>
          </span>
          @enderror
      </div>
      <div >
          <label for="company">Компания</label>
          <input type="text" name="company" id="company" class="form-control input-style grey-bg @error('company') is-invalid @enderror" value="{{ old('company') }}"  autocomplete="company" autofocus placeholder="Название вашей компании">
          @error('company')
          <span class="invalid-feedback" role="alert">
              <strong>это поле обязательно для заполнения.</strong>
          </span>
          @enderror
      </div>
      <div>
        <label for="work_type">Деятельность</label>
        <select class="browser-default custom-select input-style grey-bg" name="work_type" id="work_type">
            <option selected>Выбрать деятельность</option>
            <option value="Строительство" {{ old('work_type') == "Строительство" ? 'selected' : '' }}>Строительство</option>
            <option value="Бизнес" {{ old('work_type') == "Бизнес" ? 'selected' : '' }}>Бизнес</option>
            <option value="Маркетинг" {{ old('work_type') == "Маркетинг" ? 'selected' : '' }}>Маркетинг</option>
            <option value="IT сфера" {{ old('work_type') == "IT сфера" ? 'selected' : '' }}>IT сфера</option>
            <option value="Хозяйственная отрасль" {{ old('work_type') == "Хозяйственная отрасль" ? 'selected' : '' }}>Хозяйственная отрасль</option>
            <option value="Сфера услуг" {{ old('work_type') == "Хозяйственная отрасль" ? 'selected' : '' }}>Сфера услуг</option>
            <option value="Творческая деятельность" {{ old('work_type') == "Творческая деятельность" ? 'selected' : '' }}>Творческая деятельность</option>
            <option value="СМИ" {{ old('work_type') == "СМИ" ? 'selected' : '' }}>СМИ</option>
            <option value="6" {{ old('work_type') == "6" ? 'selected' : '' }}>Другое (ввести)</option>
        </select>
      </div>
      <div id="other_work_type_form"  style="display: none;">
          <label for="other_work_type">Введите Вашу деятельность</label>
          <input type="text" name="other_work_type" id="other_work_type" class="form-control  input-style grey-bg @error('other_work_type') is-invalid @enderror" value="{{ old('other_work_type') }}"  autocomplete="other_work_type" autofocus>
          @error('other_work_type')
          <span class="invalid-feedback" role="alert">
              <strong>это поле обязательно для заполнения.</strong>
          </span>
          @enderror
      </div>
    </div>
    <div class="d-flex mt-5">
        <button class="btn btn-success save_new_user_form_btn" id="buy_for_new_user_form_btn" send_email="0" event_name="{{ $event->title }}" type="button">Сохранить</button>
        <button class="btn btn-warning save_new_user_form_btn" id="buy_for_new_user_form_btn_email" send_email="1" event_name="{{ $event->title }}" type="button">Сохранить и отправить</button>
    </div>
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
@push('scripts')
<script type="text/javascript">
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
</script>

@endpush
