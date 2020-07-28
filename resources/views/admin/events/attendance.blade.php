@extends('layouts.admin_app')
@section('content')
<div class="container">
  <div class="row col-lg-9 px-0">
    <h3 class="mb-3 mt-3 font-weight-medium attendance_events_title pl-0" style="color:#222222;text-transform: uppercase;">Посещаемость</h3>
  </div>
  <div class="row py-3 mb-1">
    <div class="col-lg-4 col-12 pt-3 pl-0 mb-3">
        <label for="event_date" style="color:#A6ACBE;">Дата начала мероприятия</label>
        <div class="mt-0">
            <input placeholder="Выберите период" type="text" id="event_sort_date" name="date" class="imput_new form-control input-style" autocomplete="off">
        </div>
    </div>
    <div class="col-lg-8 col-12 pt-3 pl-0 mb-3 d-flex align-items-end justify-content-end">
      <form action="{{route('export_all_events_attendance')}}" method="post">
        @csrf
        <input type="hidden" id="start_sort_date" name="start">
        <input type="hidden" id="end_sort_date" name="end">
        <button type="submit" class="exportBtn ml-1 export_all_events_attendance" ><img class="mr-1" src="{{asset('images/bx_bx-export.svg')}}" alt=""> Экспорт в exel</button>
      </form>
    </div>
  </div>
  <div class="report-header row mb-1 event">
      <div class="col-lg-2 col-12"><p class="mb-0 title">Проект</p></div>
      <div class="col"><p class="mb-0 title">Дата начало</p></div>
      <div class="col"><p class="mb-0 title">Дата окончания</p></div>
      <div class="col-lg-2"><p class="mb-0 title">Спикер</p></div>
      <div class="col-lg-2 col-12"><p class="mb-0 title">Общ. кол. бил.</p></div>
      <div class="col-lg-1 col-12"><p class="mb-0 title">Прод.</p></div>
      <div class="col-lg-1 col-12"><p class="mb-0 title">Не прод.</p></div>
      <div class="col-lg-1 col-12"><p class="mb-0 title">Выручка</p></div>
  </div>
  @include('admin.events.include.attendance_for_events',['events'=>$data['events']])
</div>
@endsection
@push('scripts')
<script>
  var picker = new Litepicker({
     element: document.getElementById('event_sort_date'),
     singleMode: false,
     lang:'ru-RU',
     numberOfMonths:2,
     numberOfColumns:2,
     mobileFriendly:true,
     format:'YYYY MMM D',
     showTooltip:false,
     onSelect: function(start, end){
       getFromSort();
       $('#start_sort_date').val(start);
       $('#end_sort_date').val(end);
     }
  });
  function getFromSort(){
    let start = picker.getStartDate();
    let end = picker.getEndDate();
    $.ajax({
        url: '{{ route('sort_event') }}',
        method: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "start": start,
            "end": end,
        },
        success: data => {
         $('.event.report-card').remove();
         $('.event.report-header').after(data.view);
        },
        error: () => {
        }
    })
  }
</script>
@endpush
