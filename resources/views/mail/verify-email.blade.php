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
    max-width: 250px;
    margin: auto;
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
<div class="email_body_class">
	<p class="email_body_bold">Здравствуйте!</p>
	<p class="email_body_thanx">Спасибо, что присоединились к САМО — одну из лидирующих центров тренинговой индустрии по СНГ.</p>
	<p class="email_body_bold">Ваши данные для входа:</p>
	<span>E-mail:</span><br>
	<span style="color: #3F8CFF; font-weight: bold;">{{$email}}</span><br>
	<span>Пароль:</span>
	<span style="font-weight: bold;"> {{$pass}}</span><br>
	<span class="email_body_bold" style="display: block;margin-top: 5%;">Для подтверждения Email нажмите на кнопку ниже:</span><br>
	<div class="email_body_button">
    <a href="{{url('/users/'.$id.'/'.$token)}}" style="color: white;">Подтвердить почту</a>
	</div>
</div>
</div>
</body>
</html>
