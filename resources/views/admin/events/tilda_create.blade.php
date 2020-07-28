@extends('layouts.admin_app')
@push('styles')
    <style>
        label
        {
            color: rgba(0, 0, 0, 0.51);
        }
    </style>
@endpush
@section('content')
  <div class="container">
      <div class="row">
        <div class="col-12 pb-4">
            <div class="d-flex justify-content-between">
                <p class="main-title text-uppercase text-lg-left text-center">Лендинг с тилды</p>
            </div>
        </div>
        <div class="col-12">
            <form action="{{route('tilda_store')}}" method="POST">
              <div class="d-flex mb-5">
                @csrf
                <input type="hidden" name="id" value="{{$event->id}}">
                <div class="col-lg-3 col-12 pt-3 d-none">
                    <label for="event_name" style="color:#6B6B6B;">Введите publickey*</label>
                    <div class="mt-0">
                        <input placeholder="publickey*" type="text" name="publickey" id="publickey" class="form-control input-style" value="{{ isset($tilda) ? $tilda->publickey : '' }}" required>
                    </div>
                </div>
                <div class="col-lg-3 col-12 pt-3 d-none">
                    <label for="event_date" style="color:#6B6B6B;">Введите secretkey*</label>
                    <div class="mt-0">
                        <input placeholder="secretkey*" type="text" id="secretkey" name="secretkey" class="form-control input-style" value="{{ isset($tilda) ? $tilda->secretkey : '' }}" required>
                    </div>
                </div>
                <div class="col-lg-3 col-12 pt-3">
                    <label for="event_name" style="color:#6B6B6B;">Ссылка для покупки </label>
                    <div class="mt-0">
                        <input placeholder="publickey*" type="text" name="publickey" id="publickey" class="form-control input-style" value="/buy/{{ $event->id }}" disabled> 
                    </div>        
                </div>
                <div class="col-lg-3 col-12 pt-3">
                    <label for="event_date" style="color:#6B6B6B;">Введите pageid*</label>
                    <div class="mt-0">
                        <input placeholder="pageid*" type="text" id="pageid" name="pageid" class="form-control  input-style" value="{{ isset($event) ? $event->tilda_pageid : '' }}" required>
                    </div>
                </div>
                <div class="col-lg-3 col-12 pt-3 d-flex align-items-end">
                  <button type="submit" class="btn btn-success m-0">
                      Отправить
                  </button>
                </div>
              </div>
            </form>
        </div>
      </div>
  </div>
  <div class="tilda_block">
    @if(isset($event))
      @if($event->tilda_pageid)
        @include('tilda_templates/'.$event->tilda_pageid.'/main')
      @endif
    @endif
  </div>
@endsection
