<div class="col-12 report-header d-flex mt-3">
    <div class="col-3">
      <input type="text" class="form-control special-search" placeholder="Введите ФИО, еmail или телефон" id="search" name="search" autocomplete="off">
    </div>
    <div class="col-3">
      <p class="mb-0 title">ФИО</p>
    </div>
    <div class="col-3">
      <p class="mb-0 title">Email</p>
    </div>
    <div class="col-3">
      <p class="mb-0 title">№ Телефон</p>
    </div>
  </div>
 <div class="col-12 p-1 form-style d-flex" >
  <div class="col-3 pl-0" id="tbody_chosen_user_col">
    <div class="d-flex flex-wrap white-bg-round py-3">
      <div class="col-12">
        <p class="profile-info font-weight-bold">
          Детали выбранного слушателя
        </p>
         <p id="chosen_user_blocked" style="color: red;font-weight: bold;display: none;">
          Данный слушатель заблокирован
        </p>
      </div>
      <div class="col-6">
        <div>
          <label>Имя</label>
          <p class="mb-0 of-elipsis" id="chosen_user_name">-</p>
        </div>
        <div>
          <label>Отчество</label>
          <p class="mb-0 of-elipsis" id="chosen_user_middle_name">-</p>
        </div>
        <div>
          <label>Страна</label>
          <p class="mb-0 of-elipsis" id="chosen_user_country">-</p>
        </div>
        <div>
          <label>Телефон</label>
          <p class="mb-0 of-elipsis"id="chosen_user_contacts">-</p>
        </div>
        <div>
          <label>Компания</label>
          <p class="mb-0 of-elipsis"id="chosen_user_company">-</p>
        </div>
      </div>
      <div class="col-6">
        <div>
          <label>Фамилия</label>
          <p class="mb-0 of-elipsis" id="chosen_user_last_name">-</p>
        </div>
        <div>
          <label>Город</label>
          <p class="mb-0 of-elipsis" id="chosen_user_city">-</p>
        </div>
        <div>
          <label>Email</label>
          <p class="mb-0 of-elipsis" id="chosen_user_email">-</p>
        </div>
        <div>
          <label>Должность</label>
          <p class="mb-0 of-elipsis" id="chosen_user_job">-</p>
        </div>
        <div>
          <label>Деятельность</label>
          <p class="mb-0 of-elipsis"  id="chosen_user_work_type">-</p>
        </div>

<!--         <div>
          <label>Статус</label>
          <p class="mb-0 of-elipsis"  id="chosen_user_status">-</p>
        </div> -->
      </div>
    </div>
  </div>
  <div class="col-9 pr-0 pl-2">
    <div class="d-flex flex-wrap" id="tbody_user">
    </div>
  </div>
 </div>
