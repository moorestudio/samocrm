@component('mail::message')


    <html>
    <body>
    <div style="padding:7%; border:4px #000000 solid; margin-bottom:5%; position: relative">
        {{--        @dd($newCart->name)--}}
        <h2>Заявка на связь {{$user->last_name}} {{$user->name}} {{$user->middle_name}}</h2>
        <br>
        <br>
        {{--@dd($newCart)--}}
        <strong>Контактный номер: </strong> {{$phone}}<br>
        <strong>Комментарий: </strong> <br>
        <p>{{ $comment }}</p>
    </div>
    </body>
    </html>
@endcomponent
