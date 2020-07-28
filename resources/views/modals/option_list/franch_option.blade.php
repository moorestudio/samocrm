<div class="modal fade" id="Franch_Option" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="second-title" id="exampleModalLabel">Настройка полей</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($option->access as $access)
                    <div class="row py-3 mb-1 report-card">
                        <div class="col-12 d-flex align-items-center justify-content-between">
                            <span>{{$access['slug']}}</span>
                            <div class="custom-checkbox custom-control">
                                <input type="checkbox" class="custom-control-input" name="{{$access['option']}}" id="{{$loop->index}}_list" {{$access['active'] == 'true' ? 'checked' : ''}}>
                                <label class="custom-control-label" for="{{$loop->index}}_list"></label>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                <button type="button" class="select-button px-4" data-id="{{$option->id}}" id="save_active_client">Сохранить</button>
            </div>
        </div>
    </div>
</div>