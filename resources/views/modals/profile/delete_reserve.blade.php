<div class="modal fade" id="DeleteReserve" tabindex="-1" role="dialog" aria-labelledby="productModal"
     aria-hidden="true">
    <div class="modal-dialog modal modal-dialog-centered" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-body p-4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>

                <div class="row py-4">
                    <div class="col-12 pb-4">
                        <h5 class="text-uppercase font-weight-bold text-center">Удаление брони</h5>
                    </div>
                    <div class="col-12">
                    <label for="delete_comment">Ваш комментарий</label>
                    <div class="md-form mt-0">
                        <input placeholder="Введите причину удаления*" type="text" id="delete_comment" name="delete_comment" class="form-control">
                    </div>
                    </div>
                    <div class="col-6 text-center">
                        <button class="btn btn-success w-100 btn-reserve-delete" data-id="">Удалить</button>
                    </div>
                    <div class="col-6 text-center">
                        <button class="btn btn-danger w-100" data-dismiss="modal" aria-label="Close">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
