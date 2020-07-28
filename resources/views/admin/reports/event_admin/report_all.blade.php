@extends('layouts.admin_app')
@section('content')
    <div class="container">
        <div class="col-12 text-center pb-4 px-0 d-flex align-items-center justify-content-between">
            <p class="main-title mb-0 text-uppercase">общий финансовый отчет</p>
            <div class="d-flex align-items-center">
                <p class="second-title mb-0 mr-2">Выберите период</p>
                <input id="datepicker" class="form-control" autocomplete="off" style="width: 250px;color: #189DDF;" placeholder="12 май 2020 - 20 май 2020"/>
            </div>
        </div>

    <div class="reports_all_block_class">
        @include('admin.reports.event_admin.event_includes.all_report_body')
    </div>
    </div>
@endsection
@push('scripts')
    <script>
    new Litepicker({
    element: document.getElementById('datepicker'),
    singleMode: false,
    lang:'ru-RU',
    numberOfMonths:2,
    numberOfColumns:2,
    mobileFriendly:true,
    format:'D MMM, YYYY',
    showTooltip:false,


    onSelect(date1, date2) {
    get_report(date1, date2);
    }
    })

    function get_report(date1, date2) {
        $.ajax({
        type: 'POST',
        url: "/ajax_get_report",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'date1': date1,
            'date2':date2,
        },
        success: function (data) {
            $('.reports_all_block_class').html(data.view).show('slide', {direction: 'left'}, 400);
        },
        });

    };

    </script>
@endpush
