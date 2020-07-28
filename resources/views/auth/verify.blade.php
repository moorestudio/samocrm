@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтвердите Вашу почту чтобы продолжить') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Ссылка для подтверждения почты была переотправлена.') }}
                        </div>
                    @endif

                    {{ __('Прежде чем продолжить прверьте свою почту на наличие ссылки для подтверждения почты.') }}
                    {{ __('Если Вы не получили письмо') }}, <a href="{{ route('verification.resend') }}">{{ __('нажмите сюда чтобы запросить новую.') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
