@extends('layouts.app')
@section('content')

<div class="container"  style="    padding-top: 2%;">
    <div class="row justify-content-lg-start justify-content-center">
        <div class="col-12 pb-3">
            <div>
                <p class="main-title text-uppercase text-lg-left text-center">Все мероприятие</p>
            </div>
        </div>
        <div class="col-12 pb-3 d-flex flex-wrap">
            <div class="col-auto px-0">
                <a href="{{ route('main') }}" class="category-sort-title {{$id ? '':'active'}}">Все</a>
            </div>
            @foreach($categories as $category)
            <div class="col-auto px-0">
                <a href="/home/{{$category->id}}" class="category-sort-title {{$id==$category->id ? 'active' : '' }}">{{$category->name}}</a>
            </div>
            @endforeach
        </div>
        @if(session('check') !== null)
          <input type="hidden" name="" value="{{session('check')}}" id='checkTry'>
          @php session()->forget('check'); @endphp
        @endif

        @forelse($events as $event)
            @if(\Carbon\Carbon::parseFromLocale($event->date,'ru') > \Carbon\Carbon::now())
                <div class="p-2 h-100 col-lg-3 col-9">
                    <div class="event-card position-relative">
                    @php $img = $event->image ? 'storage/'.$event->image:'images/default.svg'; @endphp
                        <div class="event-image" style="background-image: url({{ asset($img) }});">
                        </div>
                        <div class="p-4 event-info w-100">
                            <p class="font-weight-medium event-card-header mb-2">{{ $event->title }}</p>
                            <p class="mb-1 font-weight-normal" style="font-size:13px; color:rgba(30,36,51,0.6);">
                            <img src="{{asset('images/pin-g.svg')}}" alt="">{{ $event->city }}
                            </p>
                            <p class="mb-3 font-weight-normal" style="font-size:13px; color: rgba(30,36,51,0.6);">
                            <img src="{{asset('images/calendar-g.svg')}}" alt=""> {{ $event->date }}
                            </p>
                            <a href="{{ route('event.view', ['id' => $event->id]) }}">
                                <button class="btn btn-success p-0 samo-btn m-0 w-100" style="height:35px;margin: 0!important;">
                                    Смотреть
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @empty
          <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
              <img src="{{asset('images/disabled.svg')}}" alt="">
              <div class="w-100"></div>
              <span class="second-title mt-2 empty-element">Нет действующих <br> мероприятий</span>
          </div>
        @endforelse
    </div>
</div>
@include('modals.buy.success_modal')
@include('modals.buy.social_share_modal')
@endsection
@push('scripts')
<script>
    function copy_reff() {
        var reff = document.getElementById("reff_to_copy");
        reff.select();
        reff.setSelectionRange(0, 99999)
        document.execCommand("copy");
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Скопировано!',
        });
    }
    $(".reff_link_btn_share").click(function() {
        $('#success_modal').modal('hide');
        $('#social_share_modal').modal('show');
        let  event_id = this.getAttribute('event_id');
        $.ajax({
        type: 'POST',
        url: "/reff_gen",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'event_id': event_id,
        },
        success: function (data) {
            console.log(data,'aaaaaaaaaaaaaaaaaas');
            console.log(data.link,'aaaaaaaaaaaaaaaaaas');
            let fb_link = "https://www.facebook.com/sharer/sharer.php?u="+data.link;
            let vk_link = "http://vk.com/share.php?url="+data.link;
            let ok_link = "https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&amp;st.shareUrl="+data.link;
            let tw_link = "https://twitter.com/intent/tweet?url="+data.link;
            document.getElementById("fb_share_link").setAttribute('href', fb_link);
            document.getElementById("vk_share_link").setAttribute('href', vk_link);
            document.getElementById("ok_share_link").setAttribute('href', ok_link);
            document.getElementById("tw_share_link").setAttribute('href', tw_link);
            document.getElementById("reff_to_copy").value = data.link;
        },
    });

    })

    var popupSize = {
        width: 780,
        height: 550
    };

    $(document).on('click', '.social-buttons > a', function(e){

        var
            verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

        var popup = window.open($(this).prop('href'), 'social',
            'width='+popupSize.width+',height='+popupSize.height+
            ',left='+verticalPos+',top='+horisontalPos+
            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

        if (popup) {
            popup.focus();
            e.preventDefault();
        }

    });
</script>
  <script>
      $(document).ready(function(){
        let val = $("#checkTry").val();
        let text = '';
        let title = '';
        let info = '';
        if(val=='0'){
          title = 'Вы уже покупали билет!';
          info = 'info';
        }else if(val=='1'){
          info = 'info';
          title =  'У вас есть забронированный билет!';
        }else if(val==88){
          info = 'info';
          title =  'Вы не можете купить билет!';
        }else if(val=='0_admin'){
          info = 'info';
          title =  'У слушателя уже есть забронированный билет';
        }else if(val=='1_admin'){
          info = 'info';
          title =  'Слушатель уже покупал билет';
        }else if(val=='succeed'){
          $('#success_modal').modal('show');
        }else if(val=='succeded_admin'){
          info = 'success';
          title =  'Успешно куплен билет';
        }else if(val=='failed'){
          info = 'fail';
          title =  'Произошло ошибка при оплате!';
        }else if(val=='admin_need_pay'){
          info = 'success';
          title =  'Партнерство успешно создана нужно оплатить через администратора!';
        }else if(val=='partner_sell_canceled'){
          info = 'fail';
          title =  'Партнерство не оплачено!';
        }else if(val=='partner_sell_succeeded'){
          info = 'success';
          title =  'Партнерство оплачено!';
        }

        if(title){
          Swal.fire({
            position: 'center',
            icon: info,
            title: title,
            text:text,
            showConfirmButton: true,
          });
        }
      });
  </script>
@endpush
