<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <!-- Styles -->
</head>
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .cert-back{
        overflow:hidden;
    }
    .cert-border{
        border:25px solid #0D2580;
    }
    .samo-img{
        width: 60px;
        height: 70px;
    }
    .cert-gold-border{
        border:5px solid #A38130;
    }
    .cert-client{
        color:#333333;
        line-height: 100%;
        font-weight:bold;
    }
    .cert-back-img{
        position:absolute;
        z-index:9;
        width: 100%;
    }
    .cert-gold-border{
        position:relative;
        z-index:99;
    }
    .cert-footer{
        font-size:15px;
    }
    .cert-date{
        font-weight:normal;
    }
</style>
<body>
<!-- <div class="cerf" style="position: relative; border: 1px solid #1e2433; background:white; width:100%; height:555px;">
    <img src="{{ asset('images/certificate.jpg') }}" style="width:100%;height:100%; position: absolute; left:0%; top:0%;" alt="">
    <div style="position: absolute; left:0%; top:25.5%; width:100%; z-index:99;">
        <div style="position: relative; text-align: center;">
            <p id="cerf_user" style="margin-bottom:0px; ">Мамытовой Мээрим</p>
        </div>
    </div>
    <p id="cerf_date" style="z-index:99;margin-bottom:0px; color:#333333; position: absolute; left:48%; top:34.4%;  font-weight:bold;">01.01.2020</p>
    <div style="position: absolute; left:0%; top:29.7%; width:100%;z-index:99;">
        <div style="position: relative; text-align: center;">
            <p id="cerf_title" style="text-transform: uppercase; margin-bottom:0px; color:#1e2433;  font-weight: bold;">{{isset($cerfDesign) ? $cerfDesign->title_style['content'] : $event->title }}</p>
        </div>
    </div>
</div> -->
<div class="cerf cert-back container px-0 cert-border position-relative">
    <img class="cert-back-img" src="{{asset('images/cert-back.svg')}}" alt="">
    <div class="cert-gold-border">
        <div class="cert-border p-5">
            <div class="col-12 d-flex justify-content-start">
                <img class="samo-img" src="{{asset('images/samo.svg')}}" alt="">
            </div>    
            <div class="col-12 d-flex justify-content-center">
                <img width="50%" src="{{asset('images/cert-title.svg')}}" alt="">
            </div>
            <div class="col-12 d-flex justify-content-center flex-wrap mb-5">
                @php $name_style = isset($cerfDesign) ? $cerfDesign->name_style['font_size'] + 5 : '18'  @endphp
                <p class="mb-0 w-100 text-center cert-client my-2" style="font-size: {{$name_style}}px;">{{$user->fullName()}}</p>
                <p class="mb-0 w-100 text-center">за успешное прохождение тренинга на тему:</p>
                @php $title_style = isset($cerfDesign) ? $cerfDesign->title_style['font_size'] + 5 : '12'  @endphp
                <p class="mb-0 w-100 text-center font-weight-bold" style="font-size: {{$title_style}}px;">" {{$event->title}} "</p>
                <p class="mb-0 w-100 text-center">организованный Международным Центром Развития Человека «САМО»</p>
                @php $date_style = isset($cerfDesign) ? $cerfDesign->date_style['font_size'] + 5 : '12'   @endphp
                <p class="mb-0 w-100 text-center font-weight-bold">Дата: <span class="cert-date font-weight-bold" style="font-size: {{$date_style}}px;">{{$event->normalDate()}} - {{$event->normalEndDate()}}</span></p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <div class="col-3 d-flex justify-content-center align-items-center text-center cert-footer">
                    Президент МЦРЧ "САМО" <br>
                    Международный бизнес-тренер
                </div>
                <div class="col-6 d-flex justify-content-center"><img width="30%" src="{{asset('images/mark.png')}}" alt=""></div>
                <div class="col-3 d-flex justify-content-center align-items-center cert-footer">C.Р.Давлатов</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    $(document).ready(function(){
        // window.print();
    });
</script>
