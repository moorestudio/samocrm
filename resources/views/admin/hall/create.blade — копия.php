@extends('layouts.admin_app')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 pt-5 pb-3">
                <div class="d-flex justify-content-between">
                    <p class="h2 text-uppercase font-weight-bold">{{ isset($halls) ? 'Редактирование зала' : 'Создать схему' }}</p>
                </div>
            </div>

            @if ($halls->count()==0)
            <div class="col-12">
                <form action="{{ route('hall.store', ['id' => $event->id]) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <input type="number" name="row" id="rowId" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <input type="number" name="column" id="columnId" class="form-control" required>
                        </div>
                        @isset($event)
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                        @endisset
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Создать</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
            <br>
            <br>
            @if (isset($halls))
                <div class="col-9">
                    <div id="cells_wrapper" class="row p-4 justify-content-center">
                        @for($i = $row; $i >= 1;$i--)
                            @for($j=1;$j<=$column; $j++)
                                @if ($halls[$i-1]['column'][$j]['active'] == 1)
                                    <div class="m-1 place"
                                         style="height:10px; width:10px; background-color: green;
                                         opacity: 0; pointer-events: none;"></div>

                                @elseif ($halls[$i-1]['column'][$j]['status'] == 3)
                                    <div class="m-1 place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="3"
                                         style="height:10px; width:10px;background-color: {{$event->rate['rate3_color']}};"></div>

                                @elseif ($halls[$i-1]['column'][$j]['status'] == 2)
                                    <div class="m-1 place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="2"
                                         style="height:10px; width:10px;background-color: {{$event->rate['rate2_color']}};"></div>

                                @elseif ($halls[$i-1]['column'][$j]['status'] == 1)
                                    <div class="m-1 place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="1"
                                         style="height:10px; width:10px;background-color: {{$event->rate['rate1_color']}};"></div>

                                @elseif ($halls[$i-1]['column'][$j]['status'] == 5)
                                    <div class="m-1 place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="1"
                                         style="height:10px; width:10px;background-color: white;border-style: solid;border-width: 1px;"></div>

                                @else
                                    <div class="m-1 place" data-id="{{ $event->id }}" data-parent="{{$i}}" data-parent2="{{ $j }}" id="{{$i}}-{{$j}}" title="{{$i}}-{{$j}}" status="6"
                                         style="height:10px; width:10px; background-color: red;"></div>
                                @endif
                            @endfor
                            <div class="col-12"></div>
                        @endfor
                    </div>
                </div>
                <div class="col-3">
<!-- {{--                     <form action="{{ route('hall.destroy') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="row_number" name="row[]" value="">
                        <input type="hidden" id="place_number" name="column[]" value="">
                        <input type="hidden" id="event_id" name="eventId" value="">
 --}} -->
                    <button id="btn_remove" class="btn btn-danger btn-lg btn-block">Удалить</button>
<!-- {{--                     </form> --}} -->

                    <div>
                        <form action="{{ route('hall.all.destroy') }}" method="post">
                            @csrf
                            <input type="hidden" id="event_id" name="eventId" value="{{ $event->id }}">
                            <button type="submit" class="btn btn-danger btn-lg btn-block mt-2">Удалить схемy</button>
                        </form>
                    </div>
                    <div>
<!--  {{--                        <form action="{{ route('hall.set.type') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="row_number_s" name="row[]" value="">
                            <input type="hidden" id="place_number_s" name="column[]" value="">
                            <input type="hidden" id="event_id_s" name="eventId" value="">
                            <input type="hidden" name="type" value="1"> --}} -->
                            <button id="btn_standart" class="btn btn-lg btn-block mt-2" style="background-color: {{$event->rate['rate1_color']}}">{{$event->rate['rate1']}}</button>
