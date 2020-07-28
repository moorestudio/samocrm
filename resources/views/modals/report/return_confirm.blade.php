<div class="modal fade" id="ReturnConfirm" tabindex="-1" role="dialog" aria-labelledby="productModal"
     aria-hidden="true">
    <div class="modal-dialog modal modal-dialog-centered" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-body p-lg-4 p-2 position-relative">
                <button type="button" class="close position-absolute" style="top:3%; right:3%;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark" style="font-size: 30px;">&times;</span>
                </button>
                <div class="row py-4">
                    <div class="col-12 pb-3">
                        <h5 class="text-uppercase font-weight-bold text-center main-title">Совершить возврат?</h5>
                    </div>
                    <div class="col-12 pb-3">
                        <textarea class="form-control" name="return_comment" id="return_comment" cols="20" rows="5" placeholder="Причина возврата?"></textarea>
                    </div>
                    <div class="col-6 text-center">
                        <button class="select-button w-100 btn-return" data-id="">Подтвердить</button>
                    </div>
                    <div class="col-6 text-center">
                        <button class="select-button w-100 btn-danger" data-dismiss="modal" aria-label="Close">Отмена</button>
                    </div>
                </div>
            </div>
            <div class="comment_error p-2 bg-danger" style="display:none;">
                <p class="text-center text-white mb-0">Напишите причину возврата билета (минимум 20 символов)!</p>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
