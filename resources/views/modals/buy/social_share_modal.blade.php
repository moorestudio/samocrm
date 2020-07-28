<div class="modal fade" id="social_share_modal" tabindex="-1" role="dialog" aria-labelledby="social_share_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;" role="document">
    <div class="modal-content p-4">
        <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <img src="{{asset('images/report/close.svg')}}" alt="">
        </button>
        </div>

        <div>
            <div class="mb-2" style="margin-top: -40px;">
                <p class="title">Поделиться</p>
            </div>
            <div class="d-flex social_modal_icons_wrapper">
                <div class="my-3 mr-3 text-center">
                    <a id="fb_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/facebook.svg') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Facebook</div>
                </div>
                <div class="m-3 text-center">
                    <a id="vk_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/vk.svg') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">ВКонтакте</div>
                </div>
                <div class="m-1 mt-3 text-center">
                    <a id="ok_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/classmates.svg') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Одноклассники</div>
                </div>
                <div class="m-3 text-center">
                    <a id="tw_share_link" href="" target="_blank">
                        <img src="{{ asset('images/social/twitter.svg') }}" alt="">
                    </a>
                    <div class="social_modal_icons_title">Твиттер</div>
                </div>
            </div>
            <div>
                <p class="title">Cсылка на мероприятие</p>
            </div>
            <div class="mt-2 mb-3 d-flex justify-content-between align-items-center" style="border:1px solid #EDEDED;background-color: #FAFAFA;border-radius: 5px;">
                <input type="text" id="reff_to_copy" value="" readonly>
                <p class="mb-0" id="copy_reff_btn" onclick="copy_reff()">
                    <img class="mr-1" src="{{asset('images/social/copy.svg')}}" alt="">
                    Копировать
                </p>
            </div>
        </div>
    </div>
  </div>
</div>