<!-- {{--                         </form> -->
  <!--                       <form action="{{ route('hall.set.type') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="row_number_v" name="row[]" value="">
                            <input type="hidden" id="place_number_v" name="column[]" value="">
                            <input type="hidden" id="event_id_v" name="eventId" value="">
                            <input type="hidden" name="type" value="3"> --}} -->
                            <button id="btn_VIP"  class="btn btn-lg btn-block mt-2" style="background-color: {{$event->rate['rate2_color']}}">{{ $event->rate['rate2'] }}</button>
                        <!-- {{-- </form> --}} -->

                        <!-- <form enctype="multipart/form-data"> -->
                        <!-- <form action="{{ route('hall.set.type') }}" method="POST" enctype="multipart/form-data">  -->
<!-- {{-- <!-                             @csrf --}}
 {{--                            <input type="hidden" id="row_number_v" name="row[]" value="">
                            <input type="hidden" id="place_number_v" name="column[]" value="">
                            <input type="hidden" id="event_id_v" name="eventId" value="">
                            <input type="hidden" name="type" value="2"> > --}} -->
                            <button id="btn_gold" class="btn btn-lg btn-block mt-2" style="background-color: {{$event->rate['rate3_color']}}">{{$event->rate['rate3']}}</button>
                            <button id="btn_save" class="btn btn-success btn-lg btn-block mt-2">Save</button>
                            <!-- <button type="submit" class="btn btn-warning btn-lg btn-block mt-2">Gold</button> -->
                        <!-- </form> -->
                    </div>
                    <div>
                        <select name="row" id="rowDelete" onchange="rowValue()" class="form-control mt-2">
                            <option value="">Выберите ряд для удаления</option>
                            @foreach($halls as $hall)
                                <option value="{{ $hall->row }}">Ряд {{ $hall->row }}</option>
                            @endforeach
                        </select>
                        <select name="column" id="columnDelete" onchange="columnValue()" class="form-control mt-2">
                            <option value="">Выберите колонки для удаления</option>
                            @for($i = 1; $i <= $column; $i++)
                                <option value="{{ $i }}">Колонки {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            @endif
        </div>
    </div>
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
                $(e.currentTarget).toggleClass('place-choice');
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
///////////////////-----------------------------------------------------------

////////////////////////////////////////////////////////////////////////////////

    // $('#cells_wrapper div').click(function(e){
    // e.stopPropagation();
    // var cell = e.currentTarget.id;
    // if (!selectedRowCol.includes(cell)){
    //     selectedRowCol.push(cell);
    //     document.getElementById(cell).classList.remove('golden_style','vip_style','standart_style','removed_style','default_red');
    //     $(e.currentTarget).addClass('place-choice');
    // }
    // else{
    //     var index = selectedRowCol.indexOf(cell);
    //     if (index > -1) {
    //         selectedRowCol.splice(index, 1);
    //     }
    //     $(e.currentTarget).removeClass('place-choice');
    //     $(e.currentTarget).addClass('default_red');
    // }
    // })
var selectedRowCol_set= new Set();
// var selectedRowCol =[];


//     var row_num=[];
//     var col_num=[];
//     var class_color = '';
//      function changeColorFunc(item, index) {
//         if (class_color=='white'){
//             $('#'+item).css("border-style", 'solid');
//             $('#'+item).css("border-width", '1px');
//         }
//         $('#'+item).css("background-color", class_color);
//         $('#'+item).addClass(class_color);
//         // document.getElementById(item).classList.remove('golden_style','vip_style','standart_style','removed_style','default_red');

//         // $('#'+item).removeClass("place-choice");

//         // $('#'+item).css("background-color", class_color);
//         // $('#'+item).addClass(class_color);s

//     }


//     function split_row_col(item,index){
//         var all_split = item.split('-');
//         row_num.push(parseInt(all_split[0]));
//         col_num.push(parseInt(all_split[1]));
//     }

//     function set_type_front(type){
//         selectedRowCol.forEach(function(element){
//           $("#"+element).attr("status", type);
//         });

//     }

//     var row=[];
//     var col=[];
//     var status2=[];


    // $('#btn_save').click(save_changes);
    // var arrayStatRowCol=[];
    // function save_changes(){
    //     var all = document.getElementById('cells_wrapper').children;
    //     all =Array.from(all)
    //     all.forEach(function(el){
    //         if (el.id != ""){
    //             var tempArr = [];
    //             let all_split = el.getAttribute("id").split('-');
    //             tempArr.push(el.getAttribute("status"),all_split[0],all_split[1])
    //             arrayStatRowCol.push(tempArr);
    //         }
    //     })
    //     split_3(arrayStatRowCol)

    //     set_type()
    //     arrayStatRowCol=[]; ///обнуление
    //     row=[];
    //     col=[];
    //     status2=[];
    // }

    // function split_3(arr){
    //     row=[];
    //     arr.forEach(function(el){
    //         row.push(el[1]);
    //         col.push(el[2]);
    //         status2.push(el[0]);
    //     });


    // }

    // function set_type(){
    //     $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' }); 
    //     eventId_send="{{ $event->id }}";
    //     $.ajax({
    //         type: 'POST',
    //         url: "{{ route('hall.set.type') }}",
    //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //         data: {
    //             'row': row,
    //             'col': col,
    //             'status': status2,
    //             'eventId': eventId_send,
    //         },
    //         success: function (data) {
    //             row=[];
    //             selectedRowCol=[];
    //             row_num =[];
    //             col_num =[];
    //             $(document).ajaxStop($.unblockUI);
    //             document.location.reload(); 
    //         },
    //     });
    // }

    function send_all_in_one(status){
        $.blockUI({ message: '<div class="spinner-border" role="status"></div><h1> Ждем...</h1>' });
        status_all = status;
        eventId_send="{{ $event->id }}";
        $.ajax({
            type: 'POST',
            url: "{{ route('hall.set.type') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'all_data': selectedRowCol,
                'status': status_all,
                'eventId': eventId_send,
            },
            success: function (data) {
                $(document).ajaxStop($.unblockUI);
                document.location.reload(); 
            },
        });
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// function save_changes_30(status){
//     // var all = document.getElementById('cells_wrapper').children;
//     //
//     // all =Array.from(all)
//     selectedRowCol.forEach(function(el){
//             var tempArr = [];
//             let all_split = el.split('-');
//             tempArr.push(status,all_split[0],all_split[1])
//             arrayStatRowCol.push(tempArr);
//     });
//     split_3(arrayStatRowCol)

//     set_type()
//     arrayStatRowCol=[]; ///обнуление
//     row=[];
//     col=[];
//     status2=[];
// }
////////

    $('#btn_gold').click(function(e){
    selectedRowCol=Array.from(selectedRowCol_set);
    e.stopPropagation();
    send_all_in_one(3)
    // class_color=$( this ).css( "background-color" );
    // selectedRowCol.forEach(split_row_col)
    // selectedRowCol.forEach(changeColorFunc)
    // set_type_front(3);
    // split_3(arrayStatRowCol);
    // save_changes_30(3);
    // document.location.reload();
    })
    $('#btn_VIP').click(function(e){
    selectedRowCol=Array.from(selectedRowCol_set);
    e.stopPropagation();
    send_all_in_one(2)
    // class_color=$( this ).css( "background-color" );
    // selectedRowCol.forEach(split_row_col)
    // selectedRowCol.forEach(changeColorFunc)
    // set_type_front(2);
    // split_3(arrayStatRowCol);
    // save_changes_30(2);
    // document.location.reload();
    });
    $('#btn_standart').click(function(e){
    selectedRowCol=Array.from(selectedRowCol_set);
    e.stopPropagation();
    send_all_in_one(1)
    // class_color=$( this ).css( "background-color" );
    // selectedRowCol.forEach(split_row_col);
    // selectedRowCol.forEach(changeColorFunc);
    // set_type_front(1);
    // split_3(arrayStatRowCol);
    // save_changes_30(1);
    // document.location.reload();
    });
    $('#btn_remove').click(function(e){
    selectedRowCol=Array.from(selectedRowCol_set);
    e.stopPropagation();
    send_all_in_one(5)
    // class_color=$( this ).css( "background-color" );
    // selectedRowCol.forEach(split_row_col)
    // selectedRowCol.forEach(changeColorFunc)
    // set_type_front(5);
    // split_3(arrayStatRowCol);
    // save_changes_30(5);
    // document.location.reload();
    })

    </script>
@endpush
