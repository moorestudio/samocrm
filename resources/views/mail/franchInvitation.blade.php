<html>
<head>
<style>
.email_wrapper_class{
    max-width: 700px;
    margin: auto;
    box-shadow: 0px 20px 45px rgba(0, 0, 0, 0.11);
    margin-bottom:5%;
    position: relative;
    border-radius: 10px;
}
.email_header_class{
    padding: 5%;
    background-color: #0C69C3;
    border-radius: 10px 10px 0 0;
}
.email_body_class{
    padding: 5%;
}
.email_body_bold{
    font-size: 21px;
    font-weight: bold;
}
.email_body_thanx{
    font-size: 17px
}
.email_body_button{
    text-align: center;
    margin-top: 5%;
    font-size: 21px;
    background-color: #0C69C3;
    padding: 10px 10px;
    border-radius: 10px;
}
.email_header_class img{
    width: 15%;
}
@media screen and (max-width:500px){
    .email_wrapper_class{
        width: 100%;
        }
    .email_body_class{
        padding: 5%;
    }
    .email_body_bold,.email_body_thanx{
        font-size: 15px
    }
    .email_body_button{
        margin-top: 2%;
    }
    .email_header_class img{
        width: 20%;
    }
}
</style>
</head>
<body>
<div class="email_wrapper_class">
<div class="email_header_class" >
    {{-- <img src="{{ asset('images/mail.png') }}" style="display: block;margin: auto;" alt=""> --}}
    <img src="https://i.ibb.co/d0j49Sh/mail.png" style="display: block;margin: auto;" alt="">
</div>    
<div style="padding: 10%;">
    <p style="font-weight: bold; font-size: 21px;">Здравствуйте!</p>
    <p  style="font-size: 18px;">Вы зарегистрировались в SAMO Tickets (CAMO CRM) и после подтверждения почты, Вы можете выкупать билеты на все мероприятия САМО и следить за новостями. </p>
    <p style="font-weight: bold; font-size: 21px;">Ваши данные для входа:</p>
    <span>E-mail:</span><br>
    <span style="color: #3F8CFF; font-weight: bold;">{{$email}}</span><br>
    <span>Пароль:</span>
    <span style="font-weight: bold;"> {{$pass}}</span><br>
    <div style="text-align: center;">
    <a href="{{$log_url}}" class="email_body_bold">Пройдите по этой ссылке для перехода на сайт</a>
    </div>
</div>
</div>
</body>
</html>