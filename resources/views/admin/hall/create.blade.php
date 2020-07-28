@extends('layouts.admin_app')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 pb-2 pl-0">
                <div class="d-flex justify-content-between">
                    <p class="main-title">{{ isset($halls) ? 'Редактирование зала' : 'Создать схему' }}</p>
                </div>
            </div>

            <div class="white-bg-round col-12 px-0 d-flex py-4">
            @if(isset($halls))
                <div class="col-8">
                    @if ($halls->count()==0)
                      <div class="w-100 h-100 d-flex align-items-center justify-content-center flex-column">
                        <img src="{{asset('images/denied.svg')}}" alt="">
                        <p class="empty-element">Схема отсутствует</p>
                      </div>
                    @endif
                    <div id="cells_wrapper_out" class="d-flex justify-content-center">
                    <div id="cells_wrapper"  class="d-flex flex-wrap justify-content-start overflow-auto" style="position: relative;width: min-content;">
                        @for($i = 1; $i <= $row;$i++)
                            <div class="d-flex">
                              <div class="m-1 font-weight-bold scene-place">{{$i}}
                              </div>
                              @for($j=1;$j<=$column; $j++)
                                  @if($halls[$i-1]['column'][$j]['status']!=-1)
                                      @foreach($event->rate as $rate)
                                          @if ($halls[$i-1]['column'][$j]['status'] == $loop->index)

                                              <div class="m-1 place scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="{{$loop->index}}" shape="{{$halls[$i-1]['column'][$j]['shape']}}"
                                                   style="background-color: {{$rate[1]}};border-bottom-color: {{$rate[1]}};"></div>

                                          @endif

                                      @endforeach
                                      @if ($halls[$i-1]['column'][$j]['status'] == 105)
                                          <div class="m-1 place scene-place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="5" shape=""
                                               style="background-color: white;border-style: solid;border-width: 1px;"></div>
                                      @endif
                                  @else
                                      <div class="m-1 place scene-place {{$halls[$i-1]['column'][$j]['shape']}}" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="6" shape="{{$halls[$i-1]['column'][$j]['shape']}}"
                                           style="background-color: red;border-bottom-color:red"></div>
                                  @endif
                              @endfor
                              <div class="m-1 font-weight-bold scene-place">{{$i}}
                              </div>
                            </div>
                        @endfor
                    </div>
                  </div>
                </div>

                <div class="col-4 m-0">
                    <?php
                        $tickets = count($event->tickets()->whereIn('type',['done','buy','reserve'])->get());
                    ?>
                    @if($tickets>0)
                        <span style="color: red;">НЕ редактируйте зал, на это мероприятие уже было куплено/бронировано {{$tickets}} билет(a-ов)</span>
                    @endif
                    @php $exist = ''; @endphp
                    @if ($halls->count()==0)
                    @php $exist = 'disabled-content'; @endphp
                    <div class="col-12 px-0" style="margin-bottom:20px;">
                      <p class="third-title mb-0">Создать схему зала</p>
                    </div>
                      <div class="col-12 px-0">
                          <form action="{{ route('hall.store', ['id' => $event->id]) }}" method="POST">
                              @csrf
                              <div class="d-flex flex-wrap">
                                  <div class="col-12 px-0">
                                      <input type="number" name="row" id="rowId" class="form-control input-style grey-bg" required placeholder="Количество рядов">
                                  </div>
                                  <div class="col-12 px-0" style="margin-top:15px;">
                                      <input type="number" name="column" id="columnId" class="form-control input-style grey-bg" required placeholder="Количество мест в ряду">
                                  </div>
                                  @isset($event)
                                      <input type="hidden" name="event_id" value="{{ $event->id }}">
                                  @endisset
                                  <div class="col-12 px-0" style="margin:15px 0;">
                                      <button type="submit" class="btn btn-success mini-btn ml-0">Создать</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                    @endif
                    <div class="custom-control custom-checkbox checkbox_event_active mb-2">
                        <input type="checkbox" class="custom-control-input" name="active" id="active" {{ isset($event) && $event->active == 1 ? 'checked' : ''}}>
                        <label class="custom-control-label" for="active">Опубликовать мероприятие?</label>
                    </div>
                    <div class="d-flex flex-wrap w-100 m-0 p-0 ">
                      <div class="col-12 px-0" style="margin-bottom:20px;">
                        <p class="third-title mb-0 {{$exist}}"> Изменить категории рядов</p>
                      </div>
                        @foreach($event->rate as $rate)
                          <div class="col-4 px-1">
                            <form class="set_type_form" action="{{ route('hall.set.type') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="set_type_form_all_data" id="all_data_form_{{$loop->index}}" name="all_data" value="">
                                <input type="hidden" name="eventId" value="{{ $event->id }}">
                                <input type="hidden" name="status" value="{{$loop->index}}">
                                <button class="btn w-100 m-0 set_type_btn p-0 {{$exist}}" type="button" style="background-color: {{$rate[1]}};color:white;">{{$rate[0]}}</button>
                            </form>
                          </div>
                        @endforeach
                    </div>
                    <div class="row m-0 p-0">
                      <div class="col-12 px-0" style="margin:20px 0;">
                        <p class="third-title mb-0 {{$exist}}">Удаление</p>
                      </div>
                        <div class="col-12 px-0 d-flex">
                          <form id="hall_destroy_form" action="{{ route('hall.all.destroy') }}" method="post">
                              @csrf
                              <input type="hidden" id="event_id" name="eventId" value="{{ $event->id }}">
                              <button type="button" data-toggle="modal" data-target="#hall_destroy" class="btn btn-warning mini-btn  mr-1 my-0 ml-0 {{$exist}}">Удалить схему</button>
                          </form>
                          <form id="rem_form_id" action="{{ route('hall.set.type') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              <input type="hidden" id="all_data_form_r" name="all_data" value="">
                              <input type="hidden" name="eventId" value="{{ $event->id }}">
                              <input type="hidden" name="status" value="105">
                              <button id="btn_remove" class="btn btn-warning mini-btn ml-1 my-0 mr-0 {{$exist}}">Удалить место</button>
                          </form>
                        </div>
                       </div>
                    </div>
                </div>
              </div>
            @endif
        </div>
    </div>




