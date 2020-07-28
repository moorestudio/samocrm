@extends('layouts.admin_app')
@section('content')
    <?php
    $agent = new \Jenssegers\Agent\Agent();
    ?>
    <div class="container px-0">
        <div class="col-12 pb-2 px-0">
            <p class="main-title">Активность пользователей</p>
        </div>
        <div class="row mb-3">
          <div class="col-lg-2 col-12 pt-3">
              <label for="event_name" style="color:#6B6B6B;">Роль пол-ля</label>
              <div class="mt-0">
                  <select class="browser-default custom-select input-style" name="category_id" id="user_activity_role">
                      <option value="" selected>Выберите роль</option>
                      @foreach(\App\Role::where('id','!=',3)->where('id','!=',1)->get() as $role)
                              <option value="{{$role->id}}">{{$role->display_name}}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="col-lg-2 col-12 pt-3">
              <label for="event_name" style="color:#6B6B6B;">Пользователь</label>
              <div class="mt-0">
                  <input placeholder="Введите ФИО" type="text" id="user_activity_id" class="form-control input-style" autocomplete="off" data-id="">
                  <div class="position-relative">
                      <div class="position-absolute search-result-app bg-white mt-2" id="user_activity_result" style="right: 0; top: 160%;width:100%; z-index:999;">
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-lg-3 col-12 pt-3">
              <label for="event_date" style="color:#6B6B6B;">Период</label>
              <div class="mt-0">
                  <input placeholder="Выберите период" type="text" id="user_activity_date" name="date" class="form-control input-style" autocomplete="off">
              </div>
          </div>
          <div class="col-lg-5 col-12 pt-3 d-flex justify-content-end">
            <div class="visit col-lg-4 px-1">
              <label for="event_date" style="color:#6B6B6B;">Кол.посещений</label>
              <div class="mt-0">
                  <input type="text" name="date" class="form-control input-style for-visit-q disabled" autocomplete="off" value='0'>
              </div>
            </div>
            <div class="activated col-lg-4 px-1">
              <label for="event_date" style="color:#6B6B6B;">Кол.активаций</label>
              <div class="mt-0">
                  <input type="text" name="date" class="form-control input-style for-active-q disabled" autocomplete="off" value='0'>
              </div>
            </div>
            <div class="blocked col-lg-4 px-1">
              <label for="event_date" style="color:#6B6B6B;">Кол.блокирования</label>
              <div class="mt-0">
                  <input type="text" name="date" class="form-control input-style for-block-q disabled" autocomplete="off" value='0'>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-lg-start justify-content-center" id="visit_activity_content">
        </div>
        <div class="d-flex mt-4" id="action_activity_content">
        </div>
    </div>
@endsection
@push('scripts')
<script>
 var picker = new Litepicker({
    element: document.getElementById('user_activity_date'),
    singleMode: false,
    lang:'ru-RU',
    numberOfMonths:2,
    numberOfColumns:2,
    mobileFriendly:true,
    format:'YYYY MMM D',
    showTooltip:false,
    onSelect: function(start, end){
      getUserActivityReport();
    }
});
$(document).on('keyup','#user_activity_id',function(){
  let role = $('#user_activity_role').val();
  let user = $('#user_activity_id').val();
  axios.post('getUserWithRole',{
    'role':role,
    'user':user,
  }).then(function(response){
    $('#user_activity_result').html(response.data.users);
  });
});
$(document).on('click','.getSelectedUser',function(){
  let val = $(this).attr('data-id');
  let name = $(this).attr('data-name');
  $('#user_activity_id').val(name).attr('data-id',val);
  $('#user_activity_result').html('');
  getUserActivityReport();
});
function getUserActivityReport(){
  let start = picker.getStartDate();
  let end = picker.getEndDate();
  let user_activity = $('#user_activity_id').attr('data-id');
  if(user_activity){
    axios.post('/getUserActivityReport',{
      'start':start,
      'end':end,
      'user':user_activity,
    }).then(function(response){
      $('#visit_activity_content').html(response.data.visit);
      $('#action_activity_content').html(response.data.action);
      let visit_q = 0;
      let active_q = 0;
      let block_q = 0;
      if(response.data.visit_q){
        visit_q = response.data.visit_q;
      }
      if(response.data.active_q){
        active_q = response.data.active_q;
      }
      if(response.data.block_q){
        block_q = response.data.block_q;
      }
      $('.for-visit-q').val(visit_q);
      $('.for-active-q').val(active_q);
      $('.for-block-q').val(block_q);
    });
  }else{
    Swal.fire({
        position: 'center',
        icon: 'info',
        title: 'Выберите пользователя!',
        showConfirmButton: false,
        timer:1500
    });
  }

};
</script>
@endpush
