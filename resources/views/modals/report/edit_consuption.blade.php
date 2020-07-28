<div class="modal fade" id="EditConsuption" tabindex="-1" role="dialog" aria-labelledby="productModal"
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
                        <h5 class="text-uppercase font-weight-bold text-center">Изменить расход?</h5>
                    </div>
                    <div class="col-12">
                        <label for="event_name">Название расхода</label>
                        <div class="md-form mt-0">
                            <input placeholder="Введите название расхода*" type="text" name="name" id="edit_consuption_name" class="form-control consuption_name" required>
                        </div>
                    </div>
                    <div class="col-12 pb-4">
                        <label for="event_name">Сумма расхода</label>
                        <div class="md-form mt-0">
                            <input placeholder="Введите сумму расхода*" type="number" max="9999999999" name="sum" pattern="[0-9]" id="edit_consuption_sum" class="form-control consuption_sum" required>
                    </div>
                    </div>

                    <div class="col-6 text-center">
                        <button class="btn btn-success w-100 btn-edit" data-id="">Сохранить</button>
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