<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button> -->

<!-- Modal -->
@include('modals.halls.delete_hall_modal')

@endsection
@push('scripts')
    <script>
        $('.place').click(e => {
            e.preventDefault();
            $('.place').removeClass('place-choice');
            let btn = $(e.currentTarget);
            let event_id = btn.data('id');
            let row = btn.data('parent');
            let place = btn.data('parent2');
            $(e.currentTarget).addClass('place-choice');
            $('#event_id').val(event_id);
            $('#row_number').val(row);
            $('#place_number').val(place);

        })
    </script>
    <script>
/////////////////////////////////////////////////

/////////////////////////////////////////////////

        var row = [];  ///parent 1
        var column = []; ///parent 2
        var eventId = null;

        function rowValue() {
            row = $('#rowDelete').val();
            eventId = "{{ $event->id }}";
            $.ajax({
                type: 'POST',
                url: "{{ route('hall.delete.row') }}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'row': row,
                    'eventId': eventId
                },
                success: function (data) {
                    location.reload();
                    conole.log('delete row success')
                },
            });
        }

        function columnValue() {
            column = $('#columnDelete').val();
            eventId = "{{ $event->id }}";
            $.ajax({
                type: 'POST',
                url: "{{ route('hall.delete.column') }}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'column': column,
                    'eventId': eventId
                },
                success: function (data) {
                    location.reload();
                },
            });
        }
