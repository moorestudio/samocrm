<div class="modal fade" id="hall_destroy" tabindex="-1" role="dialog" aria-labelledby="hall_destroy" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-0 py-2">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <img src="{{asset('images/report/close.svg')}}" alt="">
        </button>
      </div>
      <div class="modal-body">
        <div style="text-align: center;">
            <img src="{{ asset('images/question.svg') }}" alt="">
            <p class="mb-2 d-block title mt-3">Вы уверены что хотите <br> удалить схему зала?</p>
        </div>
        <div class="d-flex d-flex p-1 justify-content-center px-5">
            <button id="hall_destroy_modal_confirm" type="button" class="btn btn-success">Удалить</button>
            <button type="button" class="btn li-btn btn-cancel" data-dismiss="modal">Отмена</button>
        </div>
      </div>
    </div>
  </div>
</div>