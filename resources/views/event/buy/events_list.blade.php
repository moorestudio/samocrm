@extends('layouts.admin_app')
@section('content')
    <div class="container px-5">
        <div class="row justify-content-lg-start justify-content-center">
            <div class="col-12 px-0 pb-3">
                    <div class="justify-content-between d-lg-flex d-none">
                        <p class="text-uppercase main-title text-lg-left text-center mb-0">Выписка</p>
                    </div>
                    <div class="d-block text-center">
                        <p class="main-title-under text-lg-left text-center">Показаны только действующие мероприятия</p>
                    </div>
            </div>

<!--             <div class="col-lg-3 col-6">
                <button class="w-100 select-button select_active" data-id="1">
                    Действующие мероприятия
                </button>
            </div> -->
<!--             <div class="col-lg-3 col-6">
                <button class="w-100 select-button" data-id="2">
                    Прошедшие мероприятия
                </button>
            </div> -->
            <div class="col-12 mt-5 px-0">
                <div class="event_selector row justify-content-lg-start justify-content-center">
                    @include('event.buy.event_includes.events_active_admin')
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>
        $('.select-button').click(function (e) {
            var btn = $(e.currentTarget);
            var id = btn.data('id');

            if(!btn.hasClass('select_active'))
            {
                $('.event_selector').addClass('disappear');
                $('.select-button').removeClass('select_active');
                btn.addClass('select_active');

                $.ajax({
                    url: '{{ route('switch_event_report') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                    },
                    success: data => {
                        $('.event_selector').html(data.view).show('slide', {direction: 'left'}, 400);
                        $('.event_selector').removeClass('disappear');
                    },
                    error: () => {
                        $('.event_selector').removeClass('disappear');
                    }
                })
            }
        })
    </script>
@endpush
