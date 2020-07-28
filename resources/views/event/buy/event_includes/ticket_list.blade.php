@extends('layouts.app')
@section('content')
<?php
$agent = new \Jenssegers\Agent\Agent();
?>
<div class="container mt-4">
  <div class="row px-0 mb-4">
    <p class="main-title text-uppercase text-lg-left text-center mb-1">Статусы заявок</p>
  </div>
  <div class="row py-2 mb-1 report-header" style="z-index: 1;">
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">ФИО</p>
    </div>
    <div class="col-lg-1 col-12">
        <p class="mb-0 title">Статус</p>
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">Причина возврата</p>
    </div>
    <div class="col-lg-2 col-12">
        <p class="mb-0 title">Дата снятия брони</p>
    </div>
    <div class="col-lg-3 col-12">
        <p class="mb-0 title">Дата окончания мероприятия</p>
    </div>
    <div class="col-lg-1 col-12">
        <p class="mb-0 title" style="text-align: center;">Действие</p>
    </div>
  </div>
@foreach($tickets as $ticket)
    <div class="row mb-1 report-card special-card">
        <div class="col-lg-2 col-12">
            <p class="mb-0 of-elipsis">
                @if(\App\User::find($ticket->user_id))
                    {{ \App\User::find($ticket->user_id)->fullName() }}
                @else
                    Error - {{$ticket->id}}
                @endif
            </p>
        </div>

        <div class="col-lg-1 px-1 col-12 text-center">
            @if($ticket->type == 'buy')
                <div class="client_status" style="background-color:#E1EEFF;color: #189DDF;">
                   Продан
                </div>
            @elseif($ticket->type == 'reserve')
                <div class="client_status" style="background-color: #E1E8F3;color: #1E2433;">
                   Бронь
                </div>
            @elseif($ticket->type == 'done')
                <div class="client_status" style="background-color: #08B955;">
                   Пришел
                </div>
            @elseif($ticket->type == 'return')
                <div class="client_status" style="background-color: #F2D3D3;color: #EF6E6E;">
                    Возврат
                </div>
            @elseif($ticket->type == 'false')
                <div class="client_status" style="background-color: #333333;">
                    Не пришел
                </div>
            @endif
         </div>
        <div class="col-lg-2 col-12">
            <p class="mb-0 of-elipsis" data-toggle="tooltip" title="{{$ticket->comment}}">
                {{  \Illuminate\Support\Str::limit($ticket->comment, 20, $end='...') }}

            </p>
        </div>

        <div class="col-lg-2 col-12">
            <p class="mb-0 of-elipsis">
                @if($ticket->type == 'reserve')
                    {{ \Carbon\Carbon::parseFromLocale($ticket->reserve_date, 'ru')->format('d/m/Y')}}
                @endif
            </p>
        </div>
        <div class="col-lg-3 col-12">
            <p class="mb-0 of-elipsis">
                {{ \Carbon\Carbon::parseFromLocale($ticket->event_date, 'ru')->format('d/m/Y')}}
            </p>
        </div>
        <div class="col-lg-2 col-12 d-flex" style="justify-content: space-around;">
          @if($ticket->type == 'buy')
            <div class="col-lg-2 col-12 p-0 text-lg-center text-left return-confirm" style="cursor: pointer;" data-id="{{ $ticket->id }}"  data-toggle="tooltip" title="Совершить возврат">
                <img src="{{asset('images/goback.svg')}}" alt="">
            </div>
            @elseif($ticket->type == 'reserve')
            <div class="col-lg-1 col-12 p-0 text-lg-center text-left prolongReserve overflow-auto-unset" data-event_date="{{ \Carbon\Carbon::parseFromLocale($ticket->event_date, 'ru')->format('d/m/Y')}}" style="cursor: pointer;" data-id="{{ $ticket->id }}" data-toggle="tooltip" title="Продлить бронь">
              <img src="{{asset('images/return.svg')}}" alt="">
            </div>
            <div class="col-lg-1 col-12 p-0 text-lg-center text-left removeReserve overflow-auto-unset" style="cursor: pointer;" data-id="{{ $ticket->id }}" data-toggle="tooltip" title="Снять бронь">
              <img src="{{asset('images/delete.svg')}}" alt="">
            </div>
            <div class="col-lg-1 col-12 p-0 text-lg-center text-left buyReserve overflow-auto-unset" style="cursor: pointer;" data-id="{{ $ticket->id }}"  data-toggle="tooltip" title="Выкупить">
                <a href="{{url('admin_buy_reserved',['id'=>$ticket->id])}}">
                  <img src="{{asset('images/shopping-cart.svg')}}" alt="">
                </a>
            </div>
          @endif
        </div>
    </div>

