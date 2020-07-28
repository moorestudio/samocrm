<nav class="nav flex-column text-left scrollbar" id="search-result-ajax" style="max-height: 500px; width: 100%;box-shadow: 0px 20px 20px rgba(0, 0, 0, 0.13);">
    @if($count)
        @foreach($result as $key => $items)
            <div class="position-relative">
                <div class="collapse collapse-multi show" id="collapseAjax{{ $loop->index }}">
                    @foreach($items as $value)
                        <div class="nav-link px-2 getSelectedUser" data-id="{{$value->id}}" data-name="{{$value->name}}">
                            <div class="d-flex align-items-center justify-content-between pb-2" style="cursor: pointer;">
                                <div class="col">
                                    {{str_limit($value->name,20,'...')}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center m-0 py-4">Нет результата</p>
    @endif
</nav>
