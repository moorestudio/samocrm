@extends('layouts.admin_app')
@section('content')
  <div class="container">
      <div class="d-flex flex-wrap">
          <div class="col-6 my-3">
              <h2 class="main-title">
                  Категории
              </h2>
          </div>
          <div class="col-6 d-flex justify-content-end pr-0">
                <a class="btn btn-success" href="{{ route('create_category') }}">
                    Создать категорию
                </a>
          </div>
          @forelse($categories as $category)
            <div class="col-4 p-3">
                <div class="promo p-4 white-bg-round">
                    <p class="h5 font-weight-bold">
                        {{ $category->name }}
                    </p>
                    <div class="d-flex flex-column">
                        <p class="profile-info mb-0">Колличество мероприятий</p>
                        <p class="profile-info font-weight-bold">{{$category->eventQuantity()}}</p>
                    </div>
                    <div class="d-flex mt-4">
                    <a class="user_list_action_btn bg-transparent" href="{{ route('edit_category',['id' => $category->id]) }}">
                        <img class="mr-2" src="{{asset('images/pencil.svg')}}" alt="">
                        Редактировать
                    </a>
                    <a class="user_list_action_btn bg-transparent" href="{{ route('delete_category',['id' => $category->id]) }}" style="color: #A6ACBE;">
                        <img class="ml-5 mr-2" src="{{asset('images/delete.svg')}}" alt="">
                        Удалить
                    </a>
                    </div>
                </div>
            </div>
          @empty
            <div class="d-flex  w-100 justify-content-center flex-wrap" style="padding-top:175px;">
                <img src="{{asset('images/disabled.svg')}}" alt="">
                <div class="w-100"></div>
                <span class="second-title mt-2 empty-element">Нет активных <br> категорий</span>
            </div>
          @endforelse
      </div>
  </div>
@endsection
