@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтвердите Вашу почту чтобы продолжить') }}</div>

                <div class="card-body">
                    {{-- На указанную Вами почту:{{$user_email}} было {{ isset($re) ? "переотправлено" : 'отправлено' }} --}}
                    На указанную Вами почту было отправлено
                    письмо с паролем на Вашу учетную запись и ссылкой для подтверждения почты.
                    {{-- Если Вы не получили письмо, <a href="{{ route('email_re_send') }}">нажмите сюда чтобы запросить новую.</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
