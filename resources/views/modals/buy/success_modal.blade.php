<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="success_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content p-2">
        <div class="modal-header border-0 py-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <img src="{{asset('images/report/close.svg')}}" alt="">
            </button>
        </div>
        <div>
            <div style="text-align: center;">
                <img src="{{ asset('images/report/check2.svg') }}" alt="">
                <p class="mb-2 d-block title mt-3">ваш заказ успешно оформлен</p>
                <p class="text px-5">Электронный билет выслан на вашу почту, пожалуйста проверьте почту.</p>
            </div>
            <div class="d-flex d-flex p-1 justify-content-center px-5">
                <button event_id="{{session('event_id')}}" class="btn btn-success reff_link_btn_share">Поделиться</button>
                @php session()->forget('event_id'); @endphp
                <a href="/profile"  class="btn li-btn btn-cancel">Посмотреть</a>
            </div>
        </div>
    </div>
  </div>
</div>