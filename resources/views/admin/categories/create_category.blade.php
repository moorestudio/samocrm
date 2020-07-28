@extends('layouts.admin_app')
@section('content')
  <div class="container my-3">
      <h2 class="main-title">{{isset($category) ?  'Изменение категории' : 'Создание категории' }}</h2>
      <form class="mt-4" action="{{ isset($category) ? route('update_category') : route('store_category') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="p-5 white-bg-round">
            <input type="hidden" name="id" id="id" value="{{isset($category) ? $category->id : ''}}">
            <label for="name">Название категории</label>
            <div class="col-5 pl-0">
                <input placeholder="Название категории*" type="text" id="name" name="name" class="form-control input-style grey-bg" value="{{ isset($category) ? $category->name : '' }}" required>
            </div>
            <div class="d-flex mt-5 pb-5">
                <button class="btn btn-success ml-0" type="submit">
                    Сохранить
                </button>
                <a class="btn li-btn btn-cancel" href="/category">Отмена</a>
            </div>
          </div>
      </form>
  </div>
@endsection