@endforeach

</div>
@include('modals.report.return_confirm')
@include('modals.profile.delete_reserve')
@include('modals.profile.prolong_reserve')
@endsection
@push('scripts')
<script>
    $(document).on('click', '.return-confirm',function (e) {
        var btn = $(e.currentTarget);
        var id = btn.data('id');
        $('.btn-return').attr('data-id',id);
        $('#ReturnConfirm').modal('show');
    });
    $(document).on('click','.removeReserve', function(e) {
        var btn = $(e.currentTarget);
        var id = btn.data('id');


        $('.btn-reserve-delete').attr('data-id',id);
        $('#DeleteReserve').modal('show');
    })
    $(document).on('click','.prolongReserve', function(e) {
        var btn = $(e.currentTarget);
        var id = btn.data('id');

        document.getElementById("current_ticket_event_date").innerHTML =btn.data('event_date');

        new Litepicker({
        element: document.getElementById('datepicker_reserve'),
        singleMode: true,
        lang:'ru-RU',
        numberOfMonths:1,
        numberOfColumns:1,
        mobileFriendly:true,
        format:'D/MM/YYYY',
        showTooltip:false,
          onSelect(date) {
            $('.btn-new_reserve_date').attr('data-new_date',date);
          }
        })


        $('.btn-new_reserve_date').attr('data-id',id);
        $('#prolongReserve').modal('show');
    })


    $(document).on('click','.btn-return',function (e) {
    var btn = $(e.currentTarget);
    var id = btn.data('id');
    var comment = $('#return_comment');

    if(comment.val() != '' && comment.val().length > 19)
    {
        $('.comment_error').hide(200);

        $.ajax({
            url: '{{ route('return_ticket') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "comment": comment.val(),
            },
            success: data => {
                comment.val('');
                $('#ReturnConfirm').modal('hide');
                location.reload();
                document.getElementById('buy-' + id).style.display = "none";
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Статус билета изменен!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: false,
                });

            },
            error: () => {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ошибка!',
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: false,
                });
            }
        })

    }
    else
    {
        $('.comment_error').show(200);
    }
})

$(document).on('click','.btn-reserve-delete', function (e) {
    var btn = $(e.currentTarget);
    var id = btn.data('id');
    var comment = $('#delete_comment').val();
    if(comment != '')
    {
        $.ajax({
            url: '{{ route('profile_delete_reserve') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "comment": comment,
            },
            success: data => {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Статус билета изменен!',
                    timer: 2000,
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: false,
                });
                setTimeout(() => window.location.reload(true), 9000);
                $('#reserve-' + id).hide(200);
                $('#DeleteReserve').modal('hide');
            },
            error: () => {
            }
        })
    }
    else
    {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Напишите причину удаления брони!',
            // description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
            showConfirmButton: false,
        });
    }
})

$(document).on('click','.btn-new_reserve_date', function (e) {
    $('#prolongReserve').modal('hide');
    var btn = $(e.currentTarget);
    var id = btn.data('id');
    var new_date = btn.data('new_date');


    if(new_date != '')
    {
        $.ajax({
            url: '/ajax_set_new_reserve_date',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "new_date": new_date,
            },
            success: data => {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Статус билета изменен!',
                    timer: 2000,
                    // description: ' Каждый пользователь может купить лишь 1 билет ' ,
                    showConfirmButton: false,
                });
                // setTimeout(() => window.location.reload(true), 9000);
                setTimeout(function() {
                    window.location.reload(true);
                }, 9000); 
            },
            error: () => {
            }
        })
    }
    else
    {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Выберите дату!',
            // description: 'Вы не можете купить другой билет, если у вас есть забронированный билет',
            showConfirmButton: false,
        });
    }
})

</script>
@endpush