///////////////////-----------------------------------------------------------

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                if((ax = arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        function removeColumn(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                if((ax = arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        function registerplace(item, parent, parent2) {
            item.click(function (e) {

                e.preventDefault();
                // $(e.currentTarget).toggleClass('place-choice');//////////////////////---------------------------------s
                // $(e.currentTarget).unbind( "click" );
                removeA(row, parent);
                removeColumn(column, parent2);
                removeA(row, parent);
                removeColumn(column, parent2);

            })
        }

        $('.place').click(e => {
            e.preventDefault();
            let btn = $(e.currentTarget);
            eventId = (btn.data('id'));
            row.push(btn.data('parent'));
            column.push(btn.data('parent2'));
            $(e.currentTarget).addClass('place-choice');
            $('#row_number').val(row);
            $('#place_number').val(column);
            $('#event_id').val(eventId);
            $('#row_number_s').val(row);
            $('#place_number_s').val(column);
            $('#event_id_s').val(eventId);
            $('#row_number_v').val(row);
            $('#place_number_v').val(column);
            $('#event_id_v').val(eventId);
            registerplace($(e.currentTarget), btn.data('parent'), btn.data('parent2'));

        });

        function deleteColumn() {
            $.ajax({
                type: 'POST',
                url: "{{ route('hall.destroy') }}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'row': row,
                    'column': column,
                    'eventId': eventId
                },
                success: function (data) {
                },
            });
        }
$('#hall_destroy_modal_confirm').click(function(e){
    document.getElementById("hall_destroy_form").submit();
});
var selectedRowCol_set= new Set();
var selectedRowCol_set_out= new Set();

    // })
    $('#btn_gold').click(function(e){
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_form_g').val(selectedRowCol_form);

        if(selectedRowCol_form.length>0){
            document.getElementById("gold_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        }
        else{
            alert("Ничего не выбрано haha");
            return false;
        }

    });
/////////////////////////////////////////////////////////////////////////////////
    $('#shape_triangle_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_shape_t').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("std_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
    //    selectedRowCol_form.forEach((el) => {
    //     $('#'+el).removeClass('place-choice');
    //     let color = $('#'+el)[0].style.backgroundColor
    //     $('#'+el).addClass('triangle_shape').css("border-bottom-color", color);
    //     $('#'+el).attr("shape","triangle_shape");
    // })
   })

    $('#shape_oval_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_shape_o').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("std_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));
    })
    $('#shape_round_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_shape_r').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("std_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));
    })
    $('#shape_square_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_shape_sq').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("std_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));
    })
////////////////////////////////////////////////////////////////////////////////
    $('#merge_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set_out);
        $('#all_data_shape_b').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("vip_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));

        // selectedRowCol_form.forEach((el) => {
        // $('#'+el).addClass('block_shape');
        // })
    })
    $('#merge_btn_clear').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set_out);
        $('#all_data_shape_b_clear').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("vip_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));

        // selectedRowCol_form.forEach((el) => {
        // $('#'+el).addClass('block_shape');
        // })
    })
    $('#speaker_btn').click(function (e) {

            let x = document.getElementById("speaker_position_img").getAttribute("data-x");
            let y = document.getElementById("speaker_position_img").getAttribute("data-y");
            $('#speaker_pos_x').val(x);
            $('#speaker_pos_y').val(y);
    })
        $('#entry_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set_out);
        $('#all_data_entry').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("vip_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));

        // selectedRowCol_form.forEach((el) => {
        // $('#'+el).addClass('block_shape');
        // })
    })
        $('#exit_btn').click(function (e) {
        let selectedRowCol_form=Array.from(selectedRowCol_set_out);
        $('#all_data_exit').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            // document.getElementById("vip_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
        // selectedRowCol_form.forEach(el=>$('#'+el).addClass('oval_shape'));

        // selectedRowCol_form.forEach((el) => {
        // $('#'+el).addClass('block_shape');
        // })
    })
////////////////////////////////////////////////////////////////////////////////
    $('#btn_VIP').click(function(e){
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_form_v').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            document.getElementById("vip_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }

    });
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('.set_type_btn').click(function(e){
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('.set_type_form_all_data').val(selectedRowCol_form);

        let form_this = e.currentTarget.parentElement
        if(selectedRowCol_form.length>0){

            form_this.submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }

    });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // $('#btn_VIP').click(function(e){
    // selectedRowCol=Array.from(selectedRowCol_set);
    // e.stopPropagation();
    // send_all_in_one(2)
    // });
    // $('#btn_standart').click(function(e){
    // selectedRowCol=Array.from(selectedRowCol_set);
    // e.stopPropagation();
    // send_all_in_one(1)
    // });

    $('#btn_standart').click(function(e){
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_form_s').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            document.getElementById("std_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }


    });


    // $('#btn_remove').click(function(e){
    // selectedRowCol=Array.from(selectedRowCol_set);
    // e.stopPropagation();
    // send_all_in_one(5)

    // })

    $('#btn_remove').click(function(e){
        let selectedRowCol_form=Array.from(selectedRowCol_set);
        $('#all_data_form_r').val(selectedRowCol_form);
        if(selectedRowCol_form.length>0){
            document.getElementById("rem_form_id").submit();
            $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Загрузка...</h1>' });
        }
        else{
            alert("Ничего не выбрано");
            return false;
        }
    });

$('.checkbox_event_active').on('change', 'input[type=checkbox]', function() {
  let event_status = $("#active").is(':checked');
    $.ajax({
        url: '/event.change.status',
        method: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "event": {{$event->id}},
            "event_status": event_status,
        },
        success: data => {
            result_text = data.status==1?"Опубликована":"Не опубликована";
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Статус: ' + result_text,
                // description: ' Каждый пользователь может купить лишь 1 билет ' ,
            });

        },
    });

});


    </script>
@endpush